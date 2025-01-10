<?php

namespace App\Form;

use App\Entity\Autoridad;
use App\Entity\Denuncia;
use App\Entity\Usuario;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DenunciaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categoria')
            ->add('descripcion')
            ->add('ubicacion')
            ->add('fecha_hora', null, [
                'widget' => 'single_text',
            ])
            ->add('numero_caso')
            ->add('estado')
            ->add('prioridad')
            ->add('motivo_anulacion')
            ->add('created_at', null, [
                'widget' => 'single_text',
            ])
            ->add('updated_at', null, [
                'widget' => 'single_text',
            ])
            ->add('usuario', EntityType::class, [
                'class' => Usuario::class,
                'choice_label' => 'id',
            ])
            ->add('autoridades', EntityType::class, [
                'class' => Autoridad::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Denuncia::class,
        ]);
    }
}
