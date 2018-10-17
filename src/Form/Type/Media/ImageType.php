<?php

namespace App\Form\Type\Media;

use App\Entity\Media;
use App\Form\Transformer\MediaTransformer;
use App\Form\Type\BaseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ImageType extends BaseType
{
    protected $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', TextType::class, ['required' => false, 'label' => 'admin.media.id.label'])
                ->addModelTransformer(new MediaTransformer($this->em));
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
            'by_reference' => false,
            'translation_domain' => 'app'
        ));
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'media';
    }
}
