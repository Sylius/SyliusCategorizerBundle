<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CategorizerBundle\Controller\Backend;

use Sylius\Bundle\CategorizerBundle\EventDispatcher\Event\FilterCategoryEvent;
use Sylius\Bundle\CategorizerBundle\EventDispatcher\SyliusCategorizerEvents;
use Sylius\Bundle\CategorizerBundle\Registry\CatalogInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Category backend controller.
 * Manages all backend actions related to categories.
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
     *
     * @return Response
     */
    public function showAction($alias, $id)
    {
        $catalog = $this->container->get('sylius_categorizer.registry')->getCatalog($alias);
        $category = $this->findCategoryOr404($catalog, $id);

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

        return $this->container->get('templating')->renderResponse(sprintf($catalog->getOption('templates.backend'), 'show'), $parameters);
    }

    /**
     * Displays list of categories from specific catalog.
     *
     * @param string $alias The key to identify catalog
     *
     * @return Response
     */
    public function listAction($alias)
    {
        $catalog = $this->container->get('sylius_categorizer.registry')->getCatalog($alias);
        $categories = $this->container->get('sylius_categorizer.manager.category')->findCategories($catalog);

        return $this->container->get('templating')->renderResponse(sprintf($catalog->getOption('templates.backend'), 'list'), array(
            'catalog'    => $catalog,
            'categories' => $categories
        ));
    }

    /**
     * Creates new category in given catalog.
     *
     * @param Request $request
     * @param string  $alias The key to identify catalog
     *
     * @return Response
     */
    public function createAction(Request $request, $alias)
    {
        $catalog = $this->container->get('sylius_categorizer.registry')->getCatalog($alias);
        $category = $this->container->get('sylius_categorizer.manager.category')->createCategory($catalog);

        $form = $this->container->get('form.factory')->create($catalog->getOption('form'), $category, array('catalog' => $alias));

        if ('POST' == $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $this->container->get('event_dispatcher')->dispatch(SyliusCategorizerEvents::CATEGORY_CREATE, new FilterCategoryEvent($category, $catalog));
                $this->container->get('sylius_categorizer.manipulator.category')->create($category);

                return new RedirectResponse($this->container->get('router')->generate('sylius_categorizer_backend_category_list', array(
                    'alias' => $catalog->getAlias()
                )));
            }
        }

        return $this->container->get('templating')->renderResponse(sprintf($catalog->getOption('templates.backend'), 'create'), array(
            'catalog' => $catalog,
            'form'    => $form->createView()
        ));
    }

    /**
     * Updates a category from given catalog.
     *
     * @param Request $request
     * @param string  $alias   The key to identify catalog
     * @param integer $id      Category id
     */
    public function updateAction(Request $request, $alias, $id)
    {
        $catalog = $this->container->get('sylius_categorizer.registry')->getCatalog($alias);
        $category = $this->findCategoryOr404($catalog, $id);

        $form = $this->container->get('form.factory')->create($catalog->getOption('form'), $category, array('catalog' => $alias));

        if ('POST' == $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $this->container->get('event_dispatcher')->dispatch(SyliusCategorizerEvents::CATEGORY_UPDATE, new FilterCategoryEvent($category, $catalog));
                $this->container->get('sylius_categorizer.manipulator.category')->update($category);

                return new RedirectResponse($this->container->get('router')->generate('sylius_categorizer_backend_category_list', array(
                    'alias' => $catalog->getAlias()
                )));
            }
        }

        return $this->container->get('templating')->renderResponse(sprintf($catalog->getOption('templates.backend'), 'update'), array(
            'catalog'  => $catalog,
            'category' => $category,
            'form'     => $form->createView()
        ));
    }

    /**
     * Deletes category.
     *
     * @param string  $alias
     * @param integer $id
     *
     * @return RedirectResponse
     */
    public function deleteAction($alias, $id)
    {
        $catalog = $this->container->get('sylius_categorizer.registry')->getCatalog($alias);
        $category = $this->findCategoryOr404($catalog, $id);

        $this->container->get('event_dispatcher')->dispatch(SyliusCategorizerEvents::CATEGORY_DELETE, new FilterCategoryEvent($category, $catalog));
        $this->container->get('sylius_categorizer.manipulator.category')->delete($category);

        return new RedirectResponse($this->container->get('router')->generate('sylius_categorizer_backend_category_list', array(
            'alias' => $catalog->getAlias()
        )));
    }

    /**
     * Moves up category.
     *
     * @param string  $alias The key to identify catalog
     * @param integer $id    Category id
     *
     * @return RedirectResponse
     */
    public function moveUpAction($alias, $id)
    {
        $catalog = $this->container->get('sylius_categorizer.registry')->getCatalog($alias);
        $category = $this->findCategoryOr404($catalog, $id);

        $this->container->get('event_dispatcher')->dispatch(SyliusCategorizerEvents::CATEGORY_MOVE_UP, new FilterCategoryEvent($category, $catalog));
        $this->container->get('sylius_categorizer.manipulator.category')->moveUp($category);

        return new RedirectResponse($this->container->get('router')->generate('sylius_categorizer_backend_category_list', array(
            'alias' => $catalog->getAlias()
        )));
    }

    /**
     * Moves down category.
     *
     * @param string  $alias The key to identify catalog
     * @param integer $id    Category id
     *
     * @return RedirectResponse
     */
    public function moveDownAction($alias, $id)
    {
        $catalog = $this->container->get('sylius_categorizer.registry')->getCatalog($alias);
        $category = $this->findCategoryOr404($catalog, $id);

        $this->container->get('event_dispatcher')->dispatch(SyliusCategorizerEvents::CATEGORY_MOVE_DOWN, new FilterCategoryEvent($category, $catalog));
        $this->container->get('sylius_categorizer.manipulator.category')->moveDown($category);

        return new RedirectResponse($this->container->get('router')->generate('sylius_categorizer_backend_category_list', array(
            'alias' => $catalog->getAlias()
        )));
    }

    /**
     * Looks for category and throws 404 http exception
     * when unsuccessful.
     *
     * @param CatalogInterface $catalog The catalog
     * @param integer          $id      Category id
     *
     * @return CategoryInterface
     *
     * @throws NotFoundHttpException
     */
    protected function findCategoryOr404(CatalogInterface $catalog, $id)
    {
        if (!$category = $this->container->get('sylius_categorizer.manager.category')->findCategory($id, $catalog)) {
            throw new NotFoundHttpException('Requested category does not exist.');
        }

        return $category;
    }
}
