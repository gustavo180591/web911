<?php

namespace App\Form;

use App\Entity\Autoridad;
use App\Entity\Denuncia;
use App\Entity\Usuario;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DenunciaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categoria', ChoiceType::class, [
                'label' => 'Categoría',
                'choices' => [
                    'Robo' => 'robo',
                    'Vandalismo' => 'vandalismo',
                    'Violencia' => 'violencia',
                    'Otro' => 'otro',
                ],
                'placeholder' => 'Seleccione una categoría',
            ])
            ->add('descripcion', TextareaType::class, [
                'label' => 'Descripción',
                'attr' => [
                    'placeholder' => 'Describe el incidente con detalles',
                    'maxlength' => 1000, // Validación de longitud máxima
                ],
            ])
            ->add('ubicacion', null, [
                'label' => 'Ubicación',
                'attr' => ['placeholder' => 'Ingrese la ubicación exacta del incidente'],
            ])
            ->add('fecha_hora', null, [
                'label' => 'Fecha y Hora del Incidente',
                'widget' => 'single_text',
            ])
            ->add('numero_caso', null, [
                'label' => 'Número de Caso',
                'attr' => ['readonly' => true], // Solo lectura
            ])
            ->add('estado', ChoiceType::class, [
                'label' => 'Estado de la Denuncia',
                'choices' => [
                    'Pendiente' => 'pendiente',
                    'En Proceso' => 'en_proceso',
                    'Resuelto' => 'resuelto',
                    'Anulado' => 'anulado',
                ],
            ])
            ->add('prioridad', ChoiceType::class, [
                'label' => 'Prioridad',
                'choices' => [
                    'Alta' => 'alta',
                    'Media' => 'media',
                    'Baja' => 'baja',
                ],
            ])
            ->add('motivo_anulacion', TextareaType::class, [
                'label' => 'Motivo de Anulación (si aplica)',
                'required' => false,
            ])
            ->add('created_at', null, [
                'label' => 'Fecha de Creación',
                'widget' => 'single_text',
                'attr' => ['readonly' => true], // Solo lectura
            ])
            ->add('updated_at', null, [
                'label' => 'Última Actualización',
                'widget' => 'single_text',
                'attr' => ['readonly' => true], // Solo lectura
            ])
            ->add('usuario', EntityType::class, [
                'label' => 'Usuario',
                'class' => Usuario::class,
                'choice_label' => 'email', // Mostrar email como opción
                'required' => false, // Permitimos que sea anónimo
                'placeholder' => 'Seleccione un usuario',
            ])
            ->add('autoridades', EntityType::class, [
                'label' => 'Autoridades Asignadas',
                'class' => Autoridad::class,
                'choice_label' => 'nombre', // Mostrar nombre como opción
                'multiple' => true,
                'required' => false,
            ])
            ->add('anonima', CheckboxType::class, [
                'label' => '¿Realizar esta denuncia de forma anónima?',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Denuncia::class,
        ]);
    }
}
