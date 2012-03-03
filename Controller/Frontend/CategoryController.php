<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CategorizerBundle\Controller\Frontend;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Category frontend controller.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class CategoryController extends ContainerAware
{
    /**
     * Displays category.
     *
     * @param string  $alias The key to identify catalog
     * @param integer $id    Category id
     */
    public function showAction($alias, $id, $slug)
    {
        $catalog = $this->container->get('sylius_categorizer.registry')->getCatalog($alias);
        $category = $this->container->get('sylius_categorizer.manager.category')->findCategoryBy(array('id' => $id, 'slug' => $slug), $catalog);

        $property = $catalog->getOption('property');

        $parameters = array(
            'catalog'  => $catalog,
            'category' => $category
        );

        if ($catalog->getOption('pagination')) {
            $paginator = $this->container->get('sylius_categorizer.manager.category')->createPaginator($category);
            $paginator->setCurrentPage($this->container->get('request')->query->get('page', 1), true, true);
            $paginator->setMaxPerPage($catalog->getOption('pagination.mpp'));

            $parameters[$property] = $paginator->getCurrentPageResults();
            $parameters['paginator'] = $paginator;
        } else {
            $parameters[$property] = $category->{'get'.ucfirst($property)}();
        }

        return $this->container->get('templating')->renderResponse($catalog->getOption('templates.frontend.show'), $parameters);
    }

    /**
     * Display table of categories of specific catalog.
     *
     * @param string $alias The key to identify catalog
     */
    public function listAction($alias)
    {
        $catalog = $this->container->get('sylius_categorizer.registry')->getCatalog($alias);
        $categories = $this->container->get('sylius_categorizer.manager.category')->findCategories($catalog);

        return $this->container->get('templating')->renderResponse($catalog->getOption('templates.frontend.list'), array(
            'catalog'    => $catalog,
            'categories' => $categories
        ));
    }
}
