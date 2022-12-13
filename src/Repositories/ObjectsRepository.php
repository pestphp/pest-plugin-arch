<?php

declare(strict_types=1);

namespace Pest\Arch\Repositories;

use Pest\Arch\Factories\ObjectDescriptionFactory;
use Pest\Arch\LayerOptions;
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
            $namespace = rtrim((string) $namespacePrefix, '\\');

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

        if ($directoriesByNamespace === []) {
            return [];
        }

        $objects = [];

        foreach ($directoriesByNamespace as $prefix => $directories) {
            if (array_key_exists($prefix, $this->cachedObjectsPerPrefix)) {
                $objects = [...$this->cachedObjectsPerPrefix[$prefix]];

                continue;
            }

            $objectsPerPrefix = array_values(array_filter(array_reduce($directories, fn (array $files, $directory): array => array_merge($files, array_values(array_map(
                static fn (SplFileInfo $file): ObjectDescription|null => ObjectDescriptionFactory::make($file->getRealPath()),
                iterator_to_array(Finder::create()->files()->in($directory)->name('*.php')),
            ))), [])));  // phpstan-ignore-line

            return [...$this->cachedObjectsPerPrefix[$prefix] = $objectsPerPrefix];
        }

        return $objects;
    }

    /**
     * Gets all the directories for the given namespace.
     *
     * @return array<string, array<int, string>>
     */
    private function directoriesByNamespace(string $name): array
    {
        $directoriesByNamespace = [];

        foreach ($this->prefixes as $prefix => $directories) {
            if (str_starts_with($name, $prefix)) {
                $directories = array_values(array_filter($directories, static fn (string $directory): bool => is_dir($directory)));

                $directoriesByNamespace[$prefix] = $directories;
            }
        }

        return $directoriesByNamespace;
    }
}
