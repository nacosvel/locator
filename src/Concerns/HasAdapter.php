<?php

namespace Nacosvel\Locator\Concerns;

trait HasAdapter
{
    public function getName(): string
    {
        return $this->name;
    }

    public function hasConfig(string $name): bool
    {
        return isset($this->config[$name]);
    }

    public function getConfig(string $name = null): mixed
    {
        return is_null($name) ? $this->config : $this->config[$name] ?? null;
    }

    public function getDefaultConfig(): array
    {
        return $this->getConfig($this->getConfig('default'));
    }
}
