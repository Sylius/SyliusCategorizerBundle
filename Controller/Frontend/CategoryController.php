<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CatalogBundle\Controller\Frontend;

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
     */
    public function showAction($catalogAlias, $id, $slug)
    {
        $catalog = $this->container->get('sylius_catalog.provider')->getCatalog($catalogAlias);
        
        $categoryManager = $this->container->get('sylius_catalog.manager.category');
        
    	$category = $categoryManager->findCategoryBy($catalog, array('id' => $id, 'slug' => $slug));
    	
    	if (!$category) {
    	    throw new NotFoundHttpException('Requested category does not exist.');
    	}
    	
    	if ($catalog->getOption('sorter', false)) {
    	    $sorter = $this->container->get($catalog->getOption('sorter'));
    	} else { 
    	    $sorter = null;
    	}
    	
        $paginator = $categoryManager->createPaginator($catalog, $category, $sorter);
        
        $paginator->setCurrentPage($this->container->get('request')->query->get('page', 1), true, true);
        $items = $paginator->getCurrentPageResults();
        
        $property = $catalog->getOption('property');
    	
        return $this->container->get('templating')->renderResponse($catalog->getOption('templates.frontend.show'), array(
            'catalog'	   => $catalog,
        	'category'     => $category,
            $property      => $items,
            'paginator'    => $paginator,
        ));
    }
    
    /**
     * Renders all categories as list.
     */
    public function listAction($catalogAlias)
    {
        $catalog = $this->container->get('sylius_catalog.provider')->getCatalog($catalogAlias);
        
         if ($catalog->getOption('nested')) {
    	    $categories = $this->container->get('sylius_catalog.manager.category')->findCategories($catalog, true);
        } else {
            $categories = $this->container->get('sylius_catalog.manager.category')->findCategories($catalog);
        }
    	
        return $this->container->get('templating')->renderResponse($catalog->getOption('templates.frontend.list'), array(
            'catalog'	 => $catalog,
        	'categories' => $categories
        ));
    }
}
