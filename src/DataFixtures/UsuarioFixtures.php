<?php

namespace App\DataFixtures;

use App\Entity\Usuario;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsuarioFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Crear un usuario administrador
        $admin = new Usuario();
        $admin->setNombre('Admin')
            ->setApellido('Administrador')
            ->setEmail('admin@example.com')
            ->setTelefono('123456789')
            ->setDireccion('Calle Admin 123')
            ->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'))
            ->setRol('admin')
            ->setVerificado(true)
            ->setDni('12345678')
            ->setDniFrente('dni_frente_admin.jpg')
            ->setDniDorso('dni_dorso_admin.jpg')
            ->setGenero('masculino');
        $manager->persist($admin);

        // Crear un usuario moderador
        $moderador = new Usuario();
        $moderador->setNombre('Moderador')
            ->setApellido('Mod')
            ->setEmail('moderador@example.com')
            ->setTelefono('987654321')
            ->setDireccion('Calle Moderador 456')
            ->setPassword($this->passwordHasher->hashPassword($moderador, 'moderador123'))
            ->setRol('moderador')
            ->setVerificado(true)
            ->setDni('87654321')
            ->setDniFrente('dni_frente_moderador.jpg')
            ->setDniDorso('dni_dorso_moderador.jpg')
            ->setGenero('femenino');
        $manager->persist($moderador);

        // Crear un usuario estándar
        $usuario = new Usuario();
        $usuario->setNombre('Usuario')
            ->setApellido('Normal')
            ->setEmail('usuario@example.com')
            ->setTelefono('555666777')
            ->setDireccion('Calle Usuario 789')
            ->setPassword($this->passwordHasher->hashPassword($usuario, 'usuario123'))
            ->setRol('usuario')
            ->setVerificado(true)
            ->setDni('11223344')
            ->setDniFrente('dni_frente_usuario.jpg')
            ->setDniDorso('dni_dorso_usuario.jpg')
            ->setGenero('otro');
        $manager->persist($usuario);

        // Guardar los usuarios en la base de datos
        $manager->flush();
    }
}
