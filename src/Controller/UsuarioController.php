<?php

namespace App\Controller;

use App\Entity\MedidaEstandar;
use App\Repository\MedidaEstandarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UsuarioRepository;
use App\Repository\DatoFisicoRepository;
use App\Repository\PlanUsuarioRepository;
use App\Service\UsuarioMedidaEstandarService;

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
        UsuarioRepository $usuarioRepository,
        MedidaEstandarRepository $medidaEstandarRepository,
        UsuarioMedidaEstandarService $usuarioMedidaEstandarService
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
        $eps = $request->request->get('eps');
        $correo = $request->request->get('correo');
        $idMedidaEstandar = $request->request->get('id_medida_estandar');

        //validar todos los campos
        if (empty($nombre) || empty($cedula) || empty($celular) || empty($direccion) || empty($fecha_nacimiento) || empty($eps) || empty($correo)) {
            return new JsonResponse(['status' => 'error', 'message' => 'Todos los campos son obligatorios.'], 400);
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

        $medidaEstandar = null;
        if ($idMedidaEstandar !== null && $idMedidaEstandar !== '') {
            $medidaEstandar = $medidaEstandarRepository->find((int) $idMedidaEstandar);
            if (!$medidaEstandar instanceof MedidaEstandar) {
                return new JsonResponse(['status' => 'error', 'message' => 'Medida estándar no encontrada.'], 404);
            }
        } elseif ($isNew) {
            $medidaEstandar = $medidaEstandarRepository->findOneBy(['active' => true], ['id' => 'ASC']);
        }

        if ($medidaEstandar instanceof MedidaEstandar) {
            $usuarioMedidaEstandarService->assignFromTemplate($usuario, $medidaEstandar);
        }

        return new JsonResponse(['status' => 'success', 'message' => 'Registro de usuario exitoso.']);
    }

    #[Route('/usuario/listar', name: 'app_usuario_listar')]
    public function listar(UsuarioRepository $usuarioRepository): JsonResponse
    {
        $usuarios = $usuarioRepository->findAll();
        $data = [];

        foreach ($usuarios as $usuario) {
            $medidaEstandar = $usuario->getMedidaEstandarActual();
            $data[] = [
                'id' => $usuario->getId(),
                'nombre' => $usuario->getNombre(),
                'cedula' => $usuario->getCedula(),
                'celular' => $usuario->getCelular(),
                'direccion' => $usuario->getDireccion(),
                'fecha_nacimiento' => $usuario->getFechaNacimiento()->format('Y-m-d'),
                'eps' => $usuario->getEps(),
                'correo' => $usuario->getCorreo(),
                'img' => $usuario->getImg(),
                'code' => $usuario->getCard() ? $usuario->getCard()->getCode() : 'sin asignar',
                'medida_estandar_nombre' => $medidaEstandar?->getNombreReferencia() ?? 'sin asignar',
                'medida_estandar_id' => $medidaEstandar?->getMedidaEstandar()?->getId(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/usuario/dato_fisico', name: 'app_datos_fisicos_registro')]
    public function detalle(
        Request $request,
        UsuarioRepository $usuarioRepository,
        DatoFisicoRepository $datoFisicoRepository
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

        if ($id > 0) {
            $datoFisico = $datoFisicoRepository->find($id);
            if (!$datoFisico instanceof \App\Entity\DatoFisico) {
                return new JsonResponse(['status' => 'error', 'message' => 'Dato Fisico no encontrado.'], 404);
            }
        } else {
            $usuario = $usuarioRepository->find($id_usuario);
            if (!$usuario instanceof \App\Entity\Usuario) {
                return new JsonResponse(['status' => 'error', 'message' => 'Usuario no encontrado.'], 404);
            }
            if (!$usuario->getMedidaEstandarActual()) {
                return new JsonResponse(['status' => 'error', 'message' => 'Primero debes asignar una medida estándar al usuario.'], 400);
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

    #[Route('/usuario/medida_estandar/listar', name: 'app_usuario_medida_estandar_listar', methods: ['GET'])]
    public function listarMedidasEstandar(MedidaEstandarRepository $medidaEstandarRepository): JsonResponse
    {
        $medidas = $medidaEstandarRepository->findAllActive();
        return new JsonResponse(array_map(
            fn(MedidaEstandar $medida) => $medida->toArray(),
            $medidas
        ));
    }

    #[Route('/usuario/medida_estandar/asignar', name: 'app_usuario_medida_estandar_asignar', methods: ['POST'])]
    public function asignarMedidaEstandar(
        Request $request,
        UsuarioRepository $usuarioRepository,
        MedidaEstandarRepository $medidaEstandarRepository,
        UsuarioMedidaEstandarService $usuarioMedidaEstandarService
    ): JsonResponse
    {
        $idUsuario = (int) $request->request->get('id_usuario', 0);
        $idMedidaEstandar = (int) $request->request->get('id_medida_estandar', 0);

        if ($idUsuario <= 0 || $idMedidaEstandar <= 0) {
            return new JsonResponse(['status' => 'error', 'message' => 'Usuario y medida estándar son obligatorios.'], 400);
        }

        $usuario = $usuarioRepository->find($idUsuario);
        if (!$usuario instanceof \App\Entity\Usuario) {
            return new JsonResponse(['status' => 'error', 'message' => 'Usuario no encontrado.'], 404);
        }

        $medidaEstandar = $medidaEstandarRepository->find($idMedidaEstandar);
        if (!$medidaEstandar instanceof MedidaEstandar) {
            return new JsonResponse(['status' => 'error', 'message' => 'Medida estándar no encontrada.'], 404);
        }

        $asignacion = $usuarioMedidaEstandarService->assignFromTemplate($usuario, $medidaEstandar);

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Medida estándar asignada correctamente.',
            'asignacion' => $asignacion->toArray(),
        ]);
    }
}
