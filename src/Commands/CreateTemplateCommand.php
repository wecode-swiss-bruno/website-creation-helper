<?php

namespace Wecode\WebsiteCreationHelper\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateTemplateCommand extends Command
{
    protected static $defaultName = 'template:create';

    protected function configure()
    {
        $this
            ->setDescription('Create a new custom template')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the template');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $templateName = $input->getArgument('name');
        
        // Créer le dossier des templates personnalisés s'il n'existe pas
        $customTemplateDir = __DIR__ . '/../../templates/custom';
        if (!is_dir($customTemplateDir)) {
            mkdir($customTemplateDir, 0755, true);
        }

        // Créer le nouveau template
        $templatePath = $customTemplateDir . '/' . $templateName . '.twig';
        if (file_exists($templatePath)) {
            $io->error("Template '$templateName' already exists!");
            return Command::FAILURE;
        }

        // Template de base
        $templateContent = $this->getBaseTemplate();
        file_put_contents($templatePath, $templateContent);

        $io->success("Template '$templateName' created successfully!");
        return Command::SUCCESS;
    }

    private function getBaseTemplate(): string
    {
        return <<<TWIG
{% extends "base.twig" %}

{% block title %}New Template{% endblock %}

{% block content %}
    <div class="container">
        <h1>New Template</h1>
        <!-- Add your content here -->
    </div>
{% endblock %}
TWIG;
    }
}
