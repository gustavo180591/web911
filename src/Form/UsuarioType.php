<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre',
                'attr' => ['placeholder' => 'Ingrese su nombre'],
                'required' => true,
            ])
            ->add('apellido', TextType::class, [
                'label' => 'Apellido',
                'attr' => ['placeholder' => 'Ingrese su apellido'],
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correo Electrónico',
                'attr' => ['placeholder' => 'Ingrese su correo electrónico'],
                'required' => true,
            ])
            ->add('telefono', TextType::class, [
                'label' => 'Teléfono',
                'attr' => ['placeholder' => 'Ingrese su número de teléfono'],
                'required' => true,
            ])
            ->add('direccion', TextType::class, [
                'label' => 'Dirección',
                'attr' => ['placeholder' => 'Ingrese su dirección'],
                'required' => false,
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Contraseña',
                'attr' => ['placeholder' => 'Ingrese su contraseña'],
                'required' => true,
            ])
            ->add('rol', ChoiceType::class, [
                'label' => 'Rol',
                'choices' => [
                    'Administrador' => 'ROLE_ADMIN',
                    'Moderador' => 'ROLE_MODERATOR',
                    'Usuario' => 'ROLE_USER',
                ],
                'placeholder' => 'Seleccione un rol',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'rol',
                    'onchange' => 'cambiarRol(this);'
                ]    
            ])
                        ->add('verificado', CheckboxType::class, [
                'label' => 'Cuenta Verificada',
                'required' => false,
            ])
            ->add('dni', TextType::class, [
                'label' => 'DNI',
                'attr' => ['placeholder' => 'Ingrese su DNI'],
                'required' => true,
            ])
            ->add('dni_frente', FileType::class, [
                'label' => 'Frente del DNI',
                'required' => true,
                'mapped' => false, // No está asociado directamente a la entidad
            ])
            ->add('dni_dorso', FileType::class, [
                'label' => 'Dorso del DNI',
                'required' => true,
                'mapped' => false, // No está asociado directamente a la entidad
            ])
            ->add('genero', ChoiceType::class, [
                'label' => 'Género',
                'choices' => [
                    'Masculino' => 'masculino',
                    'Femenino' => 'femenino',
                    'Otro' => 'otro',
                ],
                'expanded' => true,
                'required' => true,
            ])
            ->add('fecha_registro', DateType::class, [
                'label' => 'Fecha de Registro',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['readonly' => true], // Solo lectura
            ])
            ->add('failedAttempts', null, [
                'label' => 'Intentos Fallidos',
                'required' => false,
                'attr' => ['readonly' => true], // Solo lectura
            ])
            ->add('locked', CheckboxType::class, [
                'label' => 'Bloqueado',
                'required' => false,
            ])
            ->add('resetToken', TextType::class, [
                'label' => 'Token de Restablecimiento',
                'required' => false,
                'attr' => ['readonly' => true], // Solo lectura
            ])
            ->add('emailVerified', CheckboxType::class, [
                'label' => 'Email Verificado',
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
