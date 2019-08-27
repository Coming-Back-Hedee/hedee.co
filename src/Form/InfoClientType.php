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
            ->setAction('informations-generales')
            ->add('prenom', TextType::class, ['label' => 'Votre prénom', 'required' => false])
            ->add('nom', TextType::class, ['label' => 'Votre nom', 'required' => false])
            ->add('numeroTelephone', TextType::class, ['label' => 'Votre numéro de téléphone', 'required' => false])
            ->add('dateNaissance', DateType::class, [
                'label' => 'Votre date de naissance',
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ],
                'required' => false,
                'years' => range(date('Y')-10, date('Y')-110)] )
            ->add('adresse',AdresseType::class, ['label' => 'Votre adresse', 'required' => false])           
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