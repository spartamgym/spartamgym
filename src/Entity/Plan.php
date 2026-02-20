<?php

namespace App\Entity;

use App\Repository\PlanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanRepository::class)]
class Plan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nombre = null;

    #[ORM\Column]
    private ?int $precio = null;

    #[ORM\Column(nullable: true)]
    private ?int $tiempo = 0;

    #[ORM\Column(nullable: true)]
    private ?array $detalle = null;

    #[ORM\Column(options: ['default' => 1])]
    private ?int $max_beneficiarios = 1;


    public function getId(): ?int
    {
        return $this->id;
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

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'precio' => $this->getPrecio(),
            'tiempo' => $this->getTiempo(),
            'detalle' => $this->getDetalle(),
            'max_beneficiarios' => $this->getMaxBeneficiarios(),
        ];
    }

    public function getDetalleToString(): string
    {
        return implode(',', $this->detalle ?? []);
    }

    public function getMaxBeneficiarios(): int
    {
        return max(1, (int)($this->max_beneficiarios ?? 1));
    }

    public function setMaxBeneficiarios(?int $maxBeneficiarios): static
    {
        $this->max_beneficiarios = max(1, (int)($maxBeneficiarios ?? 1));

        return $this;
    }
}
