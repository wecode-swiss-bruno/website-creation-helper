<?php

namespace Wecode\WebsiteCreationHelper\Framework;

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Style\SymfonyStyle;

class FrameworkManager
{
    private array $frameworks;
    private SymfonyStyle $io;

    public function __construct(SymfonyStyle $io)
    {
        $this->frameworks = require __DIR__ . '/../../config/frameworks.php';
        $this->io = $io;
    }

    public function install(string $projectPath, string $framework, string $version): bool
    {
        if (!isset($this->frameworks['css_frameworks'][$framework])) {
            $this->io->error("Framework '$framework' not supported");
            return false;
        }

        $frameworkConfig = $this->frameworks['css_frameworks'][$framework];

        // Initialize package.json if not exists
        if (!file_exists("$projectPath/package.json")) {
            $process = new Process(['npm', 'init', '-y'], $projectPath);
            $process->run();
        }

        // Install framework
        $packageName = "{$frameworkConfig['package']}@$version";
        $process = new Process(['npm', 'install', $packageName, '--save'], $projectPath);
        $process->run(function ($type, $buffer) {
            $this->io->write($buffer);
        });

        // Install dependencies if any
        if (isset($frameworkConfig['dependencies'])) {
            foreach ($frameworkConfig['dependencies'] as $dep) {
                $process = new Process(['npm', 'install', $dep, '--save'], $projectPath);
                $process->run();
            }
        }

        // Framework specific setup
        if (isset($frameworkConfig['setup'])) {
            $process = new Process(explode(' ', $frameworkConfig['setup']), $projectPath);
            $process->run();
        }

        return true;
    }

    public function generateConfig(string $projectPath, string $framework): void
    {
        $frameworkConfig = $this->frameworks['css_frameworks'][$framework];
        
        if ($framework === 'tailwind') {
            file_put_contents(
                "$projectPath/tailwind.config.js",
                json_encode($frameworkConfig['config'], JSON_PRETTY_PRINT)
            );
        }
    }
}
