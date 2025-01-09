<?php

namespace App\Entity;

use App\Repository\ReporteEstadisticoRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReporteEstadisticoRepository::class)]
class ReporteEstadistico
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'El tipo no puede estar vacío.')]
    #[Assert\Choice(choices: ['mensual', 'semanal', 'personalizado'], message: 'El tipo debe ser "mensual", "semanal" o "personalizado".')]
    private ?string $tipo = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotNull(message: 'La fecha de inicio no puede estar vacía.')]
    #[Assert\LessThan(propertyPath: 'fecha_fin', message: 'La fecha de inicio debe ser anterior a la fecha de fin.')]
    private ?\DateTimeInterface $fecha_inicio = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotNull(message: 'La fecha de fin no puede estar vacía.')]
    private ?\DateTimeInterface $fecha_fin = null;

    #[ORM\Column(type: 'json')]
    #[Assert\NotNull(message: 'Los datos no pueden estar vacíos.')]
    private ?array $datos = null;

    #[ORM\ManyToMany(targetEntity: Denuncia::class)]
    private Collection $denuncias; // Relación ManyToMany con Denuncia

    #[ORM\ManyToOne(targetEntity: Autoridad::class, inversedBy: 'reportes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Autoridad $autoridad = null; // Relación ManyToOne con Autoridad

    public function __construct()
    {
        $this->denuncias = new ArrayCollection();
    }

    // Getters y setters para cada propiedad

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): static
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getFechaInicio(): ?\DateTimeInterface
    {
        return $this->fecha_inicio;
    }

    public function setFechaInicio(\DateTimeInterface $fecha_inicio): static
    {
        $this->fecha_inicio = $fecha_inicio;

        return $this;
    }

    public function getFechaFin(): ?\DateTimeInterface
    {
        return $this->fecha_fin;
    }

    public function setFechaFin(\DateTimeInterface $fecha_fin): static
    {
        $this->fecha_fin = $fecha_fin;

        return $this;
    }

    public function getDatos(): ?array
    {
        return $this->datos;
    }

    public function setDatos(array $datos): static
    {
        $this->datos = $datos;

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
        }

        return $this;
    }

    public function removeDenuncia(Denuncia $denuncia): static
    {
        $this->denuncias->removeElement($denuncia);

        return $this;
    }

    public function getAutoridad(): ?Autoridad
    {
        return $this->autoridad;
    }

    public function setAutoridad(?Autoridad $autoridad): static
    {
        $this->autoridad = $autoridad;

        return $this;
    }
}
