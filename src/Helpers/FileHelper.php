<?php

namespace Wecode\WebsiteCreationHelper\Helpers;

class FileHelper
{
    public static function createDirectory(string $path): bool
    {
        return !(!mkdir($path, 0755, true) && !is_dir($path));
    }

    public static function copyDirectory(string $source, string $destination): bool
    {
        if (!is_dir($source)) {
            return false;
        }

        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $dir = opendir($source);
        while (($file = readdir($dir)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $sourcePath = $source . '/' . $file;
            $destPath = $destination . '/' . $file;

            if (is_dir($sourcePath)) {
                self::copyDirectory($sourcePath, $destPath);
            } else {
                copy($sourcePath, $destPath);
            }
        }
        closedir($dir);
        return true;
    }
} 