<?php

namespace App\Entity;

use App\Repository\AutoridadRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AutoridadRepository::class)]
class Autoridad
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'El nombre no puede estar vacío.')]
    #[Assert\Length(max: 255, maxMessage: 'El nombre no puede exceder los 255 caracteres.')]
    private ?string $nombre = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: 'El email no puede estar vacío.')]
    #[Assert\Email(message: 'El email "{{ value }}" no es válido.')]
    private ?string $email = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Assert\Length(max: 20, maxMessage: 'El teléfono no puede exceder los 20 caracteres.')]
    private ?string $telefono = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'El área responsable no puede estar vacía.')]
    #[Assert\Length(max: 100, maxMessage: 'El área responsable no puede exceder los 100 caracteres.')]
    private ?string $area_responsable = null;

    #[ORM\Column(options: ['default' => true])]
    private ?bool $activo = true; // Indica si la autoridad está activa

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

    public function isActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): static
    {
        $this->activo = $activo;

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
