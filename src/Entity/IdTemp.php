<?php

namespace App\Entity;

use App\Repository\IdTempRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IdTempRepository::class)]
class IdTemp
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $identificador = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentificador(): ?string
    {
        return $this->identificador;
    }

    public function setIdentificador(string $identificador): static
    {
        $this->identificador = $identificador;

        return $this;
    }
}
