<?php

namespace Wecode\WebsiteCreationHelper\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class BuildCommand extends Command
{
    protected static $defaultName = 'build';

    protected function configure()
    {
        $this->setDescription('Build assets for production');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        
        $io->title('Building assets for production...');

        // NPM Build
        $process = new Process(['npm', 'run', 'build']);
        $process->setTty(true);
        $process->run(function ($type, $buffer) use ($io) {
            $io->write($buffer);
        });

        if (!$process->isSuccessful()) {
            $io->error('Build failed');
            return Command::FAILURE;
        }

        $io->success('Assets built successfully!');
        return Command::SUCCESS;
    }
} 