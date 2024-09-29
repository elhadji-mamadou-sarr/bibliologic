<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\LigneCommande;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LigneCommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('designation')
            ->add('prix')
            ->add('premierPaiement', null, [
                'widget' => 'single_text',
            ])
            ->add('domaine')
            ->add('rubriques', ChoiceType::class, [
                'choices' => [
                    'Option 1' => 'option1',
                    'Option 2' => 'option2',
                    'Option 3' => 'option3',
                    'Option 4' => 'option4',
                ],
                'multiple' => true,
                'expanded' => false, // Si tu veux des cases à cocher, mets 'expanded' à true
            ])
            ->add('informationUtil', TextareaType::class,[
                'required' => false,
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LigneCommande::class,
        ]);
    }
}
