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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @param string  $id    Category slug
     *
     * @return Response
     */
    public function showAction($alias, $slug)
    {
        $catalog = $this->container->get('sylius_categorizer.registry')->getCatalog($alias);
        $category = $this->container->get('sylius_categorizer.manager.category')->findCategoryBy(array('slug' => $slug), $catalog);

        $property = $catalog->getOption('property');

        $parameters = array(
            'catalog'  => $catalog,
            'category' => $category
        );

        if ($catalog->getOption('pagination')) {
            $paginator = $this->container->get('sylius_categorizer.loader.category')->loadCategory($category);
            $paginator->setCurrentPage($this->container->get('request')->query->get('page', 1), true, true);
            $paginator->setMaxPerPage($catalog->getOption('pagination.mpp'));

            $parameters[$property] = $paginator->getCurrentPageResults();
            $parameters['paginator'] = $paginator;
        } else {
            $parameters[$property] = $category->{'get'.ucfirst($property)}();
        }

        return $this->container->get('templating')->renderResponse(sprintf($catalog->getOption('templates.frontend'), 'show'), $parameters);
    }

    /**
     * Display table of categories of specific catalog.
     *
     * @param string $alias The key to identify catalog
     *
     * @return Response
     */
    public function listAction($alias)
    {
        $catalog = $this->container->get('sylius_categorizer.registry')->getCatalog($alias);
        $categories = $this->container->get('sylius_categorizer.manager.category')->findCategories($catalog);

        return $this->container->get('templating')->renderResponse(sprintf($catalog->getOption('templates.frontend'), 'list'), array(
            'catalog'    => $catalog,
            'categories' => $categories
        ));
    }
}
