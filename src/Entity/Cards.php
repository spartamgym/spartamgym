<?php

namespace App\Entity;

use App\Repository\CardsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CardsRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Cards
{
    use \App\Other\EntityExtends;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\OneToOne(mappedBy: 'card', targetEntity: Usuario::class)]
    private ?Usuario $usuario = null;

    public function getId(): ?int
    {
        return $this->id;
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
    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }
    public function setUsuario(Usuario $usuario): static
    {
        // set the owning side of the relation if necessary
        if ($usuario->getCard() !== $this) {
            $usuario->setCard($this);
        }
        $this->usuario = $usuario;

        return $this;
    }
}
