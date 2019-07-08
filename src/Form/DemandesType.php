<?php 

namespace App\Form;

use App\Entity\Demandes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormTypeInterface;

class DemandesType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('magasinAchat', TextType::class, ['label' => 'Magasin d\'achat'])
            ->add('marqueProduit', TextType::class, ['label' => 'Marque du produit'])
            ->add('referenceProduit', TextType::class, ['label' => 'Référence du produit'])
            ->add('numeroCommande', TextType::class,   ['label' => 'Numéro de commande',
            'help' => 'Le numéro de commande se trouve sur votre facture',
            'required' => false
            ])
            ->add('commentaires', TextareaType::class,   ['label' => 'Commentaires',
            'help' => 'Vous pouvez renseigner ici toutes informations complémentaires',
            'required' => false
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Demandes::class,
        ]);
    }
}