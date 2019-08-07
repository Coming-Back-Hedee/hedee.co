<?php
namespace App\Form;
 
use App\Entity\Clients;
use App\Entity\Adresses;

use App\Form\AdresseType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
 
class InfoClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom', TextType::class, ['label' => 'Prenom', 'required' => false])
            ->add('nom', TextType::class, ['label' => 'Nom', 'required' => false])
            ->add('numeroTelephone', TextType::class, ['label' => 'Numéro de téléphone', 'required' => false])
            ->add('dateNaissance', DateType::class, [
                'label' => 'Date de naissance',
                'required' => false,
                'years' => range(date('Y')-10, date('Y')-110)] )
            ->add('adresse',AdresseType::class, ['label' => 'Adresse','required' => false])           
            ->add('submit', SubmitType::class, ['label' => 'Valider']) 
            ;        
    }
 
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Clients::class,
        ));
    }
}