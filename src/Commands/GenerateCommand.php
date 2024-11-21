<?php

namespace Wecode\WebsiteCreationHelper\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Wecode\WebsiteCreationHelper\Template\TemplateManager;

class GenerateCommand extends Command
{
    protected static $defaultName = 'generate:page';

    protected function configure()
    {
        $this
            ->setDescription('Generate a new page from a template')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the page')
            ->addOption('template', 't', InputOption::VALUE_REQUIRED, 'Template to use', 'base');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $pageName = $input->getArgument('name');
        $templateName = $input->getOption('template');

        $templateManager = new TemplateManager($io);
        
        if ($templateManager->createTemplate('.', $templateName, [
            'page_name' => $pageName,
            'meta' => [
                'title' => ucfirst($pageName),
                'description' => "Description for $pageName"
            ]
        ])) {
            $io->success("Page $pageName generated successfully!");
            return Command::SUCCESS;
        }

        return Command::FAILURE;
    }
} 