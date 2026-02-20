<?php

namespace App\Service;

use App\Entity\DatoFisico;
use App\Entity\Usuario;
use App\Entity\UsuarioMedidaEstandar;
use App\Repository\UsuarioMedidaEstandarRepository;

final class ReferenciaCorporalAutomaticaService
{
    private const SEXO_MASCULINO = 'M';
    private const SEXO_FEMENINO = 'F';
    private const SEXO_OTRO = 'O';
    private const SEXO_NEUTRO = 'N';

    public function __construct(
        private UsuarioMedidaEstandarRepository $usuarioMedidaEstandarRepository
    ) {}

    public function ensureActiveFromUsuario(Usuario $usuario): ?UsuarioMedidaEstandar
    {
        $actual = $this->usuarioMedidaEstandarRepository->findActiveByUsuario($usuario);
        if ($actual instanceof UsuarioMedidaEstandar) {
            return $actual;
        }

        $medidaInicial = $this->resolveInitialMeasurement($usuario);
        if (!$medidaInicial instanceof DatoFisico) {
            return null;
        }

        return $this->createFromInitialMeasurement($usuario, $medidaInicial);
    }

    public function ensureActiveFromMeasurement(Usuario $usuario, DatoFisico $medida): UsuarioMedidaEstandar
    {
        $actual = $this->usuarioMedidaEstandarRepository->findActiveByUsuario($usuario);
        if ($actual instanceof UsuarioMedidaEstandar) {
            return $actual;
        }

        $medidaBase = $this->resolveInitialMeasurement($usuario) ?? $medida;
        return $this->createFromInitialMeasurement($usuario, $medidaBase);
    }

    public function rebuildForUsuario(Usuario $usuario): ?UsuarioMedidaEstandar
    {
        $medidaBase = $this->resolveInitialMeasurement($usuario);
        if (!$medidaBase instanceof DatoFisico) {
            return null;
        }

        $this->disableActiveForUsuario($usuario);
        return $this->createFromInitialMeasurement($usuario, $medidaBase);
    }

    public function disableActiveForUsuario(Usuario $usuario): int
    {
        $activos = $this->usuarioMedidaEstandarRepository->findAllActiveByUsuario($usuario);
        if ($activos === []) {
            return 0;
        }

        foreach ($activos as $activo) {
            if ($activo->isActive()) {
                $activo->toggleActive();
                $this->usuarioMedidaEstandarRepository->save($activo, false);
            }
        }

        $this->usuarioMedidaEstandarRepository->getEntityManager()->flush();
        return count($activos);
    }

    private function createFromInitialMeasurement(Usuario $usuario, DatoFisico $medida): UsuarioMedidaEstandar
    {
        $alturaM = $this->normalizeAlturaMetros($medida->getAltura());
        $alturaCm = $alturaM * 100;
        $sexo = $this->normalizeSexo($usuario->getSexo());
        $edad = $this->resolveEdad($usuario);
        $perfil = $this->applyAgeAdjustment($this->resolvePerfilBySexo($sexo), $edad);

        $pesoObjetivoTeorico = $perfil['imc'] * ($alturaM ** 2);
        $pesoObjetivo = $this->blend($medida->getPeso(), $pesoObjetivoTeorico);

        $cinturaObjetivo = $this->blend(
            $this->toFloat($medida->getCintura()),
            $alturaCm * $perfil['cintura_ratio']
        );
        $gluteosObjetivo = $this->blend(
            $this->toFloat($medida->getGluteos()),
            $alturaCm * $perfil['gluteos_ratio']
        );
        $brazoObjetivo = $this->blend(
            $this->toFloat($medida->getBrazo()),
            $alturaCm * $perfil['brazo_ratio']
        );
        $pechoObjetivo = $this->blend(
            $this->toFloat($medida->getPecho()),
            $alturaCm * $perfil['pecho_ratio']
        );
        $piernaObjetivo = $this->blend(
            $this->toFloat($medida->getPierna()),
            $alturaCm * $perfil['pierna_ratio']
        );
        $pantorrillaObjetivo = $this->blend(
            $this->toFloat($medida->getPantorrilla()),
            $alturaCm * $perfil['pantorrilla_ratio']
        );
        $imcObjetivo = $this->roundValue($pesoObjetivo / ($alturaM ** 2));

        $asignacion = new UsuarioMedidaEstandar();
        $asignacion->setUsuario($usuario);
        $etiquetaEdad = $edad !== null ? sprintf(', %d años', $edad) : '';
        $asignacion->setNombreReferencia(sprintf('Objetivo automático (%s%s)', $perfil['label'], $etiquetaEdad));
        $asignacion->setPeso($pesoObjetivo);
        $asignacion->setCintura($cinturaObjetivo);
        $asignacion->setGluteos($gluteosObjetivo);
        $asignacion->setBrazo($brazoObjetivo);
        $asignacion->setPecho($pechoObjetivo);
        $asignacion->setPierna($piernaObjetivo);
        $asignacion->setPantorrilla($pantorrillaObjetivo);
        $asignacion->setAltura($this->roundValue($alturaM));
        $asignacion->setImc($imcObjetivo);

        // Mantiene la relacion en memoria para respuestas del mismo request.
        $usuario->addMedidaEstandarAsignacion($asignacion);
        $this->usuarioMedidaEstandarRepository->save($asignacion);

        return $asignacion;
    }

