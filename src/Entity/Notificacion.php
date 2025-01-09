<?php

namespace App\Entity;

use App\Repository\NotificacionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NotificacionRepository::class)]
class Notificacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Usuario::class, inversedBy: 'notificaciones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $usuario = null; // FK a la tabla Usuario

    #[ORM\ManyToOne(targetEntity: Denuncia::class, inversedBy: 'notificaciones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Denuncia $denuncia = null; // FK a la tabla Denuncia

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'El tipo de notificación no puede estar vacío.')]
    #[Assert\Choice(choices: ['informativa', 'alerta', 'urgente'], message: 'El tipo debe ser válido (informativa, alerta, urgente).')]
    private ?string $tipo = null; // Tipo de notificación: informativa, alerta, etc.

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: 'El mensaje no puede estar vacío.')]
    #[Assert\Length(min: 5, minMessage: 'El mensaje debe tener al menos 5 caracteres.')]
    private ?string $mensaje = null; // Mensaje de la notificación

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $fecha_hora = null; // Fecha y hora de la notificación

    #[ORM\Column(options: ['default' => false])]
    private ?bool $leida = false; // Indica si la notificación fue leída

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

    public function getMensaje(): ?string
    {
        return $this->mensaje;
    }

    public function setMensaje(string $mensaje): static
    {
        $this->mensaje = $mensaje;

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

    public function isLeida(): ?bool
    {
        return $this->leida;
    }

    public function setLeida(bool $leida): static
    {
        $this->leida = $leida;

        return $this;
    }
}
