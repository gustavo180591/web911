<?php

namespace App\Entity;

use App\Repository\AutoridadRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: AutoridadRepository::class)]
class Autoridad
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telefono = null;

    #[ORM\Column(length: 100)]
    private ?string $area_responsable = null;

    #[ORM\ManyToMany(targetEntity: Denuncia::class, mappedBy: 'autoridades')]
    private Collection $denuncias; // Relación ManyToMany con Denuncia

    public function __construct()
    {
        $this->denuncias = new ArrayCollection();
    }

    // Getters y setters para cada propiedad

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): static
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getAreaResponsable(): ?string
    {
        return $this->area_responsable;
    }

    public function setAreaResponsable(string $area_responsable): static
    {
        $this->area_responsable = $area_responsable;

        return $this;
    }

    /**
     * @return Collection<int, Denuncia>
     */
    public function getDenuncias(): Collection
    {
        return $this->denuncias;
    }

    public function addDenuncia(Denuncia $denuncia): static
    {
        if (!$this->denuncias->contains($denuncia)) {
            $this->denuncias->add($denuncia);
            $denuncia->addAutoridad($this);
        }

        return $this;
    }

    public function removeDenuncia(Denuncia $denuncia): static
    {
        if ($this->denuncias->removeElement($denuncia)) {
            $denuncia->removeAutoridad($this);
        }

        return $this;
    }
}
