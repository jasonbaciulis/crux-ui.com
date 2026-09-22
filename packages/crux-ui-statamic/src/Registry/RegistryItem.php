<?php

declare(strict_types=1);

namespace CruxUI\Statamic\Registry;

use CruxUI\Statamic\Exceptions\RegistryException;

final readonly class RegistryItem
{
    private const string NAMESPACE_PREFIX = '@crux/';

    /**
     * @param  list<RegistryFile>  $files
     * @param  list<string>  $dependencies  npm packages the item needs.
     * @param  list<string>  $registryDependencies  Other items the item needs, without the @crux/ prefix.
     */
    public function __construct(
        public string $name,
        public array $files,
        public array $dependencies,
        public array $registryDependencies,
        public ?string $docs,
    ) {}

    /**
     * @param  array<mixed>  $data
     */
    public static function fromArray(array $data, string $url): self
    {
        return new self(
            name: self::string($data, 'name', $url),
            files: array_map(
                fn (mixed $file): RegistryFile => self::file($file, $url),
                self::list($data, 'files', $url),
            ),
            dependencies: self::strings($data, 'dependencies', $url),
            registryDependencies: array_map(
                self::withoutNamespace(...),
                self::strings($data, 'registryDependencies', $url),
            ),
            docs: isset($data['docs']) ? self::string($data, 'docs', $url) : null,
        );
    }

    public static function withoutNamespace(string $name): string
    {
        return str_starts_with($name, self::NAMESPACE_PREFIX)
            ? mb_substr($name, mb_strlen(self::NAMESPACE_PREFIX))
            : $name;
    }

    private static function file(mixed $file, string $url): RegistryFile
    {
        if (! is_array($file)) {
            throw RegistryException::malformed($url, 'files');
        }

        return new RegistryFile(
            target: self::string($file, 'target', $url),
            content: self::string($file, 'content', $url),
        );
    }

    /**
     * @param  array<mixed>  $data
     */
    private static function string(array $data, string $field, string $url): string
    {
        $value = $data[$field] ?? null;

        if (! is_string($value)) {
            throw RegistryException::malformed($url, $field);
        }

        return $value;
    }

    /**
     * @param  array<mixed>  $data
     * @return list<mixed>
     */
    private static function list(array $data, string $field, string $url): array
    {
        $value = $data[$field] ?? [];

        if (! is_array($value) || ! array_is_list($value)) {
            throw RegistryException::malformed($url, $field);
        }

        return $value;
    }

    /**
     * @param  array<mixed>  $data
     * @return list<string>
     */
    private static function strings(array $data, string $field, string $url): array
    {
        $values = self::list($data, $field, $url);

        if (! array_all($values, fn (mixed $value): bool => is_string($value))) {
            throw RegistryException::malformed($url, $field);
        }

        /** @var list<string> $values */
        return $values;
    }
}
