<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre',
                'attr' => ['placeholder' => 'Ingresa tu nombre'],
            ])
            ->add('apellido', TextType::class, [
                'label' => 'Apellido',
                'attr' => ['placeholder' => 'Ingresa tu apellido'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correo Electrónico',
                'attr' => ['placeholder' => 'Ingresa tu correo'],
            ])
            ->add('telefono', TelType::class, [
                'label' => 'Teléfono',
                'attr' => ['placeholder' => 'Ingresa tu número de teléfono'],
            ])
            ->add('direccion', TextType::class, [
                'label' => 'Dirección',
                'attr' => ['placeholder' => 'Ingresa tu dirección'],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Contraseña',
                'attr' => ['placeholder' => 'Ingresa tu contraseña'],
            ])
            ->add('rol', ChoiceType::class, [
                'label' => 'Rol',
                'choices' => [
                    'Usuario' => 'ROLE_USER',
                    'Moderador' => 'ROLE_MODERATOR',
                    'Administrador' => 'ROLE_ADMIN',
                ],
            ])
            ->add('dni', TextType::class, [
                'label' => 'DNI',
                'attr' => ['placeholder' => 'Ingresa tu DNI'],
            ])
            ->add('dni_frente', FileType::class, [
                'label' => 'Foto DNI (Frente)',
                'required' => false,
            ])
            ->add('dni_dorso', FileType::class, [
                'label' => 'Foto DNI (Dorso)',
                'required' => false,
            ])
            ->add('genero', ChoiceType::class, [
                'label' => 'Género',
                'choices' => [
                    'Masculino' => 'M',
                    'Femenino' => 'F',
                    'Otro' => 'O',
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('fecha_registro', DateType::class, [
                'label' => 'Fecha de Registro',
                'widget' => 'single_text',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
