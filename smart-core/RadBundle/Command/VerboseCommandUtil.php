<?php

declare(strict_types=1);

namespace SmartCore\RadBundle\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class VerboseCommandUtil
{
    /** @var SymfonyStyle */
    protected $io;

    /** @var OutputInterface */
    protected $output;

    public function __construct(SymfonyStyle $io, OutputInterface $output)
    {
        $this->io = $io;
        $this->output = $output;
    }

    public function text($message): void
    {
        if ($this->output->isVerbose()) {
            $this->io->text($message);
        }
    }

    public function comment($message): void
    {
        if ($this->output->isVerbose()) {
            $this->io->comment($message);
        }
    }

    public function error($message): void
    {
        if ($this->output->isVerbose()) {
            $this->io->error($message);
        }
    }
}
