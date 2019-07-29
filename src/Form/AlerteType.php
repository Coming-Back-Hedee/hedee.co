<?php

namespace App\Form;

use App\Entity\AlertePrix;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlerteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prix', MoneyType::class, ['label' => 'Prix constaté', 'currency' => false])
            ->add('enseigne', TextType::class, ['label' => 'Enseigne la moins chère'])
            ->add('date', DateType::class, ['label' => 'Date du constat', 'widget' => 'single_text',])
            ->add('differencePrix', MoneyType::class, ['label' => 'Différence de prix', 'currency' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AlertePrix::class,
        ]);
    }
}
