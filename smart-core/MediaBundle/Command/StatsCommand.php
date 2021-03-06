<?php

declare(strict_types=1);

namespace SmartCore\Bundle\MediaBundle\Command;

use SmartCore\Bundle\MediaBundle\Entity\File;
use SmartCore\Bundle\MediaBundle\Entity\FileTransformed;
use SmartCore\Bundle\MediaBundle\Service\MediaCloudService;
use SmartCore\RadBundle\Command\AbstractCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StatsCommand extends AbstractCommand
{
    protected static $defaultName = 'smart:media:stats';

    protected $em;
    protected $mc;

    public function __construct(MediaCloudService $mc)
    {
        parent::__construct();

        $this->em = $mc->getEntityManager();
        $this->mc = $mc;
    }

    protected function configure()
    {
        $this
            ->setDescription('Show media cloud statistics.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $em = $this->em;

        $style = new TableStyle();
        /*
        $style
            ->setVerticalBorderChars('', ' ')
            ->setCrossingChars(' ')
        ;
        */

        $table = new Table($output);
        $table
            ->setHeaders(['Code', 'Collection', 'Storage', 'Default filter', 'Files', 'Original size', 'Filters size', 'Summary size'])
            ->setStyle($style)
        ;

        $totalSize = 0;

        foreach ($this->mc->getCollections() as $collection) {
            $size = round($em->getRepository(File::class)->summarySize($collection->getCode()) / 1024 / 1024, 2);
            $filtersSize = round($em->getRepository(FileTransformed::class)->summarySize($collection->getCode()) / 1024 / 1024, 2);
            $sum = $size + $filtersSize;

            $totalSize += $sum;

            $table->addRow([
                $collection->getCode(),
                $collection->getTitle(),
                $collection->getStorage()->getTitle(),
                $collection->getDefaultFilter(),
                $em->getRepository(File::class)->countByCollection($collection->getCode()),
                $size.' MB',
                $filtersSize.' MB',
                '<comment>'.$sum.'</comment> MB',
            ]);
        }

        $table->render();

        $output->writeln('Total size: '.$totalSize.' MB');

        return 0;
    }
}
