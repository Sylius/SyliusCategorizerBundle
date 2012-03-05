<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CategorizerBundle\Command;

use Sylius\Bundle\CategorizerBundle\EventDispatcher\Event\FilterCategoryEvent;
use Sylius\Bundle\CategorizerBundle\EventDispatcher\SyliusCategorizerEvents;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;

/**
 * Command for console that deletes category.
 * Takes catalog alias and category id as arguments.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class DeleteCategoryCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('sylius:categorizer:category:delete')
            ->setDescription('Deletes a category.')
            ->setDefinition(array(
                new InputArgument('alias', InputArgument::REQUIRED, 'The catalog alias'),
                new InputArgument('id', InputArgument::REQUIRED, 'The category id'),
            ))
            ->setHelp(
<<<EOT
The <info>sylius:categorizer:category:delete</info> command deletes a category:

  <info>php sylius/console sylius:categorizer:category:delete blog 24</info>
EOT
            )
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $catalog = $this->getContainer()->get('sylius_categorizer.registry')->getCatalog($input->getArgument('alias'));
        $category = $this->getContainer()->get('sylius_categorizer.manager.category')->findCategory($input->getArgument('id'), $catalog);

        if (!$category) {
            throw new \InvalidArgumentException(sprintf(
                'The category with id "%s" does not exist in catalog with alias "%s".',
                $input->getArgument('id'),
                $input->getArgument('alias')
            ));
        }

        $this->getContainer()->get('event_dispatcher')->dispatch(SyliusCategorizerEvents::CATEGORY_DELETE, new FilterCategoryEvent($category, $catalog));
        $this->getContainer()->get('sylius_categorizer.manipulator.category')->delete($category);

        $output->writeln(sprintf(
            '<info>[Sylius]</info> Deleted category with id <comment>%s</comment> from catalog with alias <comment>%s</comment>.',
            $input->getArgument('id'),
            $input->getArgument('alias')
        ));
    }

    /**
     * @see Command
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('alias')) {
            $alias = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please insert catalog alias... ',
                function($alias = null)
                {
                    if (empty($alias)) {
                        throw new \Exception('Catalog alias must be specified.');
                    }
                    return $alias;
                }
            );
            $input->setArgument('alias', $alias);
        }
        if (!$input->getArgument('id')) {
            $id = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please insert category id... ',
                function($id = null)
                {
                    if (empty($id)) {
                        throw new \Exception('Category id must be specified.');
                    }
                    if (!is_numeric($id)) {
                        throw new \Exception('Category id must be numeric.');
                    }
                    return $id;
                }
            );
            $input->setArgument('id', $id);
        }
    }
}
