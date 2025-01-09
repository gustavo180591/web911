<?php

namespace App\Entity;

use App\Repository\DenunciaRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: DenunciaRepository::class)]
class Denuncia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Usuario::class, inversedBy: 'denuncias')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Usuario $usuario = null; // FK para denuncias anónimas

    #[ORM\Column(length: 100)]
    private ?string $categoria = null;

    #[ORM\Column(type: 'text')]
    private ?string $descripcion = null;

    #[ORM\Column(length: 255)]
    private ?string $ubicacion = null; // Puede ser texto o coordenadas

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $fecha_hora = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $numero_caso = null; // Número único para cada caso

    #[ORM\Column(length: 50)]
    private ?string $estado = null; // Valores como "pendiente", "en proceso", "resuelto"

    #[ORM\ManyToMany(targetEntity: Autoridad::class, inversedBy: 'denuncias')]
    private Collection $autoridades; // Relación ManyToMany con Autoridad

    public function __construct()
    {
        $this->autoridades = new ArrayCollection();
    }

    // Getters y setters para cada propiedad

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

    public function getCategoria(): ?string
    {
        return $this->categoria;
    }

    public function setCategoria(string $categoria): static
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getUbicacion(): ?string
    {
        return $this->ubicacion;
    }

    public function setUbicacion(string $ubicacion): static
    {
        $this->ubicacion = $ubicacion;

        return $this;
    }

    public function getFechaHora(): ?\DateTimeInterface
    {
        return $this->fecha_hora;
    }

    public function setFechaHora(\DateTimeInterface $fecha_hora): static
    {
        $this->fecha_hora = $fecha_hora;

        return $this;
    }

    public function getNumeroCaso(): ?string
    {
        return $this->numero_caso;
    }

    public function setNumeroCaso(string $numero_caso): static
    {
        $this->numero_caso = $numero_caso;

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

    /**
     * @return Collection<int, Autoridad>
     */
    public function getAutoridades(): Collection
    {
        return $this->autoridades;
    }

    public function addAutoridad(Autoridad $autoridad): static
    {
        if (!$this->autoridades->contains($autoridad)) {
            $this->autoridades->add($autoridad);
            $autoridad->addDenuncia($this);
        }

        return $this;
    }

    public function removeAutoridad(Autoridad $autoridad): static
    {
        if ($this->autoridades->removeElement($autoridad)) {
            $autoridad->removeDenuncia($this);
        }

        return $this;
    }
}
