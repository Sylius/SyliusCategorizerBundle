<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CatalogBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;

/**
 * Command for console that deletes category.
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
            ->setName('sylius:catalog:category:delete')
            ->setDescription('Deletes a category.')
            ->setDefinition(array(
                new InputArgument('id', InputArgument::REQUIRED, 'The category id'),
            ))
            ->setHelp(<<<EOT
The <info>sylius:assortment:category:delete</info> command deletes a category:

  <info>php sylius/console sylius:catalog:category:delete 24</info>
EOT
            );
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $category = $this->getContainer()->get('sylius_catalog.manager.category')->findCategory($input->getArgument('id'));
        
        if (!$category) {
            throw new \InvalidArgumentException(sprintf('The category with id "%s" does not exist.', $input->getArgument('id')));
        }
        
        $this->getContainer()->get('sylius_catalog.manipulator.category')->delete($category);

        $output->writeln(sprintf('Deleted category with id: <comment>%s</comment>', $input->getArgument('id')));
    }

    /**
     * @see Command
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('id')) {
            $id = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please insert category id: ',
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
