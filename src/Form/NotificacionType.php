<?php

namespace App\Form;

use App\Entity\Denuncia;
use App\Entity\Notificacion;
use App\Entity\Usuario;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotificacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tipo')
            ->add('mensaje')
            ->add('fecha_hora', null, [
                'widget' => 'single_text',
            ])
            ->add('leida')
            ->add('usuario', EntityType::class, [
                'class' => Usuario::class,
                'choice_label' => 'id',
            ])
            ->add('denuncia', EntityType::class, [
                'class' => Denuncia::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Notificacion::class,
        ]);
    }
}
