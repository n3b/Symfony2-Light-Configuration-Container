<?php

namespace n3b\Bundle\Util\Service\Config;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class ConfigParser
{
    public function __construct(array $configs, ContainerBuilder $container, $alias)
    {
        $this->parse($configs, $container, $alias);
    }

    private function parse(array $configs, ContainerBuilder $container, $alias)
    {
        $config = array();
        foreach($configs as $subConfig)
            $config = \array_merge($config, $subConfig);

        $topConf[$alias] = $config;
        if($container->hasParameter(self::CONFIG_NODE_NAME))
            $topConf = \array_merge($topConf, $container->getParameter(self::CONFIG_NODE_NAME));

        $container->setParameter(self::CONFIG_NODE_NAME, $topConf);
    }
}
