<?php

namespace Wecode\WebsiteCreationHelper\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class InstallCommand extends Command
{
    protected static $defaultName = 'install';

    protected function configure()
    {
        $this->setDescription('Install frontend dependencies');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        
        // Vérifier si npm est installé
        $process = new Process(['npm', '--version']);
        $process->run();
        
        if (!$process->isSuccessful()) {
            $io->error('npm is required but not installed. Please install Node.js first.');
            return Command::FAILURE;
        }

        $io->section('Installing dependencies...');
        
        // Installation des dépendances npm
        $process = new Process(['npm', 'install']);
        $process->setTty(true);
        $process->run(function ($type, $buffer) use ($io) {
            $io->write($buffer);
        });

        if (!$process->isSuccessful()) {
            $io->error('Failed to install dependencies');
            return Command::FAILURE;
        }

        $io->success('Dependencies installed successfully!');
        return Command::SUCCESS;
    }
} 