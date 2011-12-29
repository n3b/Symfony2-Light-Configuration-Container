<?php

namespace YourBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;
use n3b\Bundle\Util\ConfigContainer;

class ConfigHelper extends Helper
{
    protected $config;
    
    public function __construct($configArray)
    {
        $this->config = $this->build($configArray);
    }

    public function get($key)
    {
        return $this->config->get($key);
    }

    public function getName()
    {
        return 'config';
    }
    
    protected function build(array $config)
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
}
