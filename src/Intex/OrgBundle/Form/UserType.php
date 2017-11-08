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
        $builder->add('firstname','text', array('label' => 'Фамилия',));
        $builder->add('lastname','text', array('label' => 'Имя',));
        $builder->add('middlename','text', array('label' => 'Отчество',));
        $builder->add('bithday', DateType::class, array('label' => 'День рождения(формат:ГГГГ-ММ-ДД)',
            'widget' => 'single_text','format' => 'yyyy-MM-dd',));
        $builder->add('inn','text',array('label' => 'ИНН(12 цифр)',));
        $builder->add('snils', 'text',array('label' => 'ИНН(11 цифр)',));
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
