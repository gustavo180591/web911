<?php

namespace App\Form;

use App\Entity\Denuncia;
use App\Entity\Evidencia;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvidenciaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tipo', ChoiceType::class, [
                'label' => 'Tipo de Evidencia',
                'choices' => [
                    'Foto' => 'foto',
                    'Video' => 'video',
                    'Audio' => 'audio',
                    'Documento' => 'documento',
                ],
            ])
            ->add('archivo', FileType::class, [
                'label' => 'Archivo de Evidencia',
                'multiple' => false, // Cambiar a true si se permite subir múltiples archivos en un solo formulario
                'attr' => [
                    'accept' => 'image/*,video/*,audio/*,.pdf,.doc,.docx', // Tipos de archivos permitidos
                ],
            ])
            ->add('denuncia', EntityType::class, [
                'class' => Denuncia::class,
                'choice_label' => 'id',
                'label' => 'Denuncia Asociada',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evidencia::class,
        ]);
    }
}
