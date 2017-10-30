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

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstname');
        $builder->add('lastname');
        $builder->add('patronymic');
        $builder->add('bithday');
        $builder->add('inn');
        $builder->add('snils');
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
