<?php

namespace Nacosvel\Locator\Contracts;

use Closure;

interface InstanceSwitchable
{
    /**
     * Set the default instance for the callback execution.
     *
     * @param string  $name
     * @param Closure $callback
     *
     * @return mixed
     */
    public function using(string $name, Closure $callback);
}
