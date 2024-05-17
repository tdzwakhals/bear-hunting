<?php

namespace App\Form\Request;

use App\DTO\Request\LocationDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('latitude', NumberType::class)
            ->add('longitude', NumberType::class)
            ->add('radius', IntegerType::class, ['required' => false, 'empty_data' => 25]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LocationDTO::class,
            'csrf_protection' => false,
        ]);
    }
}
