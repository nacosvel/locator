<?php

namespace Nacosvel\Locator\Contracts;

interface InstanceConfig
{
    /**
     * Get the instance specific configuration.
     *
     * @param string $name
     *
     * @return array|null
     */
    public function getInstanceConfig(string $name): ?array;
}
