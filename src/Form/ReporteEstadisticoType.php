<?php

namespace App\Form;

use App\Entity\Autoridad;
use App\Entity\Denuncia;
use App\Entity\ReporteEstadistico;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReporteEstadisticoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tipo')
            ->add('fecha_inicio', null, [
                'widget' => 'single_text',
            ])
            ->add('fecha_fin', null, [
                'widget' => 'single_text',
            ])
            ->add('datos')
            ->add('denuncias', EntityType::class, [
                'class' => Denuncia::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('autoridad', EntityType::class, [
                'class' => Autoridad::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReporteEstadistico::class,
        ]);
    }
}
