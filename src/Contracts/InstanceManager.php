<?php

namespace Nacosvel\Locator\Contracts;

use Closure;

interface InstanceManager
{
    /**
     * Get the default instance name.
     *
     * @return string
     */
    public function getDefaultInstance(): string;

    /**
     * Set the default instance name.
     *
     * @param string $name
     *
     * @return void
     */
    public function setDefaultInstance(string $name): void;

    /**
     * Get an instance by name.
     *
     * @param string|null $name
     *
     * @return mixed
     */
    public function instance(string $name = null);

    /**
     * Unset the given instances.
     *
     * @param array|string|null $name
     *
     * @return static
     */
    public function forgetInstance(array|string $name = null): static;

    /**
     * Disconnect the given instance and remove from local cache.
     *
     * @param string|null $name
     *
     * @return void
     */
    public function purge(string $name = null): void;

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
