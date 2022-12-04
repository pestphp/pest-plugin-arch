<?php

declare(strict_types=1);

namespace Pest\Arch\Repositories;

use Pest\Arch\Factories\ObjectDescriptionFactory;
use Pest\TestSuite;
use PHPUnit\Architecture\Elements\ObjectDescription;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

/**
 * @internal
 */
final class ObjectsRepository
{
    /**
     * Creates a new Objects Repository singleton instance, if any.
     */
    private static ?self $instance = null;

    /**
     * Holds the Objects Descriptions of the previous resolved prefixes.
     *
     * @var array<string, array<int, ObjectDescription>>
     */
    private array $cachedObjectsPerPrefix = [];

    /**
     * Creates a new Objects Repository.
     *
     * @param  array<string, array<int, string>>  $prefixes
     */
    public function __construct(private readonly array $prefixes)
    {
        // ...
    }

    /**
     * Creates a new Composer Namespace Repositories instance from the "global" autoloader.
     */
    public static function getInstance(): self
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $autoload = TestSuite::getInstance()->rootPath.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
        $autoloadLines = explode("\n", (string) file_get_contents($autoload));
        $loader = eval($autoloadLines[count($autoloadLines) - 2]); // @phpstan-ignore-line

        $namespaces = [];

        foreach ((fn () => $loader->getPrefixesPsr4())->call($loader) as $namespacePrefix => $directories) {
            $namespace = rtrim($namespacePrefix, '\\');

            $namespaces[$namespace] = $directories;
        }

        return self::$instance = new self($namespaces);
    }

    /**
     * Gets the objects of the given namespace.
     *
     * @return array<int, ObjectDescription>
     */
    public function allByNamespace(string $namespace): array
    {
        $directoriesByNamespace = $this->directoriesByNamespace($namespace);

        if ($directoriesByNamespace === null) {
            return [];
        }

        [$prefix, $directories] = $directoriesByNamespace;

        if (array_key_exists($prefix, $this->cachedObjectsPerPrefix)) {
            return $this->cachedObjectsPerPrefix[$prefix];
        }

        $objectsPerPrefix = array_values(array_filter(array_reduce($directories, function (array $files, $directory): array {
            return array_merge($files, array_values(array_map(
                static fn (SplFileInfo $file): ObjectDescription|null => ObjectDescriptionFactory::make($file->getRealPath()),
                iterator_to_array(Finder::create()->files()->in($directory))
            )));
        }, [])));

        return $this->cachedObjectsPerPrefix[$prefix] = $objectsPerPrefix;  // phpstan-ignore-line
    }

    /**
     * Gets all the directories for the given namespace.
     *
     * @return array{string, array<int, string>}
     */
    private function directoriesByNamespace(string $name): array|null
    {
        foreach ($this->prefixes as $prefix => $directories) {
            if (str_starts_with($name, $prefix)) {
                return [$prefix, $directories];
            }
        }

        return null;
    }
}
