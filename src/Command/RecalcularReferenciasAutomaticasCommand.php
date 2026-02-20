<?php

namespace App\Command;

use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use App\Service\ReferenciaCorporalAutomaticaService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:referencias:recalcular',
    description: 'Recalcula referencias automaticas por usuario segun primera medicion, sexo y edad.'
)]
final class RecalcularReferenciasAutomaticasCommand extends Command
{
    public function __construct(
        private UsuarioRepository $usuarioRepository,
        private ReferenciaCorporalAutomaticaService $referenciaService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $usuarios = $this->usuarioRepository->findAll();

        $procesados = 0;
        $recalculados = 0;
        $omitidosSinMedidas = 0;
        $desactivadosSinMedidas = 0;

        foreach ($usuarios as $usuario) {
            if (!$usuario instanceof Usuario) {
                continue;
            }

            $procesados++;
            if (!$usuario->hasDatoFisico()) {
                $desactivadosSinMedidas += $this->referenciaService->disableActiveForUsuario($usuario);
                $omitidosSinMedidas++;
                continue;
            }

            $recalculada = $this->referenciaService->rebuildForUsuario($usuario);
            if ($recalculada !== null) {
                $recalculados++;
            }
        }

        $io->success([
            sprintf('Usuarios procesados: %d', $procesados),
            sprintf('Referencias recalculadas: %d', $recalculados),
            sprintf('Usuarios omitidos sin medidas: %d', $omitidosSinMedidas),
            sprintf('Referencias desactivadas por falta de medidas: %d', $desactivadosSinMedidas),
        ]);

        return Command::SUCCESS;
    }
}
