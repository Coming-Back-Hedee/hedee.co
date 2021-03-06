<?php
namespace App\Form;
 
use App\Entity\Clients;
use App\Entity\Adresses;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
 
class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse email'
            ])
            //->add('codeParrainage', TextType::class, ['data_class' => null, 'mapped' => false, 'label' => 'Code parrainage', 'required' => false]) 
            ->add('submit', SubmitType::class, ['label' => 'Valider']) 
            ;        
            if (in_array('registration', $options['validation_groups'])) {
                $builder
                    ->add('plainPassword', RepeatedType::class, array(
                        'type' => PasswordType::class,
                        'first_options'  => array('label' => 'Mot de passe'),
                        'second_options' => array('label' => 'Confirmer le mot de passe'),
                    ))
                    ;
            } else {
                $builder
                    ->add('plainPassword', RepeatedType::class, array(
                        'required' => false,
                        'type' => PasswordType::class,
                        'first_options'  => array('label' => 'Mot de passe'),
                        'second_options' => array('label' => 'Confirmer le mot de passe'),
                    ))
                    ;
            }
    }
 
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Clients::class,
        ));
    }
}