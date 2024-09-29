<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Livre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')

            ->add('auteur', TextType::class,[
                'required'  => false,
            ])
            ->add('genre', TextType::class,[
                'required'  => false,
            ])
            ->add('langue', TextType::class,[
                'required'  => false,
            ])
            ->add('categorie', EntityType::class,[
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'required'   => false,
            ])
            ->add('date_publication', null, [
                'widget' => 'single_text',
            ])
            ->add('nombre_pages', TextType::class,[
                'required'  => false,
            ])

            ->add('projet', CheckboxType::class,[
                'required' => false,
            ])
            ->add('localisation', TextType::class,[
                'required'  => false,
            ])
            ->add('etat', TextType::class,[
                'required'  => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
