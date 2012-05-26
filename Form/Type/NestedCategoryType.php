<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CategorizerBundle\Form\Type;

use Sylius\Bundle\CategorizerBundle\Form\EventListener\BuildNestedCategoryTypeListener;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Category form type.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class NestedCategoryType extends CategoryType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->addEventSubscriber(new BuildNestedCategoryTypeListener($builder->getFormFactory()))
            ->add('parent', 'sylius_categorizer_category_choice', array(
                'required' => false,
                'multiple' => false,
                'catalog'  => $options['catalog']
            ))
        ;
    }
}
