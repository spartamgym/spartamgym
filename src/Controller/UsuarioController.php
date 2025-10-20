<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UsuarioRepository;

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
    public function registro(Request $request, UsuarioRepository $usuarioRepository): JsonResponse
    {
        $id = $request->request->get('id');
        $nombre = $request->request->get('nombre');
        $cedula = $request->request->get('cedula');
        $celular = $request->request->get('celular');
        $direccion = $request->request->get('direccion');
        $fecha_nacimiento = $request->request->get('fecha_nacimiento');
        $eps = $request->request->get('eps');
        $correo = $request->request->get('correo');

        //validar todos los campos
        if (empty($nombre) || empty($cedula) || empty($celular) || empty($direccion) || empty($fecha_nacimiento) || empty($eps) || empty($correo)) {
            return new JsonResponse(['status' => 'error', 'message' => 'Todos los campos son obligatorios.'], 400);
        }


        if ($id > 0) {
            $usuario = $usuarioRepository->find($id);
            if (!$usuario instanceof \App\Entity\Usuario) {
                return new JsonResponse(['status' => 'error', 'message' => 'Usuario no encontrado.'], 404);
            }           
        }else{
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
        $usuario->setImg('img/profile-img.jpeg'); // Asignar una imagen por defecto
        $usuarioRepository->save($usuario);

        return new JsonResponse(['status' => 'success', 'message' => 'Registro de usuario exitoso.']);
    }

    #[Route('/usuario/listar', name: 'app_usuario_listar')]
    public function listar(UsuarioRepository $usuarioRepository): JsonResponse
    {
        $usuarios = $usuarioRepository->findAll();
        $data = [];

        foreach ($usuarios as $usuario) {
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
                'code' => $usuario->getCode(),
            ];
        }

        return new JsonResponse($data);
    }       
}
