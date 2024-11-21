<?php

namespace Wecode\WebsiteCreationHelper\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class NewProjectCommand extends Command
{
    protected static $defaultName = 'new';

    protected function configure()
    {
        $this
            ->setDescription('Create a new project')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the project')
            ->addOption(
                'framework',
                'f',
                InputOption::VALUE_OPTIONAL,
                'The CSS framework to use',
                'bootstrap'
            )
            ->addOption(
                'framework-version',
                null,
                InputOption::VALUE_OPTIONAL,
                'The framework version to use',
                '5.3'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $projectName = $input->getArgument('name');
        $framework = $input->getOption('framework');
        $version = $input->getOption('framework-version');

        $io->title("Creating new project: $projectName");

        // Vérification du framework
        $frameworks = require __DIR__ . '/../../config/frameworks.php';
        if (!isset($frameworks['css_frameworks'][$framework])) {
            $io->error("Framework '$framework' not supported");
            return Command::FAILURE;
        }

        // Création de la structure
        if (!$this->createProjectStructure($projectName, $io)) {
            return Command::FAILURE;
        }

        // Installation du framework
        if (!$this->installFramework($projectName, $framework, $version, $io)) {
            return Command::FAILURE;
        }

        // Création des fichiers de base
        if (!$this->createBaseFiles($projectName, $framework, $version, $io)) {
            return Command::FAILURE;
        }

        // Initialisation de Git
        $this->initGit($projectName);

        $io->success("Project created successfully in ./$projectName");
        return Command::SUCCESS;
    }

    private function createProjectStructure(string $projectName, SymfonyStyle $io): bool
    {
        $directories = [
            "$projectName/src",
            "$projectName/public",
            "$projectName/public/css",
            "$projectName/public/js",
            "$projectName/public/images",
            "$projectName/templates",
            "$projectName/templates/layouts",
            "$projectName/templates/components",
            "$projectName/templates/pages",
            "$projectName/assets/css",
            "$projectName/assets/js",
            "$projectName/assets/images",
            "$projectName/config"
        ];

        foreach ($directories as $dir) {
            if (!mkdir($dir, 0755, true) && !is_dir($dir)) {
                $io->error("Failed to create directory: $dir");
                return false;
            }
        }

        return true;
    }

    private function installFramework(string $projectName, string $framework, string $version, SymfonyStyle $io): bool
    {
        try {
            $process = new Process(['npm', 'init', '-y'], $projectName);
            $process->run();

            $process = new Process(['npm', 'install', "$framework@$version", '--save'], $projectName);
            $process->run(function ($type, $buffer) use ($io) {
                $io->write($buffer);
            });

            return $process->isSuccessful();
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            return false;
        }
    }

    private function createBaseFiles(string $projectName, string $framework, string $version, SymfonyStyle $io): bool
    {
        try {
            // package.json
            $packageJson = [
                'name' => $projectName,
                'version' => '1.0.0',
                'description' => 'A new web project',
                'scripts' => [
                    'dev' => 'npm run development',
                    'development' => 'mix',
                    'watch' => 'mix watch',
                    'prod' => 'npm run production',
                    'production' => 'mix --production'
                ],
                'dependencies' => [
                    $framework => "^$version"
                ],
                'devDependencies' => [
                    'laravel-mix' => '^6.0.0',
                    'sass' => '^1.43.4',
                    'sass-loader' => '^12.3.0'
                ]
            ];

            file_put_contents(
                "$projectName/package.json",
                json_encode($packageJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );

            // Autres fichiers
            $this->createConfigFiles($projectName);
            $this->createTemplateFiles($projectName);
            $this->createAssetFiles($projectName, $framework);
            $this->createDocFiles($projectName);

            return true;
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            return false;
        }
    }

    private function initGit(string $projectPath): void
    {
        if (is_dir("$projectPath/.git")) {
            return;
        }

        $commands = [
            ['git', 'init'],
            ['git', 'add', '.'],
            ['git', 'commit', '-m', 'Initial commit']
        ];

        foreach ($commands as $command) {
            $process = new Process($command, $projectPath);
            $process->run();
        }
    }

    private function createConfigFiles(string $projectName): void
    {
        // webpack.mix.js
        $webpackMix = <<<'JS'
const mix = require('laravel-mix');

mix.js('assets/js/app.js', 'public/js')
   .sass('assets/css/app.scss', 'public/css')
   .copyDirectory('assets/images', 'public/images')
   .version();
JS;
        file_put_contents("$projectName/webpack.mix.js", $webpackMix);

        // .env
        $env = <<<'ENV'
APP_NAME="{$projectName}"
APP_ENV=local
APP_DEBUG=true
ENV;
        file_put_contents("$projectName/.env", $env);

        // .gitignore
        $gitignore = <<<'GIT'
/node_modules
/public/css
/public/js
/public/mix-manifest.json
.env
.DS_Store
GIT;
        file_put_contents("$projectName/.gitignore", $gitignore);

        // index.html
        $indexHtml = <<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <div class="container py-5">
        <h1>Welcome to your new project!</h1>
        <p class="lead">Your development environment is ready.</p>
    </div>
    <script src="/js/app.js"></script>
</body>
</html>
HTML;
        file_put_contents("$projectName/public/index.html", $indexHtml);
    }

    private function createTemplateFiles(string $projectName): void
    {
        // Base template
        $baseTemplate = <<<'TWIG'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{% block title %}Welcome{% endblock %}</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <nav>
        {% block navigation %}
        <div class="navbar">
            <a href="/" class="navbar-brand">{{ projectName }}</a>
        </div>
        {% endblock %}
    </nav>

    <main>
        {% block content %}{% endblock %}
    </main>

    <footer>
        {% block footer %}
            <p>&copy; {{ "now"|date("Y") }} {{ projectName }}. All rights reserved.</p>
        {% endblock %}
    </footer>

    <script src="/js/app.js"></script>
</body>
</html>
TWIG;
        file_put_contents("$projectName/templates/layouts/base.html.twig", $baseTemplate);

        // Index template
        $indexTemplate = <<<'TWIG'
{% extends "layouts/base.html.twig" %}

{% block title %}Home{% endblock %}

{% block content %}
    <div class="container">
        <h1>Welcome to {{ projectName }}</h1>
        <p>Your new project is ready!</p>
    </div>
{% endblock %}
TWIG;
        file_put_contents("$projectName/templates/pages/index.html.twig", $indexTemplate);
    }

    private function createAssetFiles(string $projectName, string $framework): void
    {
        // app.js
        $appJs = <<<'JS'
// Import your JavaScript dependencies
import './bootstrap';

// Your custom JavaScript code
console.log('Application initialized');
JS;
        file_put_contents("$projectName/assets/js/app.js", $appJs);

        // bootstrap.js
        $bootstrapJs = <<<'JS'
// Import framework specific dependencies
import 'bootstrap';
JS;
        file_put_contents("$projectName/assets/js/bootstrap.js", $bootstrapJs);

        // app.scss
        $appScss = <<<SCSS
// Framework imports
@import "bootstrap/scss/bootstrap";

// Variables
@import "variables";

// Base styles
@import "base";

// Components
@import "components/buttons";
@import "components/navigation";

// Pages
@import "pages/home";
SCSS;
        file_put_contents("$projectName/assets/css/app.scss", $appScss);

        // Créer les fichiers de composants
        if (!is_dir("$projectName/assets/css/components")) {
            mkdir("$projectName/assets/css/components", 0755, true);
        }
        
        // _buttons.scss
        file_put_contents("$projectName/assets/css/components/_buttons.scss", "// Custom button styles");
        
        // _navigation.scss
        file_put_contents("$projectName/assets/css/components/_navigation.scss", "// Custom navigation styles");
        
        // _base.scss
        file_put_contents("$projectName/assets/css/base.scss", "// Base styles");
    }

    private function createDocFiles(string $projectName): void
    {
        // README.md
        $readme = <<<MD
# {$projectName}

## Description
A modern web project created with Website Creation Helper.

## Installation

</file>

bash
npm install

## Development

bash
npm run dev

## Production

bash
npm run prod


## Structure
- `assets/`: Source files (JS, SCSS)
- `public/`: Web root directory
- `templates/`: Twig templates
- `config/`: Configuration files

## License
ISC
MD;
        file_put_contents("$projectName/README.md", $readme);
    }
}
