<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CatalogBundle\Controller\Backend;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sylius\Bundle\CatalogBundle\EventDispatcher\Event\FilterCategoryEvent;
use Sylius\Bundle\CatalogBundle\EventDispatcher\SyliusCatalogEvents;

/**
 * Category backend controller.
 * 
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class CategoryController extends ContainerAware
{
    /**
     * Display table of categories.
     */
    public function listAction()
    {
    	$categories = $this->container->get('sylius_catalog.manager.category')->findCategories();
    	
        return $this->container->get('templating')->renderResponse('SyliusCatalogBundle:Backend/Category:list.html.' . $this->getEngine(), array(
        	'categories' => $categories
        ));
    }
    
    /**
     * Displays category.
     */
    public function showAction($id)
    {
        $categoryManager = $this->container->get('sylius_catalog.manager.category');
    	$category = $categoryManager->findCategoryBy(array('id' => $id));
    	
    	if (!$category) {
    	    throw new NotFoundHttpException('Requested category does not exist.');
    	}
    	
        $paginator = $categoryManager->createPaginator($category);
        $paginator->setCurrentPage($this->container->get('request')->query->get('page', 1), true, true);
        
        $items = $paginator->getCurrentPageResults();
    	
        return $this->container->get('templating')->renderResponse('SyliusCatalogBundle:Backend/Category:show.html.' . $this->getEngine(), array(
        	'category'  => $category,
            'items'     => $items,
            'paginator' => $paginator,
        ));
    }
    
    /**
     * Creating a category action.
     */
    public function createAction()
    {
        $category = $this->container->get('sylius_catalog.manager.category')->createCategory();

        $form = $this->container->get('form.factory')->create($this->container->get('sylius_catalog.form.type.category'));
        $form->setData($category);
        
        $request = $this->container->get('request');
        
        if ('POST' == $request->getMethod()) {
            $form->bindRequest($request);
        
            if ($form->isValid()) {
                $this->container->get('event_dispatcher')->dispatch(SyliusCatalogEvents::CATEGORY_CREATE, new FilterCategoryEvent($category));
                $this->container->get('sylius_catalog.manipulator.category')->create($category);
               
                return new RedirectResponse($this->container->get('router')->generate('sylius_catalog_backend_category_list'));
            }
        }
        
        return $this->container->get('templating')->renderResponse('SyliusCatalogBundle:Backend/Category:create.html.' . $this->getEngine(), array(
        	'form' => $form->createView()
        ));
    }
    
    /**
     * Updating a category.
     */
    public function updateAction($id)
    {
        $category = $this->container->get('sylius_catalog.manager.category')->findCategory($id);
        
        if (!$category) {
            throw new NotFoundHttpException('Requested category does not exist.');
        }
        
        $form = $this->container->get('form.factory')->create($this->container->get('sylius_catalog.form.type.category'));        
        $form->setData($category);
        
        $request = $this->container->get('request');
        
        if ('POST' == $request->getMethod()) {
            $form->bindRequest($request);
            
            if ($form->isValid()) {
                $this->container->get('event_dispatcher')->dispatch(SyliusCatalogEvents::CATEGORY_UPDATE, new FilterCategoryEvent($category));
                $this->container->get('sylius_catalog.manipulator.category')->update($category);
                
                return new RedirectResponse($this->container->get('router')->generate('sylius_catalog_backend_category_list'));
            }
        }
        
        return $this->container->get('templating')->renderResponse('SyliusCatalogBundle:Backend/Category:update.html.' . $this->getEngine(), array(
        	'form' => $form->createView(),
            'category' => $category
        ));
    }
    
	/**
     * Deletes category.
     */
    public function deleteAction($id)
    {
        $category = $this->container->get('sylius_catalog.manager.category')->findCategory($id);
        
        if (!$category) {
            throw new NotFoundHttpException('Requested category does not exist.');
        }
        
        $this->container->get('event_dispatcher')->dispatch(SyliusCatalogEvents::CATEGORY_DELETE, new FilterCategoryEvent($category));
        $this->container->get('sylius_catalog.manipulator.category')->delete($category);
        
        return new RedirectResponse($this->container->get('router')->generate('sylius_catalog_backend_category_list'));
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
