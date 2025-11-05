<?php

namespace App\Entity;

use App\Repository\PlanUsuarioRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanUsuarioRepository::class)]
#[ORM\HasLifecycleCallbacks]
class PlanUsuario
{
    use \App\Other\EntityExtends;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'plan')]
    private ?Usuario $usuario = null;

    #[ORM\ManyToOne(inversedBy: 'planUsuarios')]
    private ?Plan $plan = null;

    #[ORM\Column]
    private ?\DateTime $fecha_inicio;

    #[ORM\Column]
    private ?\DateTime $fecha_fin;


    #[ORM\Column(nullable: true)]
    private ?bool $predefinido = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getPlan(): ?Plan
    {
        return $this->plan;
    }

    public function setPlan(?Plan $plan): static
    {
        $this->plan = $plan;

        return $this;
    }

    public function isPredefinido(): ?bool
    {
        return $this->predefinido;
    }

    public function setPredefinido(bool $predefinido): static
    {
        $this->predefinido = $predefinido;

        return $this;
    }

    public function predefinir(): void
    {
        $this->predefinido = true;
    }


    public function getFechaInicio(): ?\DateTime
    {
        return $this->fecha_inicio;
    }

    public function setFechaInicio(?\DateTime $fecha_inicio): static
    {
        $this->fecha_inicio = $fecha_inicio;

        return $this;
    }

    public function getFechaFin(): ?\DateTime
    {
        return $this->fecha_fin;
    }

    public function setFechaFin(\DateTime $fecha_fin): static
    {
        $this->fecha_fin = $fecha_fin;

        return $this;
    }

    //validar si el plan esta vigente o no
    public function statusPlanArray(): array
    {
        $today = (new \DateTime())->setTime(0, 0, 0);
        $fechaFin = (clone $this->getFechaFin())->setTime(0, 0, 0);

        if ($today <= $fechaFin) {
            $diff = $today->diff($fechaFin);
            return [
                'estado' => 'vigente',
                'dias_restantes' => $diff->days,
                'vigente' => true

            ];
        }

        return [
            'estado' => 'vencido',
            'dias_restantes' => 0,
            'vigente' => false
        ];
    }

    // 2. Función que devuelve solo el estado
    public function statusPlanEstado(): string
    {
        return $this->statusPlanArray()['estado'];
    }

    // 3. Función que devuelve solo los días restantes
    public function statusPlanDiasRestantes(): int
    {
        return $this->statusPlanArray()['dias_restantes'];
    }

    public function isVigente(): bool
    {
        return $this->statusPlanArray()['vigente'];
    }
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'isActive' => $this->isActive(),
            'isPredefinido' => $this->isPredefinido(),
            'isVigente' => $this->isVigente(),
            'plan' => $this->getPlan() ? [
                'id' => $this->getPlan()->getId(),
                'nombre' => $this->getPlan()->getNombre(),
                'precio' => $this->getPlan()->getPrecio(),
                'tiempo' => $this->getPlan()->getTiempo(),
                'detalle' => $this->getPlan()->getDetalle(),
                'predefinido' => $this->isPredefinido() ? 'si' : 'no',
                'isPredefinido' => $this->isPredefinido(),
                'status_plan' => $this->statusPlanEstado(),
                'dias_restantes' => $this->statusPlanDiasRestantes(),
            ] : null,
        ];
    }
}
