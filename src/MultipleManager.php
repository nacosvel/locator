<?php

namespace Nacosvel\Locator;

use Closure;
use InvalidArgumentException;
use Nacosvel\Locator\Contracts\Adapter;
use Nacosvel\Macroable\Macroable;
use RuntimeException;

class MultipleManager implements Contracts\MultipleManager
{
    use Macroable {
        __call as macroCall;
    }

    /**
     * The configuration repository instance.
     *
     * @var array
     */
    protected array $config = [];

    /**
     * The array of resolved instances.
     *
     * @var array
     */
    protected array $instances = [];

    /**
     * The registered custom instance creators.
     *
     * @var array
     */
    protected array $customCreators = [];

    /**
     * The key name of the "driver" equivalent configuration option.
     *
     * @var string
     */
    protected string $driverKey = 'driver';

    /**
     * Create a new manager instance.
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Get an instance by name.
     *
     * @param string|null $name
     *
     * @return Adapter
     */
    public function instance(string $name = null): Adapter
    {
        $name = $name ?: $this->getDefaultDriver();

        return $this->instances[$name] ??= $this->resolve($name);
    }

    /**
     * Resolve the given instance.
     *
     * @param string $name
     *
     * @return Adapter
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    protected function resolve(string $name): Adapter
    {
        $config = $this->getConfig($name);

        if (isset($this->customCreators[$name])) {
            return $this->callCustomCreator($name, $config);
        }

        $driverMethod = 'create' . ucfirst($name) . ucfirst($this->driverKey);

        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}($name, $config);
        }

        throw new InvalidArgumentException("Driver {$name} is not defined.");
    }

    /**
     * Call a custom instance creator.
     *
     * @param string $name
     * @param array  $config
     *
     * @return Adapter
     */
    protected function callCustomCreator(string $name, array $config): Adapter
    {
        return $this->customCreators[$name]($name, $config);
    }

    /**
     * Get specific configuration.
     *
     * @param string|null $name
     *
     * @return array
     */
    public function getConfig(string $name = null): array
    {
        return is_null($name) ? $this->config : $this->config[$name] ?? [];
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver(): string
    {
        return $this->config['default'] ?? 'default';
    }

    /**
     * Set the default driver name.
     *
     * @param string $name
     *
     * @return void
     */
    public function setDefaultDriver(string $name): void
    {
        $this->config['default'] = $name;
    }

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
    public function extend(string $name, Closure $callback): static
    {
        $this->customCreators[$name] = $callback->bindTo($this, $this);

        return $this;
    }

    /**
     * Dynamically call the default instance.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        return $this->instance()->$method(...$parameters);
    }
}
