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

            ->add('categorieProduit', ChoiceType::class, [ 'choices' => [
                "Selectionnez votre categorie" => null,
                'Produits électroniques' => 'produits_electroniques',
                'Maisons et jardins' => 'maisons_et_jardins',
                'Jeux vidéos et jouets' => 'jvideos_et_jouets',
                'Santé et beauté' => 'sante_et_beaute',              
                'Auto et moto' => 'auto_et_moto',
                'Sports et mode' => 'sports_et_mode'
            ],
            'label' => 'Catégorie du produit d\'achat'
            ])

            ->add('enseigne', TextType::class, ['label' => 'Enseigne d\'achat'])
            ->add('dateAchat', TextType::class, ['label' => 'Date d\'achat'])       
            ->add('prixAchat', MoneyType::class, ['label' => 'Prix de l\'article', 'currency' => false])
            ->add('urlProduit', UrlType::class, ['label' => 'URL du produit d\'achat'])
            ->add('numeroCommande', TextType::class,   ['label' => 'Numéro de commande',
            'help' => 'Le numéro de commande se trouve sur votre facture',
            'required' => false
            ])
            //->add('pieceJointe', FileType::class,   ['label' => 'Votre facture', 'required' => false])
            ->add('client', ClientType::class,   ['label' => 'Informations client'])
            ->add('cgu', CheckboxType::class,   ['label' => 'Conditions générales d\'utilisation'])       
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