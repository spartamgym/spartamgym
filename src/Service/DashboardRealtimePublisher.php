<?php

namespace App\Service;

use App\Entity\Usuario;
use App\Repository\ColaCardsRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

final class DashboardRealtimePublisher
{
    public const TOPIC = 'https://spartamgym.local/dashboard';

    public function __construct(
        private HubInterface $hub,
        private ColaCardsRepository $colaCardsRepository,
        private ReferenciaCorporalAutomaticaService $referenciaCorporalAutomaticaService,
        private LoggerInterface $logger
    ) {}

    public function buildSnapshot(): array
    {
        return [
            'queue' => $this->buildQueue(),
            'user' => null,
            'event_at' => (new \DateTimeImmutable())->format(DATE_ATOM),
        ];
    }

    public function publishQueueUpdated(): void
    {
        $payload = [
            'type' => 'queue.updated',
            'queue' => $this->buildQueue(),
            'event_at' => (new \DateTimeImmutable())->format(DATE_ATOM),
        ];
        $this->publishPayload($payload);
    }

    public function publishUserSelected(?Usuario $usuario, string $origin = 'system'): void
    {
        if ($usuario instanceof Usuario) {
            $this->referenciaCorporalAutomaticaService->ensureActiveFromUsuario($usuario);
        }

        $payload = [
            'type' => 'user.selected',
            'origin' => $origin,
            'user' => $usuario instanceof Usuario ? $usuario->toArray() : null,
            'event_at' => (new \DateTimeImmutable())->format(DATE_ATOM),
        ];
        $this->publishPayload($payload);
    }

    private function buildQueue(): array
    {
        $queue = $this->colaCardsRepository->getAllCardsActive();
        return array_map(static function ($item): array {
            $usuario = $item->getUsuario();
            return [
                'id' => $item->getId(),
                'code' => $item->getCode(),
                'usuario' => [
                    'id' => $usuario?->getId(),
                    'nombre' => $usuario?->getNombre(),
                    'cedula' => $usuario?->getCedula(),
                ],
            ];
        }, $queue);
    }

    private function publishPayload(array $payload): void
    {
        try {
            $this->hub->publish(new Update(
                self::TOPIC,
                json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            ));
        } catch (\Throwable $e) {
            $this->logger->error('No se pudo publicar evento de dashboard en Mercure', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
