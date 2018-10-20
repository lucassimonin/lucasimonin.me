<?php

/**
 * Form type
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Form\Type\User;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

/**
 *  Form Type
 */
class UserType extends AbstractType
{
    /**
     * Build Form
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class, [
            'label' => 'admin.user.form.username',
            'required' => true
        ]);
        if ($options['admin']) {
            $builder->add('roles', ChoiceType::class, [
                'choices' => [
                    'roles.super_admin' => 'ROLE_SUPER_ADMIN',
                    'roles.admin' => 'ROLE_ADMIN'
                ],
                'label' => 'admin.user.form.roles',
                'multiple' => true,
                'not_real_multiple' => true
            ]);
        }
        $builder->add('firstName', TextType::class, ['label' => 'admin.user.form.firstname']);
        $builder->add('lastName', TextType::class, ['label' => 'admin.user.form.lastname']);
        $builder->add('email', EmailType::class, ['label' => 'admin.user.form.email']);
        $builder->add('plainPassword', RepeatedType::class, array(
            'type' => PasswordType::class,
            'label' => 'admin.user.form.password',
            'options' => array('required' => false),
            'first_options'   =>  array('label' => 'admin.user.form.password'),
            'second_options'  =>  array('label' => 'admin.user.form.repeat_password'),
            'invalid_message' =>  'admin.user.form.password.error'
        ));
        $builder->add('enabled', CheckboxType::class, array('required' => false, 'label' => 'admin.common.activate'));
    }

    /**
     * Configure Options
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'translation_domain' => 'app',
            'admin' => false
        ));
    }
}
