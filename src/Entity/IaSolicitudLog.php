<?php

namespace App\Entity;

use App\Repository\IaSolicitudLogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IaSolicitudLogRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'ia_solicitud_log', indexes: [
    new ORM\Index(name: 'IDX_IA_LOG_CREATED', fields: ['createdAt']),
    new ORM\Index(name: 'IDX_IA_LOG_ESTADO', fields: ['estado']),
])]
class IaSolicitudLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $usuarioNombre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $usuarioCedula = null;

    #[ORM\Column(length: 40)]
    private ?string $estado = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $payloadJson = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $responseHash = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $errorMensaje = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $ip = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function setUsuarioCedula(?string $usuarioCedula): static
    {
        $this->usuarioCedula = $usuarioCedula;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): static
    {
        $this->estado = $estado;

        return $this;
    }

    public function getPayloadJson(): ?string
    {
        return $this->payloadJson;
    }

    public function setPayloadJson(?string $payloadJson): static
    {
        $this->payloadJson = $payloadJson;

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

    public function getErrorMensaje(): ?string
    {
        return $this->errorMensaje;
    }

    public function setErrorMensaje(?string $errorMensaje): static
    {
        $this->errorMensaje = $errorMensaje;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): static
    {
        $this->ip = $ip;

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

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'usuario_nombre' => $this->getUsuarioNombre(),
            'usuario_cedula' => $this->getUsuarioCedula(),
            'estado' => $this->getEstado(),
            'response_hash' => $this->getResponseHash(),
            'error_mensaje' => $this->getErrorMensaje(),
            'ip' => $this->getIp(),
            'created_at' => $this->getCreatedAt()?->format(DATE_ATOM),
        ];
    }
}
