<?php

namespace App\Entity;

use App\Repository\NotificacionRepository;
use Doctrine\ORM\Mapping as ORM;

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

    #[ORM\Column(type: 'text')]
    private ?string $mensaje = null; // Mensaje de la notificación

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $fecha_hora = null; // Fecha y hora de la notificación

    #[ORM\Column]
    private ?bool $leida = null; // Indica si la notificación fue leída

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
