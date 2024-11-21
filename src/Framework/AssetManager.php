<?php

namespace Wecode\WebsiteCreationHelper\Framework;

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Style\SymfonyStyle;

class AssetManager
{
    private SymfonyStyle $io;

    public function __construct(SymfonyStyle $io)
    {
        $this->io = $io;
    }

    public function setupAssets(string $projectPath, string $framework): bool
    {
        // Créer le fichier de configuration webpack si nécessaire
        if ($framework === 'tailwind') {
            $this->setupTailwind($projectPath);
        }

        // Créer les scripts npm
        $this->updatePackageJson($projectPath, $framework);

        return true;
    }

    private function setupTailwind(string $projectPath): void
    {
        $cssContent = "@tailwind base;\n@tailwind components;\n@tailwind utilities;";
        file_put_contents("$projectPath/assets/css/app.css", $cssContent);
    }

    private function updatePackageJson(string $projectPath, string $framework): void
    {
        $packageJson = json_decode(file_get_contents("$projectPath/package.json"), true);
        
        $packageJson['scripts'] = array_merge($packageJson['scripts'] ?? [], [
            'dev' => 'npm run development',
            'development' => 'mix',
            'watch' => 'mix watch',
            'prod' => 'npm run production',
            'production' => 'mix --production'
        ]);

        file_put_contents(
            "$projectPath/package.json",
            json_encode($packageJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }
}
