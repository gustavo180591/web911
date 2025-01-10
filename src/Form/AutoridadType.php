<?php

namespace App\Form;

use App\Entity\Autoridad;
use App\Entity\Denuncia;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AutoridadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('email')
            ->add('telefono')
            ->add('area_responsable')
            ->add('activo')
            ->add('denuncias', EntityType::class, [
                'class' => Denuncia::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Autoridad::class,
        ]);
    }
}
