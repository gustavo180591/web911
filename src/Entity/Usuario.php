<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
class Usuario
{
    #[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(name: "id_usuario", type: "integer")]
private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $apellido = null;

    #[ORM\Column(length: 20)]
    private ?string $dni = null;

    #[ORM\Column(type: Types::BLOB)]
    private $fotoDni = null;

    #[ORM\Column(length: 15)]
    private ?string $telefono = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaNacimiento = null;

    #[ORM\Column(length: 10)]
    private ?string $genero = null;

    #[ORM\Column(length: 255)]
    private ?string $calle = null;

    #[ORM\Column(length: 10)]
    private ?string $numero = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $detallesUbicacion = null;

    #[ORM\Column]
    private ?bool $validacionUbicacion = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $fechaRegistro = null;

    #[ORM\Column]
    private ?int $nivelUsuario = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\ManyToOne(inversedBy: 'usuarios')]
    private ?Denuncia $denuncias = null;

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

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(string $dni): static
    {
        $this->dni = $dni;

        return $this;
    }

    public function getFotoDni()
    {
        return $this->fotoDni;
    }

    public function setFotoDni($fotoDni): static
    {
        $this->fotoDni = $fotoDni;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getFechaNacimiento(): ?\DateTimeInterface
    {
        return $this->fechaNacimiento;
    }

    public function setFechaNacimiento(\DateTimeInterface $fechaNacimiento): static
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    public function getGenero(): ?string
    {
        return $this->genero;
    }

    public function setGenero(string $genero): static
    {
        $this->genero = $genero;

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

    public function getDetallesUbicacion(): ?string
    {
        return $this->detallesUbicacion;
    }

    public function setDetallesUbicacion(?string $detallesUbicacion): static
    {
        $this->detallesUbicacion = $detallesUbicacion;

        return $this;
    }

    public function isValidacionUbicacion(): ?bool
    {
        return $this->validacionUbicacion;
    }

    public function setValidacionUbicacion(bool $validacionUbicacion): static
    {
        $this->validacionUbicacion = $validacionUbicacion;

        return $this;
    }

    public function getFechaRegistro(): ?\DateTimeImmutable
    {
        return $this->fechaRegistro;
    }

    public function setFechaRegistro(\DateTimeImmutable $fechaRegistro): static
    {
        $this->fechaRegistro = $fechaRegistro;

        return $this;
    }

    public function getNivelUsuario(): ?int
    {
        return $this->nivelUsuario;
    }

    public function setNivelUsuario(int $nivelUsuario): static
    {
        $this->nivelUsuario = $nivelUsuario;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getDenuncias(): ?Denuncia
    {
        return $this->denuncias;
    }

    public function setDenuncias(?Denuncia $denuncias): static
    {
        $this->denuncias = $denuncias;

        return $this;
    }
}
