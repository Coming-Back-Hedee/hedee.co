<?php 

namespace App\Form;

use App\Entity\EligibiliteTest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormTypeInterface;

use Symfony\Component\Validator\Constraints\Regex;

class EligibiliteType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categorie', ChoiceType::class, [ 'choices' => [
                "Selectionnez votre categorie" => null,
                'Produits électroniques' => 'produits_electroniques',
                'Maisons et jardins' => 'maisons_et_jardins',
                'Jeux vidéos et jouets' => 'jvideos_et_jouets',
                'Santé et beauté' => 'sante_et_beaute',              
                'Auto et moto' => 'auto_et_moto',
                'Sports et mode' => 'sports_et_mode'
            ],
            'label' => 'Sélectionnez la catégorie du produit de votre produit'
            ])

            ->add('enseigne', TextType::class, ['label' => 'Renseignez le nom de l\'enseigne'])
            ->add('date_achat', TextType::class, ['label' => 'Sélectionnez la date d\'achat'])       
            ->add('prix', MoneyType::class, ['label' => 'Inscrivez le montant de l\'achat en euros', 'currency' => false])
            //->add('submit', SubmitType::class, ['label' => 'Demander le remboursement'])     
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EligibiliteTest::class,
        ]);
    }
}