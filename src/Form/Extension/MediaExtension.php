<?php

namespace App\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaExtension extends AbstractTypeExtension
{
    public static function getExtendedTypes(): iterable
    {
        return array(FileType::class);
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
