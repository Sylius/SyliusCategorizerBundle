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

        if ($options['multiple'] && in_array($this->driver, array('doctrine/orm', 'doctrine/mongodb-odm', 'doctrine/couchdb-odm'))) {
            $builder->prependClientTransformer(new CollectionToArrayTransformer());
        }
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'catalog'     => null,
            'multiple'    => true,
            'expanded'    => false,
            'choice_list' => $this->categoryChoiceList,
        );
    }

    public function getParent(array $options)
    {
        return 'choice';
    }

    public function getName()
    {
        return 'sylius_categorizer_category_choice';
    }
}
