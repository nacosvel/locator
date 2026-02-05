<?php

namespace Nacosvel\Locator\Contracts;

use Closure;

interface MultipleManager
{
    /**
     * Get an instance by name.
     *
     * @param string|null $name
     *
     * @return Adapter
     */
    public function instance(string $name = null): Adapter;

    /**
     * Get specific configuration.
     *
     * @param string|null $name
     *
     * @return array
     */
    public function getConfig(string $name = null): array;

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver(): string;

    /**
     * Set the default driver name.
     *
     * @param string $name
     *
     * @return void
     */
    public function setDefaultDriver(string $name): void;

    /**
     * Register a custom instance creator Closure.
     *
     * @param string  $name
     * @param Closure $callback
     *
     * @param-closure-this  $this  $callback
     *
     * @return static
     */
    public function extend(string $name, Closure $callback): static;
}
