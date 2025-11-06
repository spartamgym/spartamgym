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

    //#[ORM\ManyToOne(inversedBy: 'planUsuarios')]
    private ?Plan $plan = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $fecha_inicio = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $fecha_fin = null;

    #[ORM\Column(nullable: true)]
    private ?bool $predefinido = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nombre = null;

    #[ORM\Column]
    private ?int $precio = null;

    #[ORM\Column(nullable: true)]
    private ?int $tiempo = 0;

    #[ORM\Column(nullable: true)]
    private ?array $detalle = null;

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
        $this->getUsuario()->resetPlanes();
        $this->setPredefinido(true);
        
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

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getPrecio(): ?int
    {
        return $this->precio;
    }

    public function setPrecio(int $precio): static
    {
        $this->precio = $precio;

        return $this;
    }

    public function getTiempo(): ?int
    {
        return $this->tiempo;
    }

    public function setTiempo(?int $tiempo): static
    {
        $this->tiempo = $tiempo;

        return $this;
    }

    public function getDetalle(): ?array
    {
        return $this->detalle;
    }

    public function setDetalle(?array $detalle): static
    {
        $this->detalle = $detalle;

        return $this;
    }
    #[ORM\PrePersist]
    public function copiar_plan(): void
    {
        //si hay un plan se copia
        if ($this->getPlan()) {
            $this->setNombre($this->getPlan()->getNombre());
            $this->setPrecio($this->getPlan()->getPrecio());
            $this->setTiempo($this->getPlan()->getTiempo());
            $this->setDetalle($this->getPlan()->getDetalle());
            $this->setFechaInicio(new \DateTime());
            //fecha final la sacamos con los dias que tenga el plan + la fecha de inicio
            $this->setFechaFin((new \DateTime())->modify('+' . $this->getTiempo() . ' day'));
        }
    }




    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'isActive' => $this->isActive(),
            'isVigente' => $this->isVigente(),
            'nombre' => $this->getNombre(),
            'precio' => $this->getPrecio(),
            'tiempo' => $this->getTiempo(),
            'detalle' => $this->getDetalle(),
            'predefinido' => $this->isPredefinido() ? 'si' : 'no',
            'is_predefinido' => $this->isPredefinido(),
            'status_plan' => $this->statusPlanEstado(),
            'dias_restantes' => $this->statusPlanDiasRestantes(),
            
        ];
    }
}
