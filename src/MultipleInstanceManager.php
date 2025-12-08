<?php

namespace Nacosvel\Locator;

use Closure;
use InvalidArgumentException;
use Nacosvel\Locator\Contracts\InstanceConfig;
use Nacosvel\Locator\Contracts\InstanceManager;
use Nacosvel\Locator\Contracts\InstanceSwitchable;
use Nacosvel\Macroable\Macroable;
use RuntimeException;

abstract class MultipleInstanceManager implements InstanceConfig, InstanceManager, InstanceSwitchable
{
    use Macroable {
        __call as macroCall;
    }

    /**
     * The configuration repository instance.
     *
     * @var array
     */
    protected array $config;

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
     * Get the instance specific configuration.
     *
     * @param string $name
     *
     * @return array|null
     */
    public function getInstanceConfig(string $name): ?array
    {
        if (is_null($config = $this->config[$name])) {
            return null;
        }

        return ($default = $config['default'] ?? null) ? $config[$default] : $config;
    }

    /**
     * Get the default instance name.
     *
     * @return string
     */
    public function getDefaultInstance(): string
    {
        return $this->config['default'] ?? 'default';
    }

    /**
     * Set the default instance name.
     *
     * @param string $name
     *
     * @return void
     */
    public function setDefaultInstance(string $name): void
    {
        $this->config['default'] = $name;
    }

    /**
     * Get an instance by name.
     *
     * @param string|null $name
     *
     * @return mixed
     */
    public function instance(string $name = null)
    {
        $name = $name ?: $this->getDefaultInstance();

        return $this->instances[$name] ??= $this->resolve($name);
    }

    /**
     * Resolve the given instance.
     *
     * @param string $name
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    protected function resolve(string $name)
    {
        $config     = $this->getInstanceConfig($name) ?? [];
        $driverName = $name;

        if (isset($this->customCreators[$driverName])) {
            return $this->callCustomCreator($driverName, $config);
        }

        $createMethod = 'create' . ucfirst($driverName) . ucfirst($this->driverKey);

        if (method_exists($this, $createMethod)) {
            return $this->{$createMethod}($config);
        }

        $words        = mb_split('\s+', str_replace(['-', '_'], ' ', $driverName));
        $studlyWords  = array_map(fn($word) => ucfirst($word), $words);
        $createMethod = 'create' . implode($studlyWords) . ucfirst($this->driverKey);

        if (method_exists($this, $createMethod)) {
            return $this->{$createMethod}($config);
        }

        throw new InvalidArgumentException("Instance {$name} is not supported.");
    }

    /**
     * Call a custom instance creator.
     *
     * @param string $driverName
     * @param array  $config
     *
     * @return mixed
     */
    protected function callCustomCreator(string $driverName, array $config)
    {
        return $this->customCreators[$driverName]($config);
    }

    /**
     * Set the default instance for the callback execution.
     *
     * @param string  $name
     * @param Closure $callback
     *
     * @return mixed
     */
    public function using(string $name, Closure $callback)
    {
        $previousName = $this->getDefaultInstance();

        try {
            $this->setDefaultInstance($name);
            return call_user_func($callback, $this);
        } finally {
            $this->setDefaultInstance($previousName);
        }
    }

    /**
     * Unset the given instances.
     *
     * @param array|string|null $name
     *
     * @return static
     */
    public function forgetInstance(array|string $name = null): static
    {
        $name      = $name ?? $this->getDefaultInstance();
        $instances = is_array($name) ? $name : [$name];

        foreach ($instances as $instanceName) {
            if (isset($this->instances[$instanceName])) {
                unset($this->instances[$instanceName]);
            }
        }

        return $this;
    }

    /**
     * Disconnect the given instance and remove from local cache.
     *
     * @param string|null $name
     *
     * @return void
     */
    public function purge(string $name = null): void
    {
        $name = $name ?? $this->getDefaultInstance();

        unset($this->instances[$name]);
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
