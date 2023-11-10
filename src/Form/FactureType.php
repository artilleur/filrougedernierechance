<?php

namespace App\Form;

use App\Entity\Facture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $user = $options['user'];
        $builder
            ->add('client')
            ->add('adresse_livraison')
            ->add('code_postal')
            ->add('ville')
            ->add('email')
            ->add('telephone')
            ->add('prduit')
            ->add('prix')
            ->add('quantite')

            ->add('adresse', EntityType::class, [
                'class' => Adresse::class,
                'label' => 'Adresse de Livraison',
                'required' => true,
                'multiple' => false,
                'choices' => $user->getAdresses(),
                'expanded' => true
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}
