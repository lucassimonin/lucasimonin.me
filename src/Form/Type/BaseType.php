<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class BaseType extends AbstractType
{
    /**
     * Build Form
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('metaTitle', TextType::class);
        $builder->add('metaDescription', TextareaType::class);
        $builder->add('metaKeywords', TextType::class);
        $builder->add('ogTitle', TextType::class);
        $builder->add('ogDescription', TextareaType::class);
        $builder->add('ogImage', TextType::class);
    }
}
