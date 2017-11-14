<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 30.10.2017
 * Time: 14:08
 */

namespace Intex\OrgBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('firstname','text', array('label' => 'Last Name',));
        $builder->add('lastname','text', array('label' => 'First Name',));
        $builder->add('middlename','text', array('label' => 'Middle Name',));
        $builder->add('bithday', DateType::class, array('label' => 'Bithday(YYYY-MM-DD)',
            'widget' => 'single_text','format' => 'yyyy-MM-dd',));
        $builder->add('inn','text',array('label' => 'ITN (12 digits)',));
        $builder->add('snils', 'text',array('label' => 'INILA (11 digits)',));
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
        return 'intex_orgbundle_usertype';
    }
}
