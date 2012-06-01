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

use Sylius\Bundle\CategorizerBundle\Form\ChoiceList\CategoryChoiceList;
use Sylius\Bundle\CategorizerBundle\Model\CategoryManagerInterface;
use Sylius\Bundle\CategorizerBundle\Registry\CatalogRegistry;
use Sylius\Bundle\CategorizerBundle\SyliusCategorizerBundle;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Category choice form type.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
class CategoryChoiceType extends AbstractType
{
    /**
     * Catalog provider.
     *
     * @var CatalogRegistry
     */
    protected $catalogRegistry;

    /**
     * Category manager.
     *
     * @var CategoryManagerInterface
     */
    protected $categoryManager;

    /**
     * Bundle driver.
     *
     * @var string
     */
    protected $driver;

    /**
     * Constructor.
     *
     * @param CatalogRegistry          $catalogRegistry
     * @param CategoryManagerInterface $categoryManager
     * @param string                   $driver
     */
    public function __construct(CatalogRegistry $catalogRegistry, CategoryManagerInterface $categoryManager, $driver)
    {
        $this->catalogRegistry = $catalogRegistry;
        $this->categoryManager = $categoryManager;
        $this->driver = $driver;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $doctrineBasedDrivers = array(
            SyliusCategorizerBundle::DRIVER_DOCTRINE_ORM,
            SyliusCategorizerBundle::DRIVER_DOCTRINE_MONGODB_ODM,
            SyliusCategorizerBundle::DRIVER_DOCTRINE_COUCHDB_ODM
        );

        if ($options['multiple'] && in_array($this->driver, $doctrineBasedDrivers)) {
            $builder->prependClientTransformer(new CollectionToArrayTransformer());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $categoryManager = $this->categoryManager;
        $choiceList = function (Options $options) use ($categoryManager) {
            return new CategoryChoiceList($categoryManager, $options['catalog']);
        };

        $resolver
           ->setDefaults(array(
                'multiple'    => true,
                'expanded'    => false,
                'choice_list' => $choiceList,
            ))
            ->setRequired(array(
                'catalog'
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_categorizer_category_choice';
    }
}
