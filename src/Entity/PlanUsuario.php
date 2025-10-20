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

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'isActive' => $this->isActive(),
            'plan' => $this->getPlan() ? [
                'id' => $this->getPlan()->getId(),
                'nombre' => $this->getPlan()->getNombre(),
                'precio' => $this->getPlan()->getPrecio(),
                'tiempo' => $this->getPlan()->getTiempo(),
                'detalle' => $this->getPlan()->getDetalle(),
            ] : null,
        ];
    }
}
