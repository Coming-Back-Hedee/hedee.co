<?php
namespace App\Form;
 
use App\Entity\Clients;
use App\Entity\Adresses;

use App\Form\AdresseType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
 
class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nom', TextType::class, ['label' => 'Nom'])
        ->add('prenom', TextType::class, ['label' => 'Prénom'])
        ->add('numeroTelephone', TextType::class, ['label' => 'Numéro de téléphone'])
        //->add('codeParrainage', TextType::class, ['label' => 'Code Parrainage'])
        ->add('adresse', AdresseType::class)
            ;
    }
 
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Clients::class,
        ));
    }
}