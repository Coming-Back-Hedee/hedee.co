<?php 

namespace App\Form;

use App\Entity\Demandes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class DemandesInternetType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('urlProduit', UrlType::class, ['label' => 'Renseignez l\'URL de la page du produit'])
            ->add('numeroCommande', TextType::class,   ['label' => 'Indiquez le numéro de commande',
            'help' => 'Le numéro de commande se trouve sur votre facture',
            'required' => false
            ])
            //->add('pieceJointe', FileType::class,   ['label' => 'Votre facture', 'required' => false])
            ->add('client', ClientType::class,   ['label' => 'Informations client'])
            ->add('cgu', CheckboxType::class,   ['label' => 'J\'accepte les Conditions générales d\'utilisation',
            'attr' => [
                'class' => 'lecture_cgu'
            ]])       
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Demandes::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'demandes';
    }
}