<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Usuario
{
    use \App\Other\EntityExtends;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cedula = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $celular = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $direccion = null;

    #[ORM\Column(nullable: true)]
    private \DateTime $fecha_nacimiento;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $eps = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $correo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $img = null;

    /**
     * @var Collection<int, Datofisico>
     */
    #[ORM\OneToMany(targetEntity: Datofisico::class, mappedBy: 'usuario')]
    private Collection $datofisicos;

    /**
     * @var Collection<int, PlanUsuario>
     */
    #[ORM\OneToMany(targetEntity: PlanUsuario::class, mappedBy: 'usuario')]
    private Collection $plan;

    public function __construct()
    {
        $this->datofisicos = new ArrayCollection();
        $this->plan = new ArrayCollection();
    }


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

    public function getCedula(): ?string
    {
        return $this->cedula;
    }

    public function setCedula(?string $cedula): static
    {
        $this->cedula = $cedula;

        return $this;
    }

    public function getCelular(): ?string
    {
        return $this->celular;
    }

    public function setCelular(?string $celular): static
    {
        $this->celular = $celular;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): static
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getFechaNacimiento(): ?\DateTime
    {
        return $this->fecha_nacimiento;
    }
    public function setFechaNacimiento(\DateTime $fecha_nacimiento): static
    {
        $this->fecha_nacimiento = $fecha_nacimiento;

        return $this;
    }

    public function getEps(): ?string
    {
        return $this->eps;
    }

    public function setEps(?string $eps): static
    {
        $this->eps = $eps;

        return $this;
    }

    public function getCorreo(): ?string
    {
        return $this->correo;
    }

    public function setCorreo(?string $correo): static
    {
        $this->correo = $correo;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): static
    {
        $this->img = $img;

        return $this;
    }

    /**
     * @return Collection<int, Datofisico>
     */
    public function getDatofisicos(): Collection
    {
        return $this->datofisicos;
    }

    public function addDatofisico(Datofisico $datofisico): static
    {
        if (!$this->datofisicos->contains($datofisico)) {
            $this->datofisicos->add($datofisico);
            $datofisico->setUsuario($this);
        }

        return $this;
    }

    public function removeDatofisico(Datofisico $datofisico): static
    {
        if ($this->datofisicos->removeElement($datofisico)) {
            // set the owning side to null (unless already changed)
            if ($datofisico->getUsuario() === $this) {
                $datofisico->setUsuario(null);
            }
        }

        return $this;
    }

    public function toArray(): array
{
    return [
        'id'              => $this->getId(),
        'nombre'          => $this->getNombre(),
        'cedula'          => $this->getCedula(),
        'celular'         => $this->getCelular(),
        'direccion'       => $this->getDireccion(),
        'fecha_nacimiento'=> $this->getFechaNacimiento()?->format('Y-m-d'),
        'eps'             => $this->getEps(),
        'correo'          => $this->getCorreo(),
        'code'            => $this->getCode(),
        'img'             => $this->getImg(),
        'datofisicos'     => array_map(fn($df) => $df->toArray(), $this->getDatofisicos()->toArray()),
        'planes'            => array_map(fn($pu) => $pu->toArray(), $this->getPlan()->toArray()),
    ];
}

    /**
     * @return Collection<int, PlanUsuario>
     */
    public function getPlan(): Collection
    {
        return $this->plan;
    }

    public function addPlan(PlanUsuario $plan): static
    {
        if (!$this->plan->contains($plan)) {
            $this->plan->add($plan);
            $plan->setUsuario($this);
        }

        return $this;
    }

    public function removePlan(PlanUsuario $plan): static
    {
        if ($this->plan->removeElement($plan)) {
            // set the owning side to null (unless already changed)
            if ($plan->getUsuario() === $this) {
                $plan->setUsuario(null);
            }
        }

        return $this;
    }


}
