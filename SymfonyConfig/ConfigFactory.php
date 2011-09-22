<?php

namespace n3b\Bundle\Util\SymfonyConfig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ConfigFactory
{
    private $nodeName = 'n3b_config';

    public function build(array $config)
    {
        $container = new ConfigContainer();
        
        foreach($config as $key => $value) {
            if(is_array($value))
                $container[$key] = $this->build($value);
            else
                $container[$key] = $value;
        }
        
        return $container;
    }
    
    public function inject(ContainerInterface $container, array $configs, $alias) 
    {
        if($container->hasParameter($this->nodeName))
            $newConfigs = $container->getParameter($this->nodeName);
        
        $newConfigs[$alias] = $configs[0];
        
        $container->setParameter($this->nodeName, $newConfigs);
    }
}
