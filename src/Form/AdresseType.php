<?php 

namespace App\Form;

use App\Entity\Adresses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormTypeInterface;

class AdresseType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomRue', TextType::class, ['label' => 'Votre adresse postale'])
            //->add('complements', TextType::class, ['label' => 'Compléments', 'required' => false])
            ->add('ville', TextType::class, ['label' => 'Ville'])
            ->add('codePostal', NumberType::class,   ['label' => 'Code postal'])            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adresses::class,
        ]);
    }
}