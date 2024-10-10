<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType; // Import TextType for the 'Nom' field
use Symfony\Component\Form\Extension\Core\Type\NumberType; 

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [ // Use lowercase for 'nom'
            'label' => 'Nom',
        ])
        ->add('prix', NumberType::class, [ // Use NumberType for price
            'label' => 'Prix',
            'scale' => 2, // Set scale to 2 for two decimal places
            'html5' => true, // Use HTML5 input type for number
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
