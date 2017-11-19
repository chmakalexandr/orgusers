<?php

namespace Intex\OrgBundle\Form;

use Symfony\Component\Form\AbstractType;
use Intex\OrgBundle\Form\UserType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ExtendUserType extends UserType
{
    /**
     * {@inheritdoc}
     */

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        parent::buildForm($builder,$options);
        $builder->add('company', 'entity', array(
            'class' => 'Intex\OrgBundle\Entity\Company',
            'property' => 'name',
            'placeholder' => 'Choose organization',
            'required' => true
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Intex\OrgBundle\Entity\User'
        ));
    }

    public function getBlockPrefix()
    {
        return 'intex_orgbundle_newusertype';
    }
}
