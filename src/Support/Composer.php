<?php

declare(strict_types=1);

namespace Pest\Arch\Support;

use Composer\Autoload\ClassLoader;
use Pest\TestSuite;

/**
 * @internal
 */
final class Composer
{
    /**
     * Gets the list of namespaces defined in the "composer.json" file.
     *
     * @return array<int, string>
     */
    public static function userNamespaces(): array
    {
        $namespaces = [];

        $vendorDirectory = TestSuite::getInstance()->rootPath.DIRECTORY_SEPARATOR.'vendor';

        foreach (self::loader()->getPrefixesPsr4() as $namespace => $directories) {
            foreach ($directories as $directory) {
                if (realpath($directory) === false) {
                    continue;
                }
                if (str_starts_with(realpath($directory), $vendorDirectory)) {
                    continue;
                }
                $namespaces[] = rtrim($namespace, '\\');
            }
        }

        return $namespaces;
    }

    /**
     * Gets composer's autoloader class.
     */
    public static function loader(): ClassLoader
    {
        $autoload = TestSuite::getInstance()->rootPath.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
        $autoloadLines = explode("\n", (string) file_get_contents($autoload));

        /** @var ClassLoader $loader */
        $loader = eval($autoloadLines[count($autoloadLines) - 2]); // @phpstan-ignore-line

        return $loader;
    }
}
