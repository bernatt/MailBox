<?php

namespace UserListBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AddressType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('POST')
            ->add('city',TextType::class, ['label' => 'Miasto'])
            ->add('street',TextType::class, ['label' => 'Ulica'])
            ->add('homeNumber',NumberType::class, ['label' => 'Numer domu'])
            ->add('apartmentNumber',NumberType::class, ['label' => 'Numer mieszkania'])
            ->add('user' )
            ->add('save', SubmitType::class, ['label' => 'Dodaj adres']);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserListBundle\Entity\Address'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'userlistbundle_address';
    }


}
