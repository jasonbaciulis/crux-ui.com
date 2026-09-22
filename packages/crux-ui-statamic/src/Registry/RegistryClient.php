<?php

declare(strict_types=1);

namespace CruxUI\Statamic\Registry;

use CruxUI\Statamic\Enums\Stack;
use CruxUI\Statamic\Exceptions\RegistryException;
use Illuminate\Support\Facades\Http;

final class RegistryClient
{
    public function item(string $registryUrl, Stack $stack, string $name): RegistryItem
    {
        $url = sprintf('%s/%s/%s.json', mb_rtrim($registryUrl, '/'), $stack->value, $name);
        $response = Http::acceptJson()->get($url);

        if ($response->notFound()) {
            throw RegistryException::itemNotFound($name, $url);
        }

        if ($response->failed()) {
            throw RegistryException::unreachable($url, $response->status());
        }

        $document = $response->json();

        if (! is_array($document)) {
            throw RegistryException::notJson($url);
        }

        return RegistryItem::fromArray($document, $url);
    }
}
