<?php

namespace Wecode\WebsiteCreationHelper\Tests\Template;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Style\SymfonyStyle;
use Wecode\WebsiteCreationHelper\Template\TemplateManager;

class TemplateManagerTest extends TestCase
{
    private TemplateManager $manager;
    private string $testDir;

    protected function setUp(): void
    {
        /** @var SymfonyStyle $io */
        $io = $this->createMock(SymfonyStyle::class);
        $this->manager = new TemplateManager($io);
        $this->testDir = sys_get_temp_dir() . '/wch-test-' . uniqid();
        mkdir($this->testDir);
    }

    protected function tearDown(): void
    {
        $this->removeDirectory($this->testDir);
    }

    public function testListTemplates()
    {
        $templates = $this->manager->listTemplates();
        $this->assertIsArray($templates);
        $this->assertArrayHasKey('base', $templates);
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