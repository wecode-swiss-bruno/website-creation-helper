<?php

namespace Wecode\WebsiteCreationHelper\Template;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Symfony\Component\Console\Style\SymfonyStyle;

class TemplateManager
{
    private Environment $twig;
    private array $templates;
    private SymfonyStyle $io;

    public function __construct(SymfonyStyle $io)
    {
        $this->io = $io;
        $this->templates = require __DIR__ . '/../../config/templates.php';
        $this->initTwig();
    }

    private function initTwig(): void
    {
        $loader = new FilesystemLoader(__DIR__ . '/../../templates');
        $this->twig = new Environment($loader, [
            'cache' => false,
            'debug' => true,
            'auto_reload' => true
        ]);
    }

    public function createTemplate(string $projectPath, string $templateName, array $variables = []): bool
    {
        if (!isset($this->templates['templates'][$templateName])) {
            $this->io->error("Template '$templateName' not found");
            return false;
        }

        $template = $this->templates['templates'][$templateName];
        
        try {
            $content = $this->twig->render($template['file'], $variables);
            
            // Ensure templates directory exists
            if (!is_dir("$projectPath/templates")) {
                mkdir("$projectPath/templates", 0755, true);
            }

            file_put_contents(
                "$projectPath/templates/" . $template['file'],
                $content
            );

            return true;
        } catch (\Exception $e) {
            $this->io->error("Error creating template: " . $e->getMessage());
            return false;
        }
    }

    public function listTemplates(): array
    {
        return $this->templates['templates'];
    }
} 