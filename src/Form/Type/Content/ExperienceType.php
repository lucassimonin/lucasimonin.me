<?php

/**
 * Form type
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Form\Type\Content;

use App\Entity\Experience;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use A2lix\TranslationFormBundle\Form\Type\TranslationsType;

/**
 *  Form Type
 */
class ExperienceType extends AbstractType
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
                    'label' => 'admin.experience.form.name',
                ],
                'title' => [
                    'label' => 'admin.experience.form.title',
                ],
                'duration' => [
                    'label' => 'admin.experience.form.duration',
                ],
                'description' => [
                    'label' => 'admin.experience.form.description',
                    'field_type' => CKEditorType::class
                ]
            ]
        ]);

        $builder->add('type', ChoiceType::class, [
            'choices' => array_flip([
                Experience::EXPERIENCE_TYPE_WORK => 'admin.experience.form.' . Experience::EXPERIENCE_TYPE_WORK,
                Experience::EXPERIENCE_TYPE_EDUCATION => 'admin.experience.form.' . Experience::EXPERIENCE_TYPE_EDUCATION
            ]),
            'expanded' => false,
            'multiple' => false,
            'label' => 'admin.component.experience.label.type',
            'attr' => ['class' => 'select2']
        ]);
        $builder->add('startDate', DateType::class, [
            'widget' => 'single_text',
            'label' => 'admin.experience.form.start_date',
            'html5' => false,
            'required' => true,
            'format' => 'dd-MM-yyyy',
            'attr' => [
                'class' => 'date',
                'autocomplete' => 'off'
            ]
        ]);
        $builder->add('city', TextType::class, ['label' => 'admin.experience.form.city']);
        $builder->add('url', UrlType::class, ['label' => 'admin.experience.form.url']);
    }

    /**
     * Configure Options
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Experience::class,
            'translation_domain' => 'app'
        ]);
    }
}
