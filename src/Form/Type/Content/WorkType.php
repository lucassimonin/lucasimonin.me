<?php

/**
 * Form type
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Form\Type\Content;

use App\Entity\Work;
use App\Form\Type\Media\MediaType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use A2lix\TranslationFormBundle\Form\Type\TranslationsType;

/**
 *  Form Type
 */
class WorkType extends AbstractType
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
                'name' => [
                    'label' => 'admin.work.form.name',
                ],
                'description' => [
                    'label' => 'admin.work.form.description',
                    'field_type' => CKEditorType::class
                ]
            ]
        ]);
        $builder->add('tags', TextType::class, ['label' => 'admin.work.form.tags']);
        $builder->add('picture', MediaType::class, [
            'label' => false
        ]);
        $builder->add('url', UrlType::class, ['label' => 'admin.work.form.url']);
    }

    /**
     * Configure Options
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Work::class,
            'translation_domain' => 'app'
        ]);
    }
}
