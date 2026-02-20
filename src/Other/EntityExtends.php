<?php

namespace App\Other;

use Doctrine\ORM\Mapping as ORM;

trait EntityExtends
{

    #[ORM\Column(nullable: true)]
    private \DateTime $createAt;

    #[ORM\Column(nullable: true)]
    private \DateTime $UpdateAt;

    #[ORM\Column(nullable: true)]
    private ?bool $active = null;


    public function getCreateAt(): ?\DateTime
    {
        return $this->createAt;
    }

    private function appDateTimeZone(): \DateTimeZone
    {
        $timezone = getenv('APP_TIMEZONE') ?: 'America/Bogota';
        try {
            return new \DateTimeZone($timezone);
        } catch (\Exception) {
            return new \DateTimeZone('America/Bogota');
        }
    }

    #[ORM\PrePersist]
    public function setCreateAt(): void
    {
        $this->createAt = new \DateTime('now', $this->appDateTimeZone());
    }

    public function getUpdateAt(): ?\DateTime
    {
        return $this->UpdateAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdateAt(): void
    {
        $this->UpdateAt = new \DateTime('now', $this->appDateTimeZone());
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    #[ORM\PrePersist]
    public function setActive(): self
    {
        $this->active = true;
        return $this;
    }

    public function toggleActive(): self
    {
        $this->active = !$this->active;

        return $this;
    }
}
