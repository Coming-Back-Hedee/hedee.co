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
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
 
class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $client = $event->getData();
            $form = $event->getForm();
            if ($client === null) {
                $form->add('email', EmailType::class, ['label' => 'Adresse email'] );
                $form->add('plainPassword', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'first_options'  => array('label' => 'Mot de passe'),
                    'second_options' => array('label' => 'Confirmer le mot de passe'),
                ));
                $form->add('dateNaissance', dateType::class, [
                    'label' => 'Date de naissance', 
                    'required' => false,
                    'years' => range(date('Y')-10, date('Y')-110) ] );
            }

        })
        
        ->add('nom', TextType::class, ['label' => 'Votre nom'] )
        ->add('prenom', TextType::class, ['label' => 'Votre prénom'])
        ->add('numeroTelephone', TelType::class, ['label' => 'Votre numéro de téléphone'])
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