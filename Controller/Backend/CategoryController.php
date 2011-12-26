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
     * Display table of categories of specific catalog.
     * 
     * @param string $catalog
     */
    public function listAction($catalogAlias)
    {
        $catalog = $this->container->get('sylius_catalog.provider')->getCatalog($catalogAlias);
        
        if ($catalog->getOption('nested')) {
    	    $categories = $this->container->get('sylius_catalog.manager.category')->findCategories($catalog, true);
        } else {
            $categories = $this->container->get('sylius_catalog.manager.category')->findCategories($catalog);
        }
    	
        return $this->container->get('templating')->renderResponse($catalog->getOption('templates.backend.list'), array(
            'catalog' => $catalog,
        	'categories' => $categories
        ));
    }
    
    /**
     * Displays category.
     */
    public function showAction($catalogAlias, $id)
    {
        $catalog = $this->container->get('sylius_catalog.provider')->getCatalog($catalogAlias);
        
        $categoryManager = $this->container->get('sylius_catalog.manager.category');
        
    	$category = $categoryManager->findCategory($catalog, $id);
    	
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
        
        return $this->container->get('templating')->renderResponse($catalog->getOption('templates.backend.show'), array(
            'catalog'	=> $catalog,
        	'category'  => $category,
            $property     => $items,
            'paginator' => $paginator,
        ));
    }
    
    /**
     * Creating a category action.
     */
    public function createAction($catalogAlias)
    {
        $catalog = $this->container->get('sylius_catalog.provider')->getCatalog($catalogAlias);
        
        $category = $this->container->get('sylius_catalog.manager.category')->createCategory($catalog);

        $form = $this->container->get('sylius_catalog.form.factory.category')->create($catalog);
        $form->setData($category);
        
        $request = $this->container->get('request');
        
        if ('POST' == $request->getMethod()) {
            $form->bindRequest($request);
        
            if ($form->isValid()) {
                $this->container->get('event_dispatcher')->dispatch(SyliusCatalogEvents::CATEGORY_CREATE, new FilterCategoryEvent($category));
                $this->container->get('sylius_catalog.manipulator.category')->create($category);
               
                return new RedirectResponse($this->container->get('router')->generate('sylius_catalog_backend_category_list', array('catalogAlias' => $catalog->getAlias())));
            }
        }

        return $this->container->get('templating')->renderResponse($catalog->getOption('templates.backend.create'), array(
            'catalog' => $catalog,
        	'form' => $form->createView()
        ));
    }
    
    /**
     * Updating a category.
     */
    public function updateAction($catalogAlias, $id)
    {
        $catalog = $this->container->get('sylius_catalog.provider')->getCatalog($catalogAlias);
        
        $category = $this->container->get('sylius_catalog.manager.category')->findCategory($catalog, $id);
        
        if (!$category) {
            throw new NotFoundHttpException('Requested category does not exist.');
        }
        
        $form = $this->container->get('sylius_catalog.form.factory.category')->create($catalog);      
        $form->setData($category);
        
        $request = $this->container->get('request');
        
        if ('POST' == $request->getMethod()) {
            $form->bindRequest($request);
            
            if ($form->isValid()) {
                $this->container->get('event_dispatcher')->dispatch(SyliusCatalogEvents::CATEGORY_UPDATE, new FilterCategoryEvent($category));
                $this->container->get('sylius_catalog.manipulator.category')->update($category);
                
                return new RedirectResponse($this->container->get('router')->generate('sylius_catalog_backend_category_list', array(
                    'catalogAlias' => $catalog->getAlias()
                )));
            }
        }
        
        return $this->container->get('templating')->renderResponse($catalog->getOption('templates.backend.update'), array(
            'catalog' => $catalog,
        	'form' => $form->createView(),
            'category' => $category
        ));
    }
    
	/**
     * Deletes category.
     */
    public function deleteAction($catalogAlias, $id)
    {
        $catalog = $this->container->get('sylius_catalog.provider')->getCatalog($catalogAlias);
        
        $category = $this->container->get('sylius_catalog.manager.category')->findCategory($catalog, $id);
        
        if (!$category) {
            throw new NotFoundHttpException('Requested category does not exist.');
        }
        
        $this->container->get('event_dispatcher')->dispatch(SyliusCatalogEvents::CATEGORY_DELETE, new FilterCategoryEvent($category));
        $this->container->get('sylius_catalog.manipulator.category')->delete($category);
        
        return new RedirectResponse($this->container->get('router')->generate('sylius_catalog_backend_category_list', array(
            'catalogAlias' => $catalog->getAlias()
        )));
    }
    
    public function moveUpAction($catalogAlias, $id)
    {
        $catalog = $this->container->get('sylius_catalog.provider')->getCatalog($catalogAlias);
        
        $category = $this->container->get('sylius_catalog.manager.category')->findCategory($catalog, $id);
        
        if (!$category) {
            throw new NotFoundHttpException('Requested category does not exist.');
        }
        
        $this->container->get('event_dispatcher')->dispatch(SyliusCatalogEvents::CATEGORY_MOVE_UP, new FilterCategoryEvent($category));
        $this->container->get('sylius_catalog.manipulator.category')->moveUp($catalog, $category);
        
        return new RedirectResponse($this->container->get('router')->generate('sylius_catalog_backend_category_list', array(
                    'catalogAlias' => $catalog->getAlias()
        )));
    }
    
    public function moveDownAction($catalogAlias, $id)
    {
        $catalog = $this->container->get('sylius_catalog.provider')->getCatalog($catalogAlias);
        
        $category = $this->container->get('sylius_catalog.manager.category')->findCategory($catalog, $id);
        
        if (!$category) {
            throw new NotFoundHttpException('Requested category does not exist.');
        }
        
        $this->container->get('event_dispatcher')->dispatch(SyliusCatalogEvents::CATEGORY_MOVE_DOWN, new FilterCategoryEvent($category));
        $this->container->get('sylius_catalog.manipulator.category')->moveDown($catalog, $category);
        
        return new RedirectResponse($this->container->get('router')->generate('sylius_catalog_backend_category_list', array(
                    'catalogAlias' => $catalog->getAlias()
        )));
    }
}
