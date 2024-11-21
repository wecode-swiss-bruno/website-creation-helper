<?php

namespace Wecode\WebsiteCreationHelper;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class WebsiteCreationHelper
{
    private Environment $twig;
    private array $config;

    public function __construct()
    {
        $this->loadConfig();
        $this->initializeTwig();
    }

    public function createProject(string $name, string $framework = 'bootstrap', string $version = '5.3'): bool
    {
        // Logique de création de projet
        return true;
    }

    private function loadConfig(): void
    {
        $this->config = [
            'frameworks' => require __DIR__ . '/../config/frameworks.php',
            'templates' => require __DIR__ . '/../config/templates.php'
        ];
    }

    private function initializeTwig(): void
    {
        $loader = new FilesystemLoader(__DIR__ . '/../templates');
        $this->twig = new Environment($loader, [
            'cache' => false,
            'debug' => true,
            'auto_reload' => true
        ]);
    }
}
