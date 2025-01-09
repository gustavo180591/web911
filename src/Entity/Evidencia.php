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
    private ?string $tipo = null; // imagen, video, audio, documento

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'El archivo no puede estar vacío.')]
    #[Assert\Url(message: 'El archivo debe ser una URL válida.')]
    private ?string $archivo = null; // URL o ruta del archivo

    // Getters y setters para cada propiedad

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
}
