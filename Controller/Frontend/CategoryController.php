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
    public function showAction($id, $slug)
    {
        $categoryManager = $this->container->get('sylius_catalog.manager.category');
    	$category = $categoryManager->findCategoryBy(array('id' => $id, 'slug' => $slug));
    	
    	if (!$category) {
    	    throw new NotFoundHttpException('Requested category does not exist.');
    	}
    	
        $paginator = $categoryManager->createPaginator($category);
        
        $paginator->setCurrentPage($this->container->get('request')->query->get('page', 1), true, true);
        $items = $paginator->getCurrentPageResults();
    	
        return $this->container->get('templating')->renderResponse('SyliusCatalogBundle:Frontend/Category:show.html.' . $this->getEngine(), array(
        	'category'  => $category,
            'items'     => $items,
            'paginator' => $paginator,
        ));
    }
    
    /**
     * Renders all categories as list.
     */
    public function listAction()
    {       
    	$categories = $this->container->get('sylius_catalog.manager.category')->findCategories();
    	
        return $this->container->get('templating')->renderResponse('SyliusCatalogBundle:Frontend/Category:list.html.' . $this->getEngine(), array(
        	'categories' => $categories
        ));
    }
    
    /**
     * Returns templating engine name.
     * 
     * @return string
     */
    protected function getEngine()
    {
        return $this->container->getParameter('sylius_catalog.engine');
    }
}
