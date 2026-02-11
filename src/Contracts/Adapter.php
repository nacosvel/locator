<?php

namespace Nacosvel\Locator\Contracts;

interface Adapter
{
    public function getName(): string;

    public function hasConfig(string $name): bool;

    public function getConfig(string $name = null, mixed $default = null): mixed;

    public function getDefaultConfig(): array;
}
