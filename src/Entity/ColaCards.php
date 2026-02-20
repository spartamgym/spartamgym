<?php

namespace App\Entity;

use App\Repository\ColaCardsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ColaCardsRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ColaCards
{
    use \App\Other\EntityExtends;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'colaCards')]
    private ?Usuario $usuario = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;


    #[ORM\Column()]
    private ?bool $ingreso = null;

    #[ORM\Column()]
    private ?bool $verificado = false;


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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function isVerificado(): ?bool
    {
        return $this->verificado;
    }

    public function setVerificado(bool $verificado): static
    {
        $this->verificado = $verificado;

        return $this;
    }

    public function isIngreso(): ?bool
    {
        return $this->ingreso;
    }

    public function setIngreso(bool $ingreso): static
    {
        $this->ingreso = $ingreso;
        return $this;
    }

    #[ORM\PrePersist]
    public function initializeIngreso(): static
    {
        if ($this->ingreso === null) {
            $this->ingreso = true;
        }
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'code' => $this->getCode(), 
            'usuario' => $this->getUsuario()->toArray(),
        ];
    }
}
