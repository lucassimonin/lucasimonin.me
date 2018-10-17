<?php

namespace App\Form\Type\Media;

use App\Entity\Media;
use App\Form\Type\BaseType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MediaType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, ['required' => false, 'label' => 'Titre']);
        $builder->add('alt', TextType::class, ['required' => false, 'label' => 'Alt']);
    }

    /**
     * Configure Options
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Media::class,
            'csrf_protection' => false,
        ));
    }
}
