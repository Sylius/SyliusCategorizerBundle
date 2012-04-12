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
use Sylius\Bundle\CategorizerBundle\Registry\CatalogRegistry;
use Sylius\Bundle\CategorizerBundle\SyliusCategorizerBundle;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * Category choice form type.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
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
     * Category choice list.
     *
     * @var CategoryChoiceList
     */
    protected $categoryChoiceList;

    /**
     * Bundle driver.
     *
     * @var string
     */
    protected $driver;

    /**
     * Constructor.
     *
     * @param CatalogRegistry    $catalogRegistry
     * @param CategoryChoiceList $categoryChoiceList
     * @param string             $driver
     */
    public function __construct(CatalogRegistry $catalogRegistry, CategoryChoiceList $categoryChoiceList, $driver)
    {
        $this->catalogRegistry = $catalogRegistry;
        $this->categoryChoiceList = $categoryChoiceList;
        $this->driver = $driver;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        if (!isset($options['catalog'])) {
            throw new \InvalidArgumentException('Catalog must be defined in category choice type options.');
        }

        $this->categoryChoiceList->initializeCatalog($this->catalogRegistry->getCatalog($options['catalog']));

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
    public function getDefaultOptions()
    {
        return array(
            'catalog'     => null,
            'multiple'    => true,
            'expanded'    => false,
            'choice_list' => $this->categoryChoiceList,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(array $options)
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
