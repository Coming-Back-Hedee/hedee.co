<?php

namespace App\Form;

use App\Entity\ModeVersement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModeVersementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->setAction('gains')
            ->add('proprietaire', TextType::class, ['label' => 'Prénom et nom du titulaire du compte'])
            ->add('swiftBic', TextType::class, ['label' => 'Code Swift/Bic'])
            ->add('iban', TextType::class, ['label' => 'IBAN'])
            ->add('cancel', ButtonType::class, [
                'attr' => ['class' => 'cancel whiteo_button'],
                'label' => 'Annuler']) 
            ->add('submit', SubmitType::class, ['attr' => ['class' => 'orange_button'], 'label' => 'Je valide' ]) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Modeversement::class,
        ]);
    }
}
