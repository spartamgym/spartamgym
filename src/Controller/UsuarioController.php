<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UsuarioRepository;
use App\Repository\DatoFisicoRepository;
use App\Repository\PlanUsuarioRepository;
use App\Service\ReferenciaCorporalAutomaticaService;

final class UsuarioController extends AbstractController
{
    #[Route('/usuario', name: 'app_usuario')]
    public function index(): Response
    {
        return $this->render('usuario/index.html.twig', [
            'controller_name' => 'UsuarioController',
        ]);
    }

    #[Route('/usuario/registro', name: 'app_usuario_registro')]
    public function registro(
        Request $request,
        UsuarioRepository $usuarioRepository
    ): JsonResponse
    {
        $id = (int) $request->request->get('id', 0);
        $isNew = $id <= 0;
        /** @var UploadedFile|null $imagen */
        $img = $request->files->get('img');
        $nombre = $request->request->get('nombre');
        $cedula = $request->request->get('cedula');
        $celular = $request->request->get('celular');
        $direccion = $request->request->get('direccion');
        $fecha_nacimiento = $request->request->get('fecha_nacimiento');
        $sexo = strtoupper(trim((string) $request->request->get('sexo', '')));
        $eps = $request->request->get('eps');
        $correo = $request->request->get('correo');

        //validar todos los campos
        if (empty($nombre) || empty($cedula) || empty($celular) || empty($direccion) || empty($fecha_nacimiento) || empty($eps) || empty($correo)) {
            return new JsonResponse(['status' => 'error', 'message' => 'Todos los campos son obligatorios.'], 400);
        }

        if ($sexo !== '' && !in_array($sexo, ['M', 'F', 'O'], true)) {
            return new JsonResponse(['status' => 'error', 'message' => 'Sexo inválido.'], 400);
        }

        if ($isNew && $sexo === '') {
            return new JsonResponse(['status' => 'error', 'message' => 'El sexo es obligatorio para nuevos usuarios.'], 400);
        }


        if (!$isNew) {
            $usuario = $usuarioRepository->find($id);
            if (!$usuario instanceof \App\Entity\Usuario) {
                return new JsonResponse(['status' => 'error', 'message' => 'Usuario no encontrado.'], 404);
            }
        } else {
            //validar que no exista otro usuario con la misma cedula
            $existingUsuario = $usuarioRepository->findOneBy(['cedula' => $cedula]);
            if ($existingUsuario instanceof \App\Entity\Usuario) {
                return new JsonResponse(['status' => 'error', 'message' => 'Ya existe un usuario con la misma cédula.'], 400);
            }
            $usuario = new \App\Entity\Usuario();
            $usuario->setCedula($cedula);
        }
        $usuario->setNombre($nombre);

        $usuario->setCelular($celular);
        $usuario->setDireccion($direccion);
        $usuario->setFechaNacimiento(new \DateTime($fecha_nacimiento));
        $usuario->setSexo($sexo !== '' ? $sexo : $usuario->getSexo());
        $usuario->setEps($eps);
        $usuario->setCorreo($correo);
        if ($img) {
            $fileName = md5(uniqid()) . '.' . $img->guessExtension();
            $img->move(
                $this->getParameter('kernel.project_dir') . '/public/img',
                $fileName
            );

            //eliminar la imagen antigua
            if ($usuario->getImg() != 'img/profile-img.jpeg' && $usuario->getImg() != null && file_exists($this->getParameter('kernel.project_dir') . '/public/' . $usuario->getImg())) {
                unlink($this->getParameter('kernel.project_dir') . '/public/' . $usuario->getImg());
            }

            $usuario->setImg("img/".$fileName);
        }else {
            //validar si ya hay una imagen
            if ($usuario->getImg()==null) {
                  // Asignar una imagen por defecto
                  $usuario->setImg('img/profile-img.jpeg');
            }
        }

        $usuarioRepository->save($usuario);

        return new JsonResponse(['status' => 'success', 'message' => 'Registro de usuario exitoso.']);
    }

