<?php

namespace Wecode\WebsiteCreationHelper\Tests\Commands;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Wecode\WebsiteCreationHelper\Commands\NewProjectCommand;

class NewProjectCommandTest extends TestCase
{
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        $application = new Application();
        $application->add(new NewProjectCommand());
        $command = $application->find('new');
        $this->commandTester = new CommandTester($command);
    }

    public function testExecute()
    {
        $projectName = 'test-project-' . uniqid();

        $this->commandTester->execute([
            'name' => $projectName,
            '--framework' => 'bootstrap',
            '--version' => '5.3'
        ]);

        $this->assertDirectoryExists($projectName);
        $this->assertDirectoryExists("$projectName/assets");
        $this->assertDirectoryExists("$projectName/templates");
        $this->assertFileExists("$projectName/package.json");

        // Cleanup
        $this->removeDirectory($projectName);
    }

    private function removeDirectory(string $dir): void
    {
        if (!file_exists($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = "$dir/$file";
            is_dir($path) ? $this->removeDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }
} 