<?php

declare(strict_types=1);

namespace App\Command;

use App\Utils\VerboseCommandUtil;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Stopwatch\Stopwatch;

abstract class AbstractCommand extends Command
{
    use ContainerAwareTrait;

    /** @var EntityManagerInterface */
    protected $em;

    /** @var SymfonyStyle */
    protected $io;

    /** @var KernelInterface */
    protected $kernel;

    /** @var LoggerInterface */
    protected $logger;

    protected $mailer;

    /** @var InputInterface */
    protected $input;

    /** @var OutputInterface */
    protected $output;

    private $progressBar;
    private $stopwatch;

    /** @var VerboseCommandUtil */
    public $verbose;

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->io = new SymfonyStyle($input, $output);

        $this->verbose = new VerboseCommandUtil($this->io, $output);

        $this->stopwatch = new Stopwatch();
        $this->stopwatch->start(__FILE__);
    }

    protected function verboseText($message): void
    {
        if ($this->output->isVerbose()) {
            $this->io->text($message);
        }
    }

    protected function verboseComment($message): void
    {
        if ($this->output->isVerbose()) {
            $this->io->comment($message);
        }
    }

    protected function verboseProgressBarStart(int $totalCount, string $totalMessage = 'Всего шагов: '): void
    {
        if ($this->output->isVerbose()) {
            $this->io->text($totalMessage . ' ' . $totalCount);

            $this->progressBar = new ProgressBar($this->output, $totalCount);
            $this->progressBar->setFormat('very_verbose');
            $this->progressBar->start();
        }
    }

    protected function verboseProgressBarAdvance(): void
    {
        if ($this->output->isVerbose()) {
            $this->progressBar->advance();
        }
    }

    protected function verboseProgressBarFinish(): void
    {
        if ($this->output->isVerbose()) {
            $this->progressBar->finish();
        }
    }

    protected function verboseTotalStats()
    {
        if ($this->output->isVerbose()) {
            $event = $this->stopwatch->stop(__FILE__);
            $this->io->comment(sprintf('Time: %.2f sec / Memory: %.2f MB', $event->getDuration() / 1000, $event->getMemory() / (1024 ** 2)));
        }
    }

    public function getStopwatchEvent()
    {
        return $this->stopwatch->getEvent(__FILE__);
    }

    public function getStopwatchEventDuration()
    {
        return $this->stopwatch->getEvent(__FILE__)->getDuration();
    }
}
