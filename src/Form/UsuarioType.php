<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('apellido')
            ->add('email')
            ->add('telefono')
            ->add('direccion')
            ->add('password')
            ->add('rol')
            ->add('verificado')
            ->add('dni')
            ->add('dni_frente')
            ->add('dni_dorso')
            ->add('genero')
            ->add('fecha_registro', null, [
                'widget' => 'single_text',
            ])
            ->add('failedAttempts')
            ->add('locked')
            ->add('resetToken')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
