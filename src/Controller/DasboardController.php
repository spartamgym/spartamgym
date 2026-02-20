<?php

namespace App\Controller;

use App\Entity\IaSolicitudLog;
use App\Entity\RutinaGenerada;
use App\Repository\IaSolicitudLogRepository;
use App\Repository\RutinaGeneradaRepository;
use App\Repository\UsuarioRepository;
use App\Service\DashboardRealtimePublisher;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class DasboardController extends AbstractController
{
    private static ?FilesystemAdapter $iaRateLimiterCache = null;
    private static ?int $lastIaRetentionRunAt = null;

    public function __construct(
        private DashboardRealtimePublisher $dashboardRealtimePublisher
    ) {}

    #[Route('/dasboard', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('dasboard/index.html.twig', [
            'mercure_topic' => DashboardRealtimePublisher::TOPIC,
        ]);
    }

    #[Route('/dashboard/realtime/snapshot', name: 'app_dashboard_realtime_snapshot', methods: ['GET'])]
    public function snapshot(): JsonResponse
    {
        return new JsonResponse($this->dashboardRealtimePublisher->buildSnapshot());
    }

    #[Route('/dashboard/ia/spec', name: 'app_dashboard_ai_spec', methods: ['GET'])]
    public function iaSpec(): JsonResponse
    {
        $webhookUrl = $this->extractEnvString('IA_WEBHOOK_URL');
        $maxRutinasPorMes = max(1, $this->extractEnvInt('IA_MAX_RUTINAS_PER_MONTH', 2));

        return new JsonResponse([
            'status' => 'success',
            'endpoint' => [
                'configured' => $webhookUrl !== '',
                'host' => parse_url($webhookUrl, PHP_URL_HOST) ?: null,
            ],
            'required_payload' => [
                ['field' => 'fullName', 'type' => 'string'],
                ['field' => 'cedula', 'type' => 'string'],
                ['field' => 'availableDays', 'type' => 'integer', 'range' => '1-7'],
                ['field' => 'currentLevel', 'type' => 'string'],
                ['field' => 'timePerSessionMin', 'type' => 'integer', 'min' => 1],
                ['field' => 'weight', 'type' => 'number', 'min' => 1],
            ],
            'optional_payload' => [
                ['field' => 'hasDisabilities', 'type' => 'boolean'],
                ['field' => 'disabilityDescription', 'type' => 'string'],
                ['field' => 'additionalInfo', 'type' => 'string'],
            ],
            'constraints' => [
                'max_rutinas_por_mes' => $maxRutinasPorMes,
                'max_payload_bytes' => $this->extractEnvInt('IA_MAX_PAYLOAD_BYTES', 16384),
            ],
        ]);
    }

    #[Route('/dashboard/ia/rutina', name: 'app_dashboard_ai_rutina', methods: ['POST'])]
    public function generarRutinaIa(
        Request $request,
        HttpClientInterface $httpClient,
        IaSolicitudLogRepository $iaSolicitudLogRepository,
        UsuarioRepository $usuarioRepository,
        RutinaGeneradaRepository $rutinaGeneradaRepository
    ): JsonResponse
    {
        $payloadRaw = $request->getContent();
        $payload = json_decode($payloadRaw, true);
        $payloadArray = is_array($payload) ? $payload : null;
        $usuarioNombre = is_array($payloadArray) ? trim((string) ($payloadArray['fullName'] ?? '')) : null;
        $usuarioCedula = is_array($payloadArray) ? trim((string) ($payloadArray['cedula'] ?? '')) : null;
        $clientIp = $request->getClientIp();

        $saveLog = function (
            string $estado,
            ?string $responseHash = null,
            ?string $errorMensaje = null
        ) use ($iaSolicitudLogRepository, $payloadArray, &$usuarioNombre, &$usuarioCedula, $clientIp): void {
            $log = new IaSolicitudLog();
            $log->setEstado($estado);
            $log->setUsuarioNombre($usuarioNombre !== '' ? $usuarioNombre : null);
            $log->setUsuarioCedula($usuarioCedula !== '' ? $usuarioCedula : null);
            $log->setIp($clientIp);
            $log->setResponseHash($responseHash);
            $log->setErrorMensaje($errorMensaje);
            $log->setPayloadJson($payloadArray ? json_encode($payloadArray, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null);
            $iaSolicitudLogRepository->save($log);
        };

        $this->runIaLogRetention($iaSolicitudLogRepository);

        $maxPayloadBytes = $this->extractEnvInt('IA_MAX_PAYLOAD_BYTES', 16384);
        if (strlen($payloadRaw) > $maxPayloadBytes) {
            $saveLog('error_payload_size', null, 'Payload excede el tamaño permitido.');
            return new JsonResponse([
                'status' => 'error',
                'message' => 'La solicitud excede el tamaño permitido.',
            ], 413);
        }

        $payloadError = $this->validateRutinaPayload($payloadArray);
        if ($payloadError !== null) {
            $saveLog('error_payload', null, $payloadError);
            return new JsonResponse([
                'status' => 'error',
                'message' => $payloadError,
            ], 400);
        }

        $usuarioCedula = (string) $payloadArray['cedula'];
        $usuario = $usuarioRepository->findOneBy(['cedula' => $usuarioCedula]);
        if (!$usuario instanceof \App\Entity\Usuario) {
            $saveLog('error_usuario', null, 'No existe usuario registrado con la cédula enviada.');
            return new JsonResponse([
                'status' => 'error',
                'message' => 'No existe usuario registrado con la cédula enviada.',
            ], 404);
        }

        $usuarioNombre = trim((string) ($payloadArray['fullName'] ?? $usuario->getNombre() ?? ''));
        [$monthStart, $monthEnd] = $this->resolveCurrentMonthRange();
        $maxRutinasPorMes = max(1, $this->extractEnvInt('IA_MAX_RUTINAS_PER_MONTH', 2));
        $rutinasMesActual = $rutinaGeneradaRepository->countByUsuarioAndRange($usuario, $monthStart, $monthEnd);
        if ($rutinasMesActual >= $maxRutinasPorMes) {
            $saveLog('error_limite_mensual', null, 'Se alcanzó el máximo de rutinas por mes.');
            return new JsonResponse([
                'status' => 'error',
                'message' => sprintf(
                    'Límite mensual alcanzado: máximo %d rutinas por usuario en el mes actual.',
                    $maxRutinasPorMes
                ),
                'limite_mensual' => [
                    'max' => $maxRutinasPorMes,
                    'usadas' => $rutinasMesActual,
                    'disponibles' => 0,
                    'mes' => $monthStart->format('Y-m'),
                ],
            ], 429);
        }

        $rateLimitError = $this->enforceIaRateLimit($request);
        if ($rateLimitError instanceof JsonResponse) {
            $saveLog('error_rate_limit', null, 'Límite de solicitudes IA por IP excedido.');
            return $rateLimitError;
        }

        $webhookUrl = $_ENV['IA_WEBHOOK_URL'] ?? $_SERVER['IA_WEBHOOK_URL'] ?? '';
        if ($webhookUrl === '') {
            $saveLog('error_config', null, 'IA webhook no configurado en el entorno.');
            return new JsonResponse([
                'status' => 'error',
                'message' => 'IA webhook no configurado en el entorno.',
            ], 500);
        }

        try {
            $timeoutSeconds = max(5, $this->extractEnvInt('IA_WEBHOOK_TIMEOUT_SECONDS', 45));
            $maxRetries = max(0, $this->extractEnvInt('IA_WEBHOOK_MAX_RETRIES', 2));
            $retryDelayMs = max(0, $this->extractEnvInt('IA_WEBHOOK_RETRY_DELAY_MS', 400));

            $response = null;
            $statusCode = 0;
            $rawBody = '';
            for ($attempt = 0; $attempt <= $maxRetries; $attempt++) {
                try {
                    $response = $httpClient->request('POST', $webhookUrl, [
                        'json' => $payloadArray,
                        'timeout' => $timeoutSeconds,
                    ]);
                    $statusCode = $response->getStatusCode();
                    $rawBody = trim($response->getContent(false));

                    $retryableStatus = $statusCode >= 500;
                    $retryableEmptyBody = $statusCode < 400 && $rawBody === '';
                    if (($retryableStatus || $retryableEmptyBody) && $attempt < $maxRetries) {
                        if ($retryDelayMs > 0) {
                            usleep($retryDelayMs * 1000);
                        }
                        continue;
                    }
                    break;
                } catch (TransportExceptionInterface $transportException) {
                    if ($attempt < $maxRetries) {
                        if ($retryDelayMs > 0) {
                            usleep($retryDelayMs * 1000);
                        }
                        continue;
                    }
                    throw $transportException;
                }
            }

            if (!$response) {
                $saveLog('error_provider', null, 'No fue posible obtener respuesta del servicio IA.');
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'El servicio IA no respondió.',
                ], 502);
            }

            if ($statusCode >= 400) {
                $saveLog('error_provider', null, sprintf('El servicio IA respondió con error HTTP %d.', $statusCode));
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'El servicio IA respondió con error.',
                ], 502);
            }

            if ($rawBody === '') {
                $saveLog('error_empty', null, 'El servicio IA respondió vacío.');
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'El servicio IA respondió vacío.',
                ], 502);
            }

            $decoded = json_decode($rawBody, true);
            $result = is_array($decoded) ? $decoded : ['content' => $rawBody];
            $rawContent = $result['output'] ?? $result['response'] ?? $result['content'] ?? $rawBody;

            if (is_array($rawContent)) {
                $rawContent = json_encode($rawContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
            if (!is_string($rawContent) || trim($rawContent) === '') {
                $rawContent = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }

            $cleanText = trim((string) $rawContent);
            $safeHtml = nl2br(htmlspecialchars($cleanText, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
            $responseHash = hash('sha256', $cleanText);

            $rutina = new RutinaGenerada();
            $rutina->setUsuario($usuario);
            $rutina->setUsuarioNombre($usuarioNombre !== '' ? $usuarioNombre : $usuario->getNombre());
            $rutina->setUsuarioCedula($usuarioCedula);
            $rutina->setPayloadJson((string) json_encode($payloadArray, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $rutina->setContenidoTexto($cleanText);
            $rutina->setContenidoHtml($safeHtml);
            $rutina->setResponseHash($responseHash);
            $rutinaGeneradaRepository->save($rutina);

            $saveLog('success', $responseHash, null);

            return new JsonResponse([
                'status' => 'success',
                'content_text' => $cleanText,
                'content_html' => $safeHtml,
                'rutina' => $this->buildRutinaArray($rutina),
                'limite_mensual' => [
                    'max' => $maxRutinasPorMes,
                    'usadas' => $rutinasMesActual + 1,
                    'disponibles' => max(0, $maxRutinasPorMes - ($rutinasMesActual + 1)),
                    'mes' => $monthStart->format('Y-m'),
                ],
            ]);
        } catch (\Throwable $e) {
            $saveLog('error_exception', null, $e->getMessage());
            return new JsonResponse([
                'status' => 'error',
                'message' => 'No fue posible procesar la solicitud IA.',
            ], 500);
        }
    }

    #[Route('/dashboard/ia/pdf', name: 'app_dashboard_ai_pdf', methods: ['POST'])]
    public function descargarRutinaPdf(Request $request, RutinaGeneradaRepository $rutinaGeneradaRepository): Response
    {
        $payload = json_decode($request->getContent(), true);
        if (!is_array($payload)) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Payload inválido.',
            ], 400);
        }

        $rutinaId = (int) ($payload['rutina_id'] ?? 0);
        $rutina = null;
        if ($rutinaId > 0) {
            $rutina = $rutinaGeneradaRepository->find($rutinaId);
            if (!$rutina instanceof RutinaGenerada) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Rutina no encontrada.',
                ], 404);
            }
        }

        $content = trim((string) ($payload['content'] ?? ''));
        $nombre = trim((string) ($payload['nombre'] ?? ''));

        if ($rutina instanceof RutinaGenerada) {
            if ($content === '') {
                $content = trim((string) ($rutina->getContenidoHtml() ?: ''));
                if ($content === '') {
                    $content = nl2br(htmlspecialchars((string) $rutina->getContenidoTexto(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
                }
            }
            if ($nombre === '') {
                $nombre = trim((string) ($rutina->getUsuarioNombre() ?: ''));
            }
        }

        if ($content === '') {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'No hay contenido para generar PDF.',
            ], 400);
        }

        if ($nombre === '') {
            $nombre = 'socio';
        }

        $safeContent = strip_tags($content, '<br><strong><b><em><i><ul><ol><li><h1><h2><h3><p>');
        $html = $this->renderView('dasboard/rutina_pdf.html.twig', [
            'nombre' => $nombre,
            'contenido' => $safeContent,
            'fecha' => new \DateTimeImmutable(),
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfBinary = $dompdf->output();
        $slug = preg_replace('/[^A-Za-z0-9_-]+/', '-', $nombre) ?: 'socio';
        $filename = sprintf('rutina-%s-%s.pdf', $slug, (new \DateTimeImmutable())->format('Ymd_His'));

        $response = new Response($pdfBinary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);

        if ($rutina instanceof RutinaGenerada) {
            $this->storeRutinaPdf($rutina, $pdfBinary, $rutinaGeneradaRepository);
            if ($rutina->getPdfFilename()) {
                $response->headers->set(
                    'Content-Disposition',
                    'attachment; filename="' . $rutina->getPdfFilename() . '"'
                );
            }
            $response->headers->set(
                'X-Rutina-Download-Url',
                $this->generateUrl(
                    'app_dashboard_ai_rutina_pdf_download',
                    ['id' => $rutina->getId()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            );
        }

        return $response;
    }

    #[Route('/dashboard/ia/rutina/{id}/pdf', name: 'app_dashboard_ai_rutina_pdf_download', methods: ['GET'])]
    public function descargarRutinaPdfGuardada(int $id, RutinaGeneradaRepository $rutinaGeneradaRepository): Response
    {
        $rutina = $rutinaGeneradaRepository->find($id);
        if (!$rutina instanceof RutinaGenerada) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Rutina no encontrada.',
            ], 404);
        }

        $pdfPath = $rutina->getPdfPath();
        if (!$pdfPath) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'La rutina seleccionada aún no tiene PDF generado.',
            ], 404);
        }

        $absolutePath = $this->getParameter('kernel.project_dir') . '/public/' . ltrim($pdfPath, '/');
        if (!is_file($absolutePath)) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'El archivo PDF no existe en almacenamiento.',
            ], 404);
        }

        $response = new BinaryFileResponse($absolutePath);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $rutina->getPdfFilename() ?: basename($absolutePath)
        );
        $response->headers->set('Content-Type', 'application/pdf');

        return $response;
    }

    #[Route('/dashboard/ia/rutinas', name: 'app_dashboard_ai_rutinas', methods: ['GET'])]
    public function listarRutinasPorCedula(Request $request, RutinaGeneradaRepository $rutinaGeneradaRepository): JsonResponse
    {
        $cedula = trim((string) $request->query->get('cedula', ''));
        if ($cedula === '') {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'La cédula es obligatoria para consultar rutinas.',
            ], 400);
        }

        $limit = (int) $request->query->get('limit', 20);
        if ($limit <= 0) {
            $limit = 20;
        }
        $limit = min($limit, 100);

        [$monthStart, $monthEnd] = $this->resolveCurrentMonthRange();
        $maxRutinasPorMes = max(1, $this->extractEnvInt('IA_MAX_RUTINAS_PER_MONTH', 2));
        $rutinasMesActual = $rutinaGeneradaRepository->countByCedulaAndRange($cedula, $monthStart, $monthEnd);
        $rutinas = $rutinaGeneradaRepository->findByCedulaLatest($cedula, $limit);

        return new JsonResponse([
            'status' => 'success',
            'cedula' => $cedula,
            'limite_mensual' => [
                'max' => $maxRutinasPorMes,
                'usadas' => $rutinasMesActual,
                'disponibles' => max(0, $maxRutinasPorMes - $rutinasMesActual),
                'mes' => $monthStart->format('Y-m'),
            ],
            'items' => array_map(
                fn(RutinaGenerada $rutina) => $this->buildRutinaArray($rutina),
                $rutinas
            ),
        ]);
    }

    private function validateRutinaPayload(?array $payload): ?string
    {
        if (!is_array($payload)) {
            return 'Payload inválido.';
        }

        $requiredFields = ['fullName', 'cedula', 'availableDays', 'currentLevel', 'timePerSessionMin', 'weight'];
        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $payload)) {
                return sprintf('Falta el campo obligatorio "%s".', $field);
            }
        }

        $fullName = trim((string) $payload['fullName']);
        $cedula = trim((string) $payload['cedula']);
        $currentLevel = trim((string) $payload['currentLevel']);
        if ($fullName === '' || $cedula === '' || $currentLevel === '') {
            return 'Los campos fullName, cedula y currentLevel son obligatorios.';
        }

        $availableDays = $payload['availableDays'];
        $timePerSessionMin = $payload['timePerSessionMin'];
        $weight = $payload['weight'];
        if (!is_numeric($availableDays) || (int) $availableDays < 1 || (int) $availableDays > 7) {
            return 'availableDays debe estar entre 1 y 7.';
        }
        if (!is_numeric($timePerSessionMin) || (int) $timePerSessionMin <= 0) {
            return 'timePerSessionMin debe ser mayor a cero.';
        }
        if (!is_numeric($weight) || (float) $weight <= 0) {
            return 'weight debe ser mayor a cero.';
        }

        return null;
    }

    private function resolveCurrentMonthRange(): array
    {
        $monthStart = (new \DateTimeImmutable('first day of this month'))->setTime(0, 0, 0);
        $monthEnd = $monthStart->modify('+1 month');

        return [$monthStart, $monthEnd];
    }

    private function buildRutinaArray(RutinaGenerada $rutina): array
    {
        $pdfUrl = null;
        if ($rutina->getPdfPath()) {
            $pdfUrl = $this->generateUrl(
                'app_dashboard_ai_rutina_pdf_download',
                ['id' => $rutina->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
        }

        $preview = trim((string) $rutina->getContenidoTexto());
        if (strlen($preview) > 240) {
            $preview = substr($preview, 0, 240) . '...';
        }

        return [
            'id' => $rutina->getId(),
            'usuario_nombre' => $rutina->getUsuarioNombre(),
            'usuario_cedula' => $rutina->getUsuarioCedula(),
            'created_at' => $rutina->getCreatedAt()?->format(DATE_ATOM),
            'preview' => $preview,
            'pdf_filename' => $rutina->getPdfFilename(),
            'pdf_download_url' => $pdfUrl,
        ];
    }

    private function storeRutinaPdf(
        RutinaGenerada $rutina,
        string $pdfBinary,
        RutinaGeneradaRepository $rutinaGeneradaRepository
    ): void {
        $cedulaSegment = $this->sanitizePathSegment((string) $rutina->getUsuarioCedula());
        if ($cedulaSegment === '') {
            $cedulaSegment = 'sin-cedula';
        }

        $relativeDirectory = sprintf('uploads/rutinas/%s', $cedulaSegment);
        $absoluteDirectory = $this->getParameter('kernel.project_dir') . '/public/' . $relativeDirectory;
        if (!is_dir($absoluteDirectory) && !mkdir($absoluteDirectory, 0775, true) && !is_dir($absoluteDirectory)) {
            throw new \RuntimeException('No fue posible crear el directorio de PDFs de rutinas.');
        }

        $filename = sprintf('rutina-%d-%s.pdf', $rutina->getId(), (new \DateTimeImmutable())->format('Ymd_His'));
        $absolutePath = $absoluteDirectory . '/' . $filename;
        if (file_put_contents($absolutePath, $pdfBinary) === false) {
            throw new \RuntimeException('No fue posible guardar el PDF de rutina.');
        }

        $rutina->setPdfPath($relativeDirectory . '/' . $filename);
        $rutina->setPdfFilename($filename);
        $rutinaGeneradaRepository->save($rutina);
    }

    private function sanitizePathSegment(string $value): string
    {
        return trim((string) preg_replace('/[^A-Za-z0-9_-]+/', '-', $value), '-');
    }

    #[Route('/dashboard/ia/logs', name: 'app_dashboard_ai_logs', methods: ['GET'])]
    public function listarLogsIa(Request $request, IaSolicitudLogRepository $iaSolicitudLogRepository): JsonResponse
    {
        $logsAuthError = $this->ensureIaLogsAuthorization($request);
        if ($logsAuthError instanceof JsonResponse) {
            return $logsAuthError;
        }

        $this->runIaLogRetention($iaSolicitudLogRepository);

        $limit = (int) $request->query->get('limit', 50);
        if ($limit <= 0) {
            $limit = 50;
        }
        $limit = min($limit, 200);

        $logs = $iaSolicitudLogRepository->findBy([], ['id' => 'DESC'], $limit);
        return new JsonResponse(array_map(
            static fn(IaSolicitudLog $log) => $log->toArray(),
            $logs
        ));
    }

    private function ensureIaLogsAuthorization(Request $request): ?JsonResponse
    {
        $expectedToken = $this->extractEnvString('IA_LOGS_TOKEN');
        if ($expectedToken === '') {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Endpoint de logs IA deshabilitado por configuración.',
            ], 403);
        }

        $providedToken = (string) ($request->headers->get('X-IA-Logs-Token') ?? '');
        if ($providedToken === '') {
            $providedToken = (string) $request->query->get('token', '');
        }

        if (!hash_equals($expectedToken, $providedToken)) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'No autorizado para consultar logs IA.',
            ], 401);
        }

        return null;
    }

    private function enforceIaRateLimit(Request $request): ?JsonResponse
    {
        $enabledRaw = strtolower($this->extractEnvString('IA_RATE_LIMIT_ENABLED', '1'));
        if (in_array($enabledRaw, ['0', 'false', 'off', 'no'], true)) {
            return null;
        }

        $windowSeconds = max(1, $this->extractEnvInt('IA_RATE_LIMIT_WINDOW_SECONDS', 60));
        $maxRequests = max(1, $this->extractEnvInt('IA_RATE_LIMIT_MAX_REQUESTS', 8));
        $ip = $request->getClientIp() ?: 'unknown';

        if (!self::$iaRateLimiterCache instanceof FilesystemAdapter) {
            self::$iaRateLimiterCache = new FilesystemAdapter('ia_rate_limit');
        }

        $cacheKey = 'ip_' . hash('sha256', $ip);
        $item = self::$iaRateLimiterCache->getItem($cacheKey);
        $now = time();
        $state = $item->isHit() ? $item->get() : null;
        if (!is_array($state)) {
            $state = ['count' => 0, 'reset_at' => $now + $windowSeconds];
        }

        $resetAt = (int) ($state['reset_at'] ?? 0);
        if ($resetAt <= $now) {
            $state = ['count' => 0, 'reset_at' => $now + $windowSeconds];
            $resetAt = (int) $state['reset_at'];
        }

        $state['count'] = ((int) ($state['count'] ?? 0)) + 1;
        $retryAfter = max(1, $resetAt - $now);
        $item->set($state);
        $item->expiresAfter($retryAfter);
        self::$iaRateLimiterCache->save($item);

        if ($state['count'] > $maxRequests) {
            return new JsonResponse([
                'status' => 'error',
                'message' => sprintf('Demasiadas solicitudes IA. Intenta de nuevo en %d segundos.', $retryAfter),
            ], 429, [
                'Retry-After' => (string) $retryAfter,
            ]);
        }

        return null;
    }

    private function runIaLogRetention(IaSolicitudLogRepository $iaSolicitudLogRepository): void
    {
        $retentionDays = $this->extractEnvInt('IA_LOG_RETENTION_DAYS', 90);
        if ($retentionDays <= 0) {
            return;
        }

        $intervalSeconds = max(300, $this->extractEnvInt('IA_LOG_RETENTION_INTERVAL_SECONDS', 3600));
        $now = time();
        if (self::$lastIaRetentionRunAt !== null && ($now - self::$lastIaRetentionRunAt) < $intervalSeconds) {
            return;
        }

        self::$lastIaRetentionRunAt = $now;
        $threshold = (new \DateTimeImmutable('today'))->modify(sprintf('-%d day', $retentionDays));
        $iaSolicitudLogRepository->deleteOlderThan($threshold);
    }

    private function extractEnvString(string $name, string $default = ''): string
    {
        $value = $_ENV[$name] ?? $_SERVER[$name] ?? $default;
        return trim((string) $value);
    }

    private function extractEnvInt(string $name, int $default): int
    {
        $value = $this->extractEnvString($name, (string) $default);
        return is_numeric($value) ? (int) $value : $default;
    }
}
