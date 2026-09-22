<?php

declare(strict_types=1);

namespace CruxUI\Statamic\Exceptions;

use RuntimeException;

final class RegistryException extends RuntimeException
{
    public static function itemNotFound(string $name, string $url): self
    {
        return new self(sprintf('No registry item named "%s" at %s', $name, $url));
    }

    public static function unreachable(string $url, int $status): self
    {
        return new self(sprintf('The registry at %s answered with HTTP %d', $url, $status));
    }

    public static function notJson(string $url): self
    {
        return new self(sprintf('The registry item at %s is not a JSON object', $url));
    }

    public static function malformed(string $url, string $field): self
    {
        return new self(sprintf('The registry item at %s has no valid "%s" field', $url, $field));
    }
}
