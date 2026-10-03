<?php

declare(strict_types=1);

namespace Pest\Arch\Repositories;

use Pest\Arch\Factories\ObjectDescriptionFactory;
use Pest\Arch\Objects\FunctionDescription;
use Pest\Arch\Support\Composer;
use Pest\Arch\Support\PhpCoreExpressions;
use Pest\Arch\Support\UserDefinedFunctions;
use PHPUnit\Architecture\Elements\ObjectDescription;
use ReflectionFunction;
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
     * Holds the Objects Descriptions of the previous resolved files.
     *
     * @var array<string, array{0?: ObjectDescription|null, 1?: ObjectDescription|null}>
     */
    private array $cachedObjectsPerFile = [];

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
        if (self::$instance instanceof ObjectsRepository) {
            return self::$instance;
        }

        $loader = Composer::loader();

        $namespaces = [];

        foreach (($loader->getPrefixesPsr4(...))->call($loader) as $namespacePrefix => $directories) {
            $namespace = rtrim($namespacePrefix, '\\');

            $namespaces[$namespace] = $directories;
        }

        return self::$instance = new self($namespaces);
    }

    /**
     * Gets the objects of the given namespace.
     *
     * @return array<int, ObjectDescription|FunctionDescription>
     */
    public function allByNamespace(string $namespace, bool $onlyUserDefinedUses = true): array
    {
        if (PhpCoreExpressions::getClass($namespace) !== null) {
            return [
                FunctionDescription::make($namespace),
            ];
        }

        if (function_exists($namespace) && (new ReflectionFunction($namespace))->getName() === $namespace) {
            return [
                FunctionDescription::make($namespace),
            ];
        }

        $directoriesByNamespace = $this->directoriesByNamespace($namespace);

        if ($directoriesByNamespace === []) {
            return [];
        }

        $objects = [];

        foreach ($directoriesByNamespace as $directories) {
            $objectsPerPrefix = array_values(array_filter(array_reduce($directories, fn (array $files, string $fileOrDirectory): array => array_merge($files, array_values(array_map(
                fn (SplFileInfo $file): ?ObjectDescription => $this->objectByFile($file->getPathname(), $onlyUserDefinedUses),
                is_dir($fileOrDirectory) || str_contains($fileOrDirectory, '*') ? iterator_to_array(Finder::create()->files()->in($fileOrDirectory)->name('*.php')) : [new SplFileInfo($fileOrDirectory)],
            ))), [])));

            $objects = [...$objects, ...$objectsPerPrefix];
        }

        // @phpstan-ignore-next-line
        return [...$objects, ...array_map(
            FunctionDescription::make(...),
            $this->functionsByNamespace($namespace),
        )];
    }

    /**
     * Gets the object of the given file, if any.
     */
    private function objectByFile(string $pathname, bool $onlyUserDefinedUses): ?ObjectDescription
    {
        $this->cachedObjectsPerFile[$pathname] ??= [];

        if (! array_key_exists((int) $onlyUserDefinedUses, $this->cachedObjectsPerFile[$pathname])) {
            $this->cachedObjectsPerFile[$pathname][(int) $onlyUserDefinedUses] = ObjectDescriptionFactory::make($pathname, $onlyUserDefinedUses);
        }

        return $this->cachedObjectsPerFile[$pathname][(int) $onlyUserDefinedUses];
    }

    /**
     * Gets all the functions for the given namespace.
     *
     * @return array<int, string>
     */
    private function functionsByNamespace(string $name): array
    {
        return array_map(
            static function (string $functionName): string {
                $reflection = new ReflectionFunction($functionName);

                return $reflection->getName();
            },
            array_values(array_filter(UserDefinedFunctions::get(), fn (string $function): bool => str_starts_with(
                mb_strtolower($function), mb_strtolower($name)
            )))
        );
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
                $directories = array_values(array_filter($directories, is_dir(...)));

                // Remove the first occurrence of the prefix, if any.
                // This is needed to avoid having a prefix like "App" and a namespace like "App\Application\..."
                // This would result in a directory like "\lication\..."
                $posFirstPrefix = strpos($name, $prefix);
                $nameWithoutPrefix = $posFirstPrefix !== false ? substr($name, $posFirstPrefix + strlen($prefix)) : $name;

                $prefix = str_replace('\\', DIRECTORY_SEPARATOR, ltrim($nameWithoutPrefix, '\\'));

                $directoriesByNamespace[$name] = [...$directoriesByNamespace[$name] ?? [], ...array_values(array_filter(array_merge(...array_map(static function (string $directory) use ($prefix): array {
                    $fileOrDirectory = $directory.DIRECTORY_SEPARATOR.$prefix;

                    if (is_dir($fileOrDirectory)) {
                        return file_exists($fileOrDirectory.'.php')
                            ? [$fileOrDirectory, $fileOrDirectory.'.php']
                            : [$fileOrDirectory];
                    }

                    if (str_contains($fileOrDirectory, '*')) {
                        return [$fileOrDirectory];
                    }

                    return [$fileOrDirectory.'.php'];
                }, $directories)), static fn (string $fileOrDirectory): bool => is_dir($fileOrDirectory) || str_contains($fileOrDirectory, '*') || file_exists($fileOrDirectory)))];
            }
        }

        return $directoriesByNamespace;
    }
}
