<?php

namespace App\Entity;

use App\Repository\UsuarioMedidaEstandarRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsuarioMedidaEstandarRepository::class)]
#[ORM\HasLifecycleCallbacks]
class UsuarioMedidaEstandar
{
    use \App\Other\EntityExtends;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'medidaEstandarAsignaciones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $usuario = null;

    #[ORM\Column(length: 255)]
    private ?string $nombreReferencia = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $peso = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $cintura = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $gluteos = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $brazo = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $pecho = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $pierna = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $pantorrilla = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $altura = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $imc = null;

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

    public function getNombreReferencia(): ?string
    {
        return $this->nombreReferencia;
    }

    public function setNombreReferencia(string $nombreReferencia): static
    {
        $this->nombreReferencia = $nombreReferencia;

        return $this;
    }

    public function getPeso(): ?float
    {
        return $this->peso;
    }

    public function setPeso(?float $peso): static
    {
        $this->peso = $peso;

        return $this;
    }

    public function getCintura(): ?float
    {
        return $this->cintura;
    }

    public function setCintura(?float $cintura): static
    {
        $this->cintura = $cintura;

        return $this;
    }

    public function getGluteos(): ?float
    {
        return $this->gluteos;
    }

    public function setGluteos(?float $gluteos): static
    {
        $this->gluteos = $gluteos;

        return $this;
    }

    public function getBrazo(): ?float
    {
        return $this->brazo;
    }

    public function setBrazo(?float $brazo): static
    {
        $this->brazo = $brazo;

        return $this;
    }

    public function getPecho(): ?float
    {
        return $this->pecho;
    }

    public function setPecho(?float $pecho): static
    {
        $this->pecho = $pecho;

        return $this;
    }

    public function getPierna(): ?float
    {
        return $this->pierna;
    }

    public function setPierna(?float $pierna): static
    {
        $this->pierna = $pierna;

        return $this;
    }

    public function getPantorrilla(): ?float
    {
        return $this->pantorrilla;
    }

    public function setPantorrilla(?float $pantorrilla): static
    {
        $this->pantorrilla = $pantorrilla;

        return $this;
    }

    public function getAltura(): ?float
    {
        return $this->altura;
    }

    public function setAltura(?float $altura): static
    {
        $this->altura = $altura;

        return $this;
    }

    public function getImc(): ?float
    {
        return $this->imc;
    }

    public function setImc(?float $imc): static
    {
        $this->imc = $imc;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'usuario_id' => $this->getUsuario()?->getId(),
            'nombre' => $this->getNombreReferencia(),
            'peso' => $this->getPeso(),
            'cintura' => $this->getCintura(),
            'gluteos' => $this->getGluteos(),
            'brazo' => $this->getBrazo(),
            'pecho' => $this->getPecho(),
            'pierna' => $this->getPierna(),
            'pantorrilla' => $this->getPantorrilla(),
            'altura' => $this->getAltura(),
            'imc' => $this->getImc(),
            'active' => $this->isActive(),
        ];
    }
}
