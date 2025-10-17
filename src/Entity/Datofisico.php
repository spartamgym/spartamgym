<?php

namespace App\Entity;

use App\Repository\DatofisicoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DatofisicoRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Datofisico
{
        use \App\Other\EntityExtends;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $peso = null;

    #[ORM\Column(nullable: true)]
    private ?int $cintura = null;

    #[ORM\Column(nullable: true)]
    private ?int $gluteos = null;

    #[ORM\Column(nullable: true)]
    private ?int $brazo = null;

    #[ORM\Column(nullable: true)]
    private ?int $pecho = null;

    #[ORM\Column(nullable: true)]
    private ?int $pierna = null;

    #[ORM\Column(nullable: true)]
    private ?int $pantorrilla = null;

    #[ORM\ManyToOne(inversedBy: 'datofisicos')]
    private ?Usuario $usuario = null;


 
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPeso(): ?int
    {
        return $this->peso;
    }

    public function setPeso(?int $peso): static
    {
        $this->peso = $peso;

        return $this;
    }

    public function getCintura(): ?int
    {
        return $this->cintura;
    }

    public function setCintura(?int $cintura): static
    {
        $this->cintura = $cintura;

        return $this;
    }

    public function getGluteos(): ?int
    {
        return $this->gluteos;
    }

    public function setGluteos(?int $gluteos): static
    {
        $this->gluteos = $gluteos;

        return $this;
    }

    public function getBrazo(): ?int
    {
        return $this->brazo;
    }

    public function setBrazo(?int $brazo): static
    {
        $this->brazo = $brazo;

        return $this;
    }

    public function getPecho(): ?int
    {
        return $this->pecho;
    }

    public function setPecho(?int $pecho): static
    {
        $this->pecho = $pecho;

        return $this;
    }

    public function getPierna(): ?int
    {
        return $this->pierna;
    }

    public function setPierna(?int $pierna): static
    {
        $this->pierna = $pierna;

        return $this;
    }

    public function getPantorrilla(): ?int
    {
        return $this->pantorrilla;
    }

    public function setPantorrilla(?int $pantorrilla): static
    {
        $this->pantorrilla = $pantorrilla;

        return $this;
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


    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'peso' => $this->getPeso(),
            'cintura' => $this->getCintura(),
            'gluteos' => $this->getGluteos(),
            'brazo' => $this->getBrazo(),
            'pecho' => $this->getPecho(),
            'pierna' => $this->getPierna(),
            'pantorrilla' => $this->getPantorrilla(),
            'usuario_id' => $this->getUsuario() ? $this->getUsuario()->getId() : null,
            'createAt' => $this->getCreateAt() ? $this->getCreateAt()->format('Y-m-d H:i:s') : null,
            'UpdateAt' => $this->getUpdateAt() ? $this->getUpdateAt()->format('Y-m-d H:i:s') : null,
            'active' => $this->isActive(),  

        ];
    }



}
