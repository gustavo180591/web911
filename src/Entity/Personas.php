<?php

namespace App\Entity;

use App\Repository\PersonasRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonasRepository::class)]
class Personas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $apellido = null;

    #[ORM\Column]
    private ?int $dni = null;

    #[ORM\Column(length: 255)]
    private ?string $fotodni = null;

    #[ORM\Column(length: 255)]
    private ?string $telefono = null;

    #[ORM\Column(length: 255)]
    private ?string $correo = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fechadenacimiento = null;

    #[ORM\Column(length: 255)]
    private ?string $calle = null;

    #[ORM\Column(length: 255)]
    private ?string $numero = null;

    #[ORM\Column(length: 255)]
    private ?string $detalleubicacion = null;

    #[ORM\Column(length: 255)]
    private ?string $validacionubicacion = null;

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

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): static
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function getDni(): ?int
    {
        return $this->dni;
    }

    public function setDni(int $dni): static
    {
        $this->dni = $dni;

        return $this;
    }

    public function getFotodni(): ?string
    {
        return $this->fotodni;
    }

    public function setFotodni(string $fotodni): static
    {
        $this->fotodni = $fotodni;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): static
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getCorreo(): ?string
    {
        return $this->correo;
    }

    public function setCorreo(string $correo): static
    {
        $this->correo = $correo;

        return $this;
    }

    public function getFechadenacimiento(): ?\DateTimeInterface
    {
        return $this->fechadenacimiento;
    }

    public function setFechadenacimiento(\DateTimeInterface $fechadenacimiento): static
    {
        $this->fechadenacimiento = $fechadenacimiento;

        return $this;
    }

    public function getCalle(): ?string
    {
        return $this->calle;
    }

    public function setCalle(string $calle): static
    {
        $this->calle = $calle;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getDetalleubicacion(): ?string
    {
        return $this->detalleubicacion;
    }

    public function setDetalleubicacion(string $detalleubicacion): static
    {
        $this->detalleubicacion = $detalleubicacion;

        return $this;
    }

    public function getValidacionubicacion(): ?string
    {
        return $this->validacionubicacion;
    }

    public function setValidacionubicacion(string $validacionubicacion): static
    {
        $this->validacionubicacion = $validacionubicacion;

        return $this;
    }
}
