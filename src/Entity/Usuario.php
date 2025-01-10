<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
class Usuario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'El nombre no puede estar vacío.')]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'El apellido no puede estar vacío.')]
    private ?string $apellido = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: 'El email no puede estar vacío.')]
    #[Assert\Email(message: 'El email "{{ value }}" no es válido.')]
    private ?string $email = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: 'El teléfono no puede estar vacío.')]
    #[Assert\Length(max: 20, maxMessage: 'El teléfono no puede tener más de 20 caracteres.')]
    private ?string $telefono = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $direccion = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La contraseña no puede estar vacía.')]
    #[Assert\Length(min: 8, minMessage: 'La contraseña debe tener al menos 8 caracteres.')]
    private ?string $password = null;

    #[ORM\Column(length: 50, options: ['default' => 'usuario'])]
    #[Assert\NotBlank(message: 'El rol no puede estar vacío.')]
    #[Assert\Choice(choices: ['usuario', 'autoridad'], message: 'El rol debe ser "usuario" o "autoridad".')]
    private ?string $rol = 'usuario';

    #[ORM\Column(options: ['default' => false])]
    private ?bool $verificado = false;

    #[ORM\Column(length: 15, unique: true)]
    #[Assert\NotBlank(message: 'El DNI no puede estar vacío.')]
    #[Assert\Length(max: 15, maxMessage: 'El DNI no puede tener más de 15 caracteres.')]
    private ?string $dni = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'El frente del DNI no puede estar vacío.')]
    private ?string $dni_frente = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'El dorso del DNI no puede estar vacío.')]
    private ?string $dni_dorso = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: 'El género no puede estar vacío.')]
    #[Assert\Choice(choices: ['masculino', 'femenino', 'otro'], message: 'El género debe ser "masculino", "femenino" o "otro".')]
    private ?string $genero = null;

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $fecha_registro = null;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $failedAttempts = 0;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $locked = false;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $resetToken = null;


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

    public function setTelefono(string $telefono): static
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): static
    {
        $this->direccion = $direccion;

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

    public function getRol(): ?string
    {
        return $this->rol;
    }

    public function setRol(string $rol): static
    {
        $this->rol = $rol;

        return $this;
    }

    public function isVerificado(): ?bool
    {
        return $this->verificado;
    }

    public function setVerificado(bool $verificado): static
    {
        $this->verificado = $verificado;

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

    public function getDniFrente(): ?string
    {
        return $this->dni_frente;
    }

    public function setDniFrente(string $dni_frente): static
    {
        $this->dni_frente = $dni_frente;

        return $this;
    }

    public function getDniDorso(): ?string
    {
        return $this->dni_dorso;
    }

    public function setDniDorso(string $dni_dorso): static
    {
        $this->dni_dorso = $dni_dorso;

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

    public function getFechaRegistro(): ?\DateTimeInterface
    {
        return $this->fecha_registro;
    }

    public function setFechaRegistro(\DateTimeInterface $fecha_registro): static
    {
        $this->fecha_registro = $fecha_registro;

        return $this;
    }
}
