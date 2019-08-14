<?php

namespace App\Form;

use App\Entity\AlertePrix;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClotureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('montantCloture', MoneyType::class, [
                'label' => 'Montant du remboursement', 
                'currency' => false, 
                'required' =>false
                ])
            ->add('clotureR', SubmitType::class, ['label' => 'Remboursement'])
            ->add('clotureNR',SubmitType::class, ['label' => 'Non remboursement'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AlertePrix::class,
        ]);
    }
}
