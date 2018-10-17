<?php

/**
 * Form type
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Form\Type\Content;

use App\Entity\Person;
use App\Form\Type\Media\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use A2lix\TranslationFormBundle\Form\Type\TranslationsType;

/**
 *  Form Type
 */
class PersonType extends AbstractType
{
    /**
     * Build Form
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('translations', TranslationsType::class, [
            'label' => false,
            'fields' => [
                'metaTitle' => [
                    'label' => 'admin.user.form.meta_title',
                ],
                'metaDescription' => [
                    'label' => 'admin.user.form.meta_description',
                ],
                'work' => [
                    'label' => 'admin.person.form.work',
                ],
                'degree' => [
                    'label' => 'admin.person.form.degree',
                ],
                'biography' => [
                    'label' => 'admin.person.form.biography',
                    'attr' => ['class' => 'editor-wysiwyg'],
                    'field_type' => TextareaType::class
                ]
            ]
        ]);
        $builder->add('firstName', TextType::class, ['label' => 'admin.user.form.firstname']);
        $builder->add('lastName', TextType::class, ['label' => 'admin.user.form.lastname']);
        $builder->add('picture', ImageType::class, [
            'label' => 'admin.person.form.picture',
            'image_input' => true
        ]);
        $builder->add('city', TextType::class, ['label' => 'admin.person.form.city']);
        $builder->add('cityUrl', UrlType::class, ['label' => 'admin.person.form.city_url']);
        $builder->add('birthday', DateType::class, [
            'widget' => 'single_text',
            'label' => 'admin.person.form.birthday',
            'html5' => false,
            'required' => true,
            'format' => 'dd-MM-yyyy',
            'attr' => [
                'class' => 'date',
                'autocomplete' => 'off'
            ]
        ]);
        $builder->add('twitter', UrlType::class, ['label' => 'admin.person.form.twitter']);
        $builder->add('linkedin', UrlType::class, ['label' => 'admin.person.form.linkedin']);
        $builder->add('github', UrlType::class, ['label' => 'admin.person.form.github']);
        $builder->add('instagram', TextType::class, ['label' => 'admin.person.form.instagram']);
        $builder->add('mail', EmailType::class, ['label' => 'admin.person.form.mail']);
    }

    /**
     * Configure Options
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Person::class,
            'translation_domain' => 'app'
        ));
    }
}
