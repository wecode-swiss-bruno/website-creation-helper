<?php

namespace Wecode\WebsiteCreationHelper\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class ServeCommand extends Command
{
    protected static $defaultName = 'serve';

    protected function configure()
    {
        $this->setDescription('Start development server');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        
        $io->title('Starting development server...');

        $process = new Process(['php', '-S', 'localhost:8000', '-t', 'public']);
        $process->setTty(true);
        $process->run(function ($type, $buffer) use ($io) {
            $io->write($buffer);
        });

        return Command::SUCCESS;
    }
} 