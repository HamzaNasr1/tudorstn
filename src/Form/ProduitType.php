<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType as TypeFileType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('quantite')
            ->add('description')
            ->add('fournisseur')
            ->add('photo', TypeFileType::class, [
                'label' => 'Photo',
                'required' => false, // Si la photo n'est pas requise
                'mapped' => false, // N'est pas mappé directement sur l'entité
                'attr' => ['accept' => 'image/*'] // Accepte tous les types d'images
            ])     
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
    
}
