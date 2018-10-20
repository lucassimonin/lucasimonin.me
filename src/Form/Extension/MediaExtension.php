<?php

namespace App\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaExtension extends AbstractTypeExtension
{
    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        // use FormType::class to modify (nearly) every field in the system
        return FileType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('image_input', false);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['image_input'] = $options['image_input'];
    }
}
