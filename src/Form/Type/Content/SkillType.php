<?php

/**
 * Form type
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Form\Type\Content;

use App\Entity\Skill;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

/**
 *  Form Type
 */
class SkillType extends AbstractType
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
                    'label' => 'admin.skill.form.name',
                ]
            ]
        ]);
        $builder->add('note', NumberType::class, ['label' => 'admin.skill.form.note']);
        $builder->add('type', ChoiceType::class, [
            'choices' => array_flip([
                Skill::SKILL_TYPE_SKILL => 'admin.skill.form.' . Skill::SKILL_TYPE_SKILL,
                Skill::SKILL_TYPE_LANGUAGE => 'admin.skill.form.' . Skill::SKILL_TYPE_LANGUAGE,
                Skill::SKILL_TYPE_TOOLS => 'admin.skill.form.' . Skill::SKILL_TYPE_TOOLS
            ]),
            'expanded' => false,
            'multiple' => false,
            'label' => 'admin.component.skill.label.type',
            'attr' => ['class' => 'select2']
        ]);
    }

    /**
     * Configure Options
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Skill::class,
            'translation_domain' => 'app'
        ]);
    }
}
