<?php

namespace App\Service;

use App\Entity\MedidaEstandar;
use App\Entity\Usuario;
use App\Entity\UsuarioMedidaEstandar;
use App\Repository\UsuarioMedidaEstandarRepository;

final class UsuarioMedidaEstandarService
{
    public function __construct(
        private UsuarioMedidaEstandarRepository $usuarioMedidaEstandarRepository
    ) {}

    public function assignFromTemplate(Usuario $usuario, MedidaEstandar $template): UsuarioMedidaEstandar
    {
        $actual = $this->usuarioMedidaEstandarRepository->findActiveByUsuario($usuario);
        if ($actual instanceof UsuarioMedidaEstandar) {
            $actual->toggleActive();
            $this->usuarioMedidaEstandarRepository->save($actual, false);
        }

        $asignacion = new UsuarioMedidaEstandar();
        $asignacion->setUsuario($usuario);
        $asignacion->setMedidaEstandar($template);
        $asignacion->setNombreReferencia($template->getNombre() ?? 'Referencia');
        $asignacion->setPeso($template->getPeso());
        $asignacion->setCintura($template->getCintura());
        $asignacion->setGluteos($template->getGluteos());
        $asignacion->setBrazo($template->getBrazo());
        $asignacion->setPecho($template->getPecho());
        $asignacion->setPierna($template->getPierna());
        $asignacion->setPantorrilla($template->getPantorrilla());
        $asignacion->setAltura($template->getAltura());
        $asignacion->setImc($template->getImc());

        $this->usuarioMedidaEstandarRepository->save($asignacion);

        return $asignacion;
    }
}
