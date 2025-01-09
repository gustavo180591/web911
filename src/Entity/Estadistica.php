<?php

namespace App\Entity;

use App\Repository\EstadisticaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EstadisticaRepository::class)]
class Estadistica
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $tipoDenuncia = null;

    #[ORM\Column]
    private ?int $cantidad = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $otrasMetricas = null;

    /**
     * @var Collection<int, Denuncia>
     */
    #[ORM\OneToMany(targetEntity: Denuncia::class, mappedBy: 'estadistica')]
    private Collection $denuncias;

    public function __construct()
    {
        $this->denuncias = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipoDenuncia(): ?string
    {
        return $this->tipoDenuncia;
    }

    public function setTipoDenuncia(string $tipoDenuncia): static
    {
        $this->tipoDenuncia = $tipoDenuncia;

        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): static
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getOtrasMetricas(): ?string
    {
        return $this->otrasMetricas;
    }

    public function setOtrasMetricas(?string $otrasMetricas): static
    {
        $this->otrasMetricas = $otrasMetricas;

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
            $denuncia->setEstadistica($this);
        }

        return $this;
    }

    public function removeDenuncia(Denuncia $denuncia): static
    {
        if ($this->denuncias->removeElement($denuncia)) {
            // set the owning side to null (unless already changed)
            if ($denuncia->getEstadistica() === $this) {
                $denuncia->setEstadistica(null);
            }
        }

        return $this;
    }
}
