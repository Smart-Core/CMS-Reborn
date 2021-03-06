<?php

declare(strict_types=1);

namespace SmartCore\Bundle\MediaBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use SmartCore\Bundle\MediaBundle\Entity\FileTransformed;
use SmartCore\Bundle\MediaBundle\Service\MediaCloudService;
use SmartCore\RadBundle\Command\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FiltersPurgeCommand extends AbstractCommand
{
    protected static $defaultName = 'smart:media:filters:purge';

    protected $em;
    protected $mcs;

    public function __construct(EntityManagerInterface $em, MediaCloudService $mcs)
    {
        parent::__construct();

        $this->em = $em;
        $this->mcs = $mcs;
    }

    protected function configure()
    {
        $this
            ->setDescription('Purge all filtered images.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<comment>Truncate FileTransformed Table...</comment>');

        $cmd = $this->em->getClassMetadata(FileTransformed::class);

        $connection = $this->em->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $connection->query('SET FOREIGN_KEY_CHECKS=0');

        $q = $dbPlatform->getTruncateTableSql($cmd->getTableName());
        $connection->executeUpdate($q);
        $connection->query('SET FOREIGN_KEY_CHECKS=1');

        $output->writeln('<comment>Remove files...</comment>');

        foreach ($this->mcs->getCollections() as $collection) {
            $collection->purgeTransformedFiles();
        }

        return 0;
    }
}
