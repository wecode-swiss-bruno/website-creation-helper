<?php

namespace Wecode\WebsiteCreationHelper\Tests\Framework;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Style\SymfonyStyle;
use Wecode\WebsiteCreationHelper\Framework\FrameworkManager;

class FrameworkManagerTest extends TestCase
{
    private FrameworkManager $manager;
    private string $testDir;

    protected function setUp(): void
    {
        $io = $this->createMock(SymfonyStyle::class);
        $this->manager = new FrameworkManager($io);
        $this->testDir = sys_get_temp_dir() . '/wch-test-' . uniqid();
        mkdir($this->testDir);
    }

    protected function tearDown(): void
    {
        $this->removeDirectory($this->testDir);
    }

    public function testInstallFramework()
    {
        $result = $this->manager->install($this->testDir, 'bootstrap', '5.3');
        $this->assertTrue($result);
        $this->assertFileExists("$this->testDir/package.json");
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