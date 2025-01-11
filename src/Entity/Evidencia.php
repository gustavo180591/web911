<?php

namespace App\Entity;

use App\Repository\EvidenciaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EvidenciaRepository::class)]
class Evidencia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Denuncia::class, inversedBy: 'evidencias')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Denuncia $denuncia = null; // FK a la tabla Denuncia

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'El tipo de evidencia no puede estar vacío.')]
    #[Assert\Choice(choices: ['imagen', 'video', 'audio', 'documento'], message: 'El tipo debe ser válido (imagen, video, audio, documento).')]
    private ?string $tipo = null; // Tipos soportados

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'El archivo no puede estar vacío.')]
    private ?string $archivo = null; // Ruta o nombre del archivo

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\Positive(message: 'El tamaño del archivo debe ser positivo.')]
    private ?int $tamano = null; // Tamaño del archivo en bytes

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $formato = null; // Formato del archivo (e.g., jpg, mp4, mp3)

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $fecha_subida = null; // Fecha de subida

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDenuncia(): ?Denuncia
    {
        return $this->denuncia;
    }

    public function setDenuncia(?Denuncia $denuncia): static
    {
        $this->denuncia = $denuncia;

        return $this;
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

    public function getArchivo(): ?string
    {
        return $this->archivo;
    }

    public function setArchivo(string $archivo): static
    {
        $this->archivo = $archivo;

        return $this;
    }

    public function getTamano(): ?int
    {
        return $this->tamano;
    }

    public function setTamano(?int $tamano): static
    {
        $this->tamano = $tamano;

        return $this;
    }

    public function getFormato(): ?string
    {
        return $this->formato;
    }

    public function setFormato(?string $formato): static
    {
        $this->formato = $formato;

        return $this;
    }

    public function getFechaSubida(): ?\DateTimeInterface
    {
        return $this->fecha_subida;
    }

    public function setFechaSubida(\DateTimeInterface $fecha_subida): static
    {
        $this->fecha_subida = $fecha_subida;

        return $this;
    }
}
