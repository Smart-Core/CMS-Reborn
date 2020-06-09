<?php

declare(strict_types=1);

namespace SmartCore\CMSBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use SmartCore\RadBundle\Command\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TechCommand extends AbstractCommand
{
    protected static $defaultName = 'cms:tech';

    protected function configure(): void
    {
        $this
            ->setDescription('Для технических целей.')
        ;
    }

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return 0;
    }
}
