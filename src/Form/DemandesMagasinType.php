<?php 

namespace App\Form;

use App\Entity\Clients;
use App\Entity\Demandes;

use App\Form\ClientType;

use App\Repository\MagasinsRepository;

use Symfony\Component\Routing\RouterInterface;
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
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class DemandesMagasinType extends AbstractType
{
    private $router;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ville', TextType::class, ['label' => 'Ville du magasin d\'achat'])
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
            ->add('pieceJointe', FileType::class,   ['label' => 'Votre facture', 'mapped' => false, 'required' => false])
            ->add('client', ClientType::class,   ['label' => 'Informations client'])
            ->add('cgu', CheckboxType::class,   ['label' => 'Conditions générales d\'utilisation'])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'client' => Clients::class,
            'data_class' => Demandes::class,
        ]);
    }

    public function __construct(MagasinsRepository $magasinsRepository, RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getBlockPrefix()
    {
        return 'demandes';
    }
}