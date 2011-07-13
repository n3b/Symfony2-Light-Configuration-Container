<?php

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ConfigContainerBuilder
{
    protected $container = array();

    public function __construct(ContainerInterface $container)
    {
        if($container->hasParameter('n3b.config'))
            $this->processParams($container->getParameter('n3b.config'));
    }

    public function init(array $configs, ContainerBuilder $container, $alias)
    {
        $config = array();
        foreach($configs as $subConfig)
            $config = \array_merge($config, $subConfig);

        $topConf[$alias] = $config;
        if($container->hasParameter('n3b.config'))
            $topConf = \array_merge($topConf, $container->getParameter('n3b.config'));

        $container->setParameter('n3b.config', $topConf);
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
