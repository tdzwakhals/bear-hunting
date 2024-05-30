<?php

declare(strict_types=1);

namespace App\Form;

use App\DTO\Request\LocationDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

final class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('latitude', NumberType::class, [
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('longitude', NumberType::class, [
                'constraints' => [
                    new NotBlank(),
                ]
            ])
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
