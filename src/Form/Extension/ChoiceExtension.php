<?php
/**
 * Created by PhpStorm.
 * User: lsimonin
 * Date: 26/04/2018
 * Time: 17:12
 */
namespace App\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChoiceExtension extends AbstractTypeExtension
{
    public static function getExtendedTypes(): iterable
    {
        return array(ChoiceType::class);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['not_real_multiple'] = $options['not_real_multiple'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('not_real_multiple', false);
    }
}