    #[Route('/usuario/listar', name: 'app_usuario_listar')]
    public function listar(UsuarioRepository $usuarioRepository): JsonResponse
    {
        $usuarios = $usuarioRepository->findAll();
        $data = [];

        foreach ($usuarios as $usuario) {
            $medidaEstandar = $usuario->getMedidaEstandarActual();
            $planStatus = 'sin_plan';
            if (!$usuario->getPlan()->isEmpty()) {
                $planStatus = $usuario->isPlanVigente() ? 'vigente' : 'vencido';
            }

            $data[] = [
                'id' => $usuario->getId(),
                'nombre' => $usuario->getNombre(),
                'cedula' => $usuario->getCedula(),
                'celular' => $usuario->getCelular(),
                'direccion' => $usuario->getDireccion(),
                'fecha_nacimiento' => $usuario->getFechaNacimiento()?->format('Y-m-d'),
                'sexo' => $usuario->getSexo(),
                'eps' => $usuario->getEps(),
                'correo' => $usuario->getCorreo(),
                'img' => $usuario->getImg(),
                'code' => $usuario->getCard() ? $usuario->getCard()->getCode() : 'sin asignar',
                'medida_estandar_nombre' => $medidaEstandar?->getNombreReferencia() ?? 'sin asignar',
                'plan_status' => $planStatus,
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/usuario/dato_fisico', name: 'app_datos_fisicos_registro')]
    public function detalle(
        Request $request,
        UsuarioRepository $usuarioRepository,
        DatoFisicoRepository $datoFisicoRepository,
        ReferenciaCorporalAutomaticaService $referenciaCorporalAutomaticaService
    ): JsonResponse {

        $id = $request->request->get('id');
        $id_usuario = $request->request->get('id_usuario');
        $peso        = $request->request->get('peso');
        $altura      = $request->request->get('altura');
        $imc         = $request->request->get('imc');
        $cintura     = $request->request->get('cintura');
        $gluteos     = $request->request->get('gluteos');
        $brazo       = $request->request->get('brazo');
        $pecho       = $request->request->get('pecho');
        $pierna      = $request->request->get('pierna');
        $pantorrilla = $request->request->get('pantorrilla');

        // validar campos obligatorios y numericos
        $numericData = [
            'peso' => $peso,
            'altura' => $altura,
            'imc' => $imc,
            'cintura' => $cintura,
            'gluteos' => $gluteos,
            'brazo' => $brazo,
            'pecho' => $pecho,
            'pierna' => $pierna,
            'pantorrilla' => $pantorrilla,
        ];
        foreach ($numericData as $field => $value) {
            if ($value === null || $value === '' || !is_numeric($value)) {
                return new JsonResponse(['status' => 'error', 'message' => "Campo inválido: {$field}"], 400);
            }
        }

        if ($id_usuario === null || $id_usuario === '') {
            return new JsonResponse(['status' => 'error', 'message' => 'Todos los campos son obligatorios.'], 400);
        }

        $usuario = null;
        if ($id > 0) {
            $datoFisico = $datoFisicoRepository->find($id);
            if (!$datoFisico instanceof \App\Entity\DatoFisico) {
                return new JsonResponse(['status' => 'error', 'message' => 'Dato Fisico no encontrado.'], 404);
            }
            $usuario = $datoFisico->getUsuario();
        } else {
            $usuario = $usuarioRepository->find($id_usuario);
            if (!$usuario instanceof \App\Entity\Usuario) {
                return new JsonResponse(['status' => 'error', 'message' => 'Usuario no encontrado.'], 404);
            }
            $datoFisico = new \App\Entity\DatoFisico();
            $datoFisico->setColor($usuario->hasDatoFisico() ? '#2563EB' : '#0EA5E9');
            $datoFisico->setUsuario($usuario);
        }

        $datoFisico->setPeso((float) $peso);
        $datoFisico->setAltura((float) $altura);
        $datoFisico->setImc((float) $imc);
        $datoFisico->setCintura((int) $cintura);
        $datoFisico->setGluteos((int) $gluteos);
        $datoFisico->setBrazo((int) $brazo);
        $datoFisico->setPecho((int) $pecho);
        $datoFisico->setPierna((int) $pierna);
        $datoFisico->setPantorrilla((int) $pantorrilla);

        $datoFisicoRepository->save($datoFisico);
        if ($usuario instanceof \App\Entity\Usuario) {
            $referenciaCorporalAutomaticaService->ensureActiveFromMeasurement($usuario, $datoFisico);
        }

        return new JsonResponse(['status' => 'success', 'message' => 'Registro de usuario exitoso.']);
    }

    #[Route('/usuario/desvincular', name: 'app_usuario_desvincular_card')]
    public function desvincular(Request $request, UsuarioRepository $usuarioRepository): JsonResponse
    {
        $id = $request->request->get('id');
        $usuario = $usuarioRepository->find($id);
        if (!$usuario instanceof \App\Entity\Usuario) {
            return new JsonResponse(['status' => 'error', 'message' => 'Usuario no encontrado.'], 404);
        }
        $usuario->unlinkCard();
        $usuarioRepository->save($usuario);
        return new JsonResponse(['status' => 'success', 'message' => 'usuario desvinculado.']);
    }

    #[Route('/usuario/planes', name: 'app_usuario_planes')]
    public function planes(Request $request, UsuarioRepository $usuarioRepository): JsonResponse
    {
        $id = $request->request->get('id');
        $usuario = $usuarioRepository->find($id);
        if (!$usuario instanceof \App\Entity\Usuario) {
            return new JsonResponse(['status' => 'error', 'message' => 'Usuario no encontrado.'], 404);
        }
        $planes = $usuario->getPlan();
        $data = [];
        foreach ($planes as $plan) {
            $data[] = $plan->toArray();
        }
        return new JsonResponse($data);
    }

    #[Route('/usuario/planes/predefinir', name: 'app_usuario_plane_predefinir')]
    public function planesId(Request $request, PlanUsuarioRepository $planesRepository): JsonResponse
    {
        $id = $request->request->get('id');
        $plan = $planesRepository->find($id);
        if (!$plan instanceof \App\Entity\PlanUsuario) {
            return new JsonResponse(['status' => 'error', 'message' => 'Plan no encontrado.'], 404);
        }
        $plan->predefinir();
        $planesRepository->save($plan);
        $planes = $plan->getUsuario()->getPlan();
        $data = [];
        foreach ($planes as $plan) {
            $data[] = $plan->toArray();
        }
        return new JsonResponse($data);
    }

    #[Route('/usuario/medidas', name: 'app_usuario_medidas')]
    public function medidas(Request $request, UsuarioRepository $usuarioRepository): JsonResponse
    {
        $id = $request->request->get('id');
        $usuario = $usuarioRepository->find($id);
        if (!$usuario instanceof \App\Entity\Usuario) {
            return new JsonResponse(['status' => 'error', 'message' => 'Usuario no encontrado.'], 404);
        }
        $medidas = $usuario->getDatoFisicos();
        $data = [];
        foreach ($medidas as $medida) {
            $data[] = $medida->toArray();
        }
        return new JsonResponse($data);
    }

    #[Route('/usuario/medidas/eliminar', name: 'app_usuario_medidas_eliminar', methods: ['POST'])]
    public function eliminarMedida(
        Request $request,
        DatoFisicoRepository $datoFisicoRepository
    ): JsonResponse {
        $id = (int) $request->request->get('id', 0);
        if ($id <= 0) {
            return new JsonResponse(['status' => 'error', 'message' => 'ID de medida inválido.'], 400);
        }

        $medida = $datoFisicoRepository->find($id);
        if (!$medida instanceof \App\Entity\DatoFisico) {
            return new JsonResponse(['status' => 'error', 'message' => 'Medida no encontrada.'], 404);
        }

        $datoFisicoRepository->remove($medida);

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Medida eliminada correctamente.',
        ]);
    }

}
