<?php

namespace Nacosvel\Locator\Concerns;

trait HasAdapter
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasConfig(string $name): bool
    {
        $items = $this->config;

        foreach (explode('.', $name) as $key) {
            if (!is_array($items) || !array_key_exists($key, $items)) {
                return false;
            }
            $items = $items[$key];
        }

        return true;
    }

    /**
     * @param string|null $name
     * @param mixed|null  $default
     *
     * @return mixed
     */
    public function getConfig(string $name = null, mixed $default = null): mixed
    {
        $items = $this->config;

        if (is_null($name)) {
            return $items;
        }

        foreach (explode('.', $name) as $key) {
            if (!is_array($items) || !array_key_exists($key, $items)) {
                return $default;
            }
            $items = $items[$key];
        }

        return $items;
    }

    /**
     * @return array
     */
    public function getDefaultConfig(): array
    {
        return $this->getConfig($this->getConfig('default'));
    }
}
