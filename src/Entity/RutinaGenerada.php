<?php

namespace App\Entity;

use App\Repository\RutinaGeneradaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RutinaGeneradaRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'rutina_generada')]
#[ORM\Index(name: 'IDX_RUTINA_CREATED', columns: ['created_at'])]
#[ORM\Index(name: 'IDX_RUTINA_CEDULA', columns: ['usuario_cedula'])]
#[ORM\Index(name: 'IDX_RUTINA_USUARIO', columns: ['usuario_id'])]
class RutinaGenerada
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Usuario $usuario = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $usuarioNombre = null;

    #[ORM\Column(length: 64)]
    private ?string $usuarioCedula = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $payloadJson = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenidoTexto = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenidoHtml = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $responseHash = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $pdfPath = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pdfFilename = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

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

    public function getUsuarioNombre(): ?string
    {
        return $this->usuarioNombre;
    }

    public function setUsuarioNombre(?string $usuarioNombre): static
    {
        $this->usuarioNombre = $usuarioNombre;

        return $this;
    }

    public function getUsuarioCedula(): ?string
    {
        return $this->usuarioCedula;
    }

    public function setUsuarioCedula(string $usuarioCedula): static
    {
        $this->usuarioCedula = $usuarioCedula;

        return $this;
    }

    public function getPayloadJson(): ?string
    {
        return $this->payloadJson;
    }

    public function setPayloadJson(string $payloadJson): static
    {
        $this->payloadJson = $payloadJson;

        return $this;
    }

    public function getContenidoTexto(): ?string
    {
        return $this->contenidoTexto;
    }

    public function setContenidoTexto(string $contenidoTexto): static
    {
        $this->contenidoTexto = $contenidoTexto;

        return $this;
    }

    public function getContenidoHtml(): ?string
    {
        return $this->contenidoHtml;
    }

    public function setContenidoHtml(string $contenidoHtml): static
    {
        $this->contenidoHtml = $contenidoHtml;

        return $this;
    }

    public function getResponseHash(): ?string
    {
        return $this->responseHash;
    }

    public function setResponseHash(?string $responseHash): static
    {
        $this->responseHash = $responseHash;

        return $this;
    }

    public function getPdfPath(): ?string
    {
        return $this->pdfPath;
    }

    public function setPdfPath(?string $pdfPath): static
    {
        $this->pdfPath = $pdfPath;

        return $this;
    }

    public function getPdfFilename(): ?string
    {
        return $this->pdfFilename;
    }

    public function setPdfFilename(?string $pdfFilename): static
    {
        $this->pdfFilename = $pdfFilename;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function ensureCreatedAt(): void
    {
        if (!$this->createdAt instanceof \DateTimeImmutable) {
            $this->createdAt = new \DateTimeImmutable();
        }
    }
}
