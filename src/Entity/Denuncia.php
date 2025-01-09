<?php

namespace App\Entity;

use App\Repository\DenunciaRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DenunciaRepository::class)]
#[ORM\HasLifecycleCallbacks]
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
    #[Assert\NotBlank(message: 'La categoría no puede estar vacía.')]
    #[Assert\Choice(choices: ['robo', 'vandalismo', 'violencia'], message: 'La categoría debe ser válida.')]
    private ?string $categoria = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: 'La descripción no puede estar vacía.')]
    #[Assert\Length(min: 10, minMessage: 'La descripción debe tener al menos 10 caracteres.')]
    private ?string $descripcion = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La ubicación no puede estar vacía.')]
    private ?string $ubicacion = null; // Puede ser texto o coordenadas

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(message: 'La fecha y hora no pueden estar vacías.')]
    private ?\DateTimeInterface $fecha_hora = null;

    #[ORM\Column(length: 50, unique: true)]
    #[Assert\NotBlank(message: 'El número de caso no puede estar vacío.')]
    private ?string $numero_caso = null; // Número único para cada caso

    #[ORM\Column(length: 50, options: ['default' => 'pendiente'])]
    #[Assert\NotBlank(message: 'El estado no puede estar vacío.')]
    #[Assert\Choice(choices: ['pendiente', 'en proceso', 'resuelto'], message: 'El estado debe ser válido.')]
    private ?string $estado = 'pendiente'; // Valores como "pendiente", "en proceso", "resuelto"

    #[ORM\Column(length: 20, options: ['default' => 'baja'])]
    #[Assert\NotBlank(message: 'La prioridad no puede estar vacía.')]
    #[Assert\Choice(choices: ['baja', 'media', 'alta'], message: 'La prioridad debe ser válida.')]
    private ?string $prioridad = 'baja'; // Niveles de urgencia

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $motivo_anulacion = null; // Motivo de anulación, si aplica

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $created_at = null; // Fecha de creación

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updated_at = null; // Fecha de última actualización

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

    public function getPrioridad(): ?string
    {
        return $this->prioridad;
    }

    public function setPrioridad(string $prioridad): static
    {
        $this->prioridad = $prioridad;

        return $this;
    }

    public function getMotivoAnulacion(): ?string
    {
        return $this->motivo_anulacion;
    }

    public function setMotivoAnulacion(?string $motivo_anulacion): static
    {
        $this->motivo_anulacion = $motivo_anulacion;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->created_at = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updated_at = new \DateTimeImmutable();
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