    private function resolveInitialMeasurement(Usuario $usuario): ?DatoFisico
    {
        $initial = null;
        foreach ($usuario->getDatofisicos() as $item) {
            if (!$item instanceof DatoFisico) {
                continue;
            }
            if (!$initial instanceof DatoFisico) {
                $initial = $item;
                continue;
            }

            $itemDate = $item->getCreateAt();
            $initialDate = $initial->getCreateAt();

            if ($itemDate instanceof \DateTimeInterface && $initialDate instanceof \DateTimeInterface) {
                if ($itemDate < $initialDate) {
                    $initial = $item;
                }
                continue;
            }

            if ($itemDate instanceof \DateTimeInterface && !($initialDate instanceof \DateTimeInterface)) {
                $initial = $item;
                continue;
            }

            if (($item->getId() ?? PHP_INT_MAX) < ($initial->getId() ?? PHP_INT_MAX)) {
                $initial = $item;
            }
        }

        return $initial;
    }

    private function normalizeSexo(?string $sexo): string
    {
        $valor = strtoupper(trim((string) $sexo));
        return match ($valor) {
            self::SEXO_MASCULINO => self::SEXO_MASCULINO,
            self::SEXO_FEMENINO => self::SEXO_FEMENINO,
            self::SEXO_OTRO => self::SEXO_OTRO,
            default => self::SEXO_NEUTRO,
        };
    }

    private function resolveEdad(Usuario $usuario): ?int
    {
        $fechaNacimiento = $usuario->getFechaNacimiento();
        if (!$fechaNacimiento instanceof \DateTimeInterface) {
            return null;
        }

        $hoy = new \DateTimeImmutable('today');
        $edad = $fechaNacimiento->diff($hoy)->y;
        return $edad >= 0 ? $edad : null;
    }

    /**
     * @param array<string,mixed> $perfil
     * @return array<string,mixed>
     */
    private function applyAgeAdjustment(array $perfil, ?int $edad): array
    {
        if ($edad === null || $edad < 30) {
            return $perfil;
        }

        $factor = match (true) {
            $edad < 40 => 1,
            $edad < 50 => 2,
            $edad < 60 => 3,
            default => 4,
        };

        $perfil['imc'] += 0.25 * $factor;
        $perfil['cintura_ratio'] += 0.005 * $factor;
        $perfil['gluteos_ratio'] += 0.002 * $factor;
        $perfil['brazo_ratio'] -= 0.001 * $factor;
        $perfil['pecho_ratio'] += 0.002 * $factor;
        $perfil['pierna_ratio'] -= 0.001 * $factor;
        $perfil['pantorrilla_ratio'] -= 0.001 * $factor;

        return $perfil;
    }

    /**
     * @return array<string,mixed>
     */
    private function resolvePerfilBySexo(string $sexo): array
    {
        return match ($sexo) {
            self::SEXO_MASCULINO => [
                'label' => 'Hombre',
                'imc' => 23.5,
                'cintura_ratio' => 0.47,
                'gluteos_ratio' => 0.54,
                'brazo_ratio' => 0.185,
                'pecho_ratio' => 0.565,
                'pierna_ratio' => 0.335,
                'pantorrilla_ratio' => 0.215,
            ],
            self::SEXO_FEMENINO => [
                'label' => 'Mujer',
                'imc' => 21.8,
                'cintura_ratio' => 0.45,
                'gluteos_ratio' => 0.595,
                'brazo_ratio' => 0.165,
                'pecho_ratio' => 0.525,
                'pierna_ratio' => 0.32,
                'pantorrilla_ratio' => 0.2,
            ],
            self::SEXO_OTRO => [
                'label' => 'Otro',
                'imc' => 22.6,
                'cintura_ratio' => 0.46,
                'gluteos_ratio' => 0.565,
                'brazo_ratio' => 0.175,
                'pecho_ratio' => 0.545,
                'pierna_ratio' => 0.327,
                'pantorrilla_ratio' => 0.207,
            ],
            default => [
                'label' => 'General',
                'imc' => 22.6,
                'cintura_ratio' => 0.46,
                'gluteos_ratio' => 0.565,
                'brazo_ratio' => 0.175,
                'pecho_ratio' => 0.545,
                'pierna_ratio' => 0.327,
                'pantorrilla_ratio' => 0.207,
            ],
        };
    }

    private function normalizeAlturaMetros(?float $altura): float
    {
        $alturaValue = (float) ($altura ?? 0.0);
        if ($alturaValue <= 0) {
            return 1.70;
        }

        return $alturaValue > 3 ? $alturaValue / 100 : $alturaValue;
    }

    private function blend(?float $actual, float $target, float $factor = 0.4): float
    {
        if ($actual === null || $actual <= 0) {
            return $this->roundValue($target);
        }

        $result = $actual + (($target - $actual) * $factor);
        return $this->roundValue($result);
    }

    private function toFloat(null|int|float $value): ?float
    {
        if ($value === null) {
            return null;
        }

        return (float) $value;
    }

    private function roundValue(float $value): float
    {
        return round($value, 1);
    }
}
