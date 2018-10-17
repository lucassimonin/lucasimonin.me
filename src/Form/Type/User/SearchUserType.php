<?php

/**
 * Form type
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Form\Type\User;

use App\Form\Model\SearchUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *  Form Type
 *
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 */
class SearchUserType extends AbstractType
{
    /**
     * Build Form
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', TextType::class, array('required' => false));
        $builder->add('firstName', TextType::class, array('required' => false));
        $builder->add('lastName', TextType::class, array('required' => false));
        $builder->add('email', TextType::class, array('required' => false));
    }

    /**
     * Configure Options
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => SearchUser::class,
            'translation_domain' => 'app'
        ));
    }
}