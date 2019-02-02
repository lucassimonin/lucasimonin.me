<?php

namespace App\Form\Type\Media;

use App\Entity\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file', FileType::class, ['required' => true, 'label' => 'admin.media.form.file', 'image_input' => true]);
        $builder->add('alt', TextType::class, ['required' => false, 'label' => 'admin.media.form.alt']);
    }

    /**
     * Configure Options
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Media::class
        ]);
    }
}
