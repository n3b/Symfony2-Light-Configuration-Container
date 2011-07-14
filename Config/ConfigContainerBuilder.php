<?php

namespace n3b\Bundle\Util\Service\Config;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ConfigContainerBuilder extends Config
{
    protected $container = array();

    public function __construct(ContainerInterface $container)
    {
        if($container->hasParameter(self::CONFIG_NODE_NAME))
            $this->processParams($container->getParameter(self::CONFIG_NODE_NAME));
    }

    public function set($key, $value)
    {
        $this->container[$key] = $value;
    }

    public function get($key)
    {
        $keys = explode('.', $key);

        if(isset($this->container[$keys[0]])) {
            if(count($keys) > 1 && $this->container[$keys[0]] instanceof ConfigContainer)
                return $this->container[$keys[0]]->get(\str_replace($keys[0] . '.', '', $key));

            return $this->container[$keys[0]];
        }

        return null;
    }

    protected function processParams($params)
    {
        foreach($params as $k => $v) {
            if(is_array($v))
                $this->set($k, new ConfigContainer($v));
            else
                $this->set($k, $v);
        }
    }
}
