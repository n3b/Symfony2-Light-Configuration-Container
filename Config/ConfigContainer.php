<?php

namespace n3b\Bundle\Util\Service\Config;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ConfigContainer extends ConfigContainerBuilder
{
    public function __construct(array $params = array())
    {
        $this->processParams($params);
    }

    public function getArray()
    {
        return $this->container;
    }
}
