<?php
// src/Entity/Person.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Person
{
    /** @ORM\Id @ORM\GeneratedValue @ORM\Column(type="integer") */
    private $id;

    /** @ORM\Column(type="string", length=100) */
    private $nombre;

    /** @ORM\Column(type="string", length=100) */
    private $apellido;

    /** @ORM\Column(type="string", length=15) */
    private $numeroDni;

    /** @ORM\Column(type="string", nullable=true) */
    private $fotoDniFrente;

    /** @ORM\Column(type="string", nullable=true) */
    private $fotoDniDorso;

    /** @ORM\Column(type="string", length=15) */
    private $telefono;

    /** @ORM\Column(type="string") @Assert\Email */
    private $correo;

    /** @ORM\Column(type="date") */
    private $fechaNacimiento;

    /** @ORM\Embedded(class="Domicilio") */
    private $domicilio;

    public function __construct()
    {
        $this->domicilio = new Domicilio();
    }

    // Getters and setters...
}

/**
 * @ORM\Embeddable
 */
class Domicilio
{
    /** @ORM\Column(type="string", length=100) */
    private $calle;

    /** @ORM\Column(type="string", length=10) */
    private $numero;

    /** @ORM\Column(type="text", nullable=true) */
    private $detallesUbicacion;

    /** @ORM\Column(type="boolean") */
    private $validacionUbicacion = false;

    // Getters and setters...
}

?>
