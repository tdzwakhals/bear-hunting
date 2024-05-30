<?php

declare(strict_types=1);

namespace App\Form\Request;

use App\Entity\Bear;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class BearType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('location', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('province', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('latitude', NumberType::class, [
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('longitude', NumberType::class, [
                'constraints' => [
                    new NotBlank(),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bear::class,
            'csrf_protection' => false,
        ]);
    }
}