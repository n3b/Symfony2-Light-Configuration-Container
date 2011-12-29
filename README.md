light symfony2 bundle configuration container

fully refactored

    Symfony/
        app/
        src/
            YourBundle/
                ...
                DependencyInjection/
                    YourBundleExtension.php
                ...
                Resources/
                    config/
                        config.yml
                        services.yml
                        ...
                    ...
                ...
        ...
        vendor/
            n3b/
                Bundle/
                    Util/
                        ConfigContainer.php
            ...


#YourBundleExtension.php

    namespace YourBundle\DependencyInjection;

    use Symfony\Component\DependencyInjection\ContainerBuilder;
    use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
    use Symfony\Component\HttpKernel\DependencyInjection\Extension;
    use Symfony\Component\Config\FileLocator;

    class YourBundleExtension extends Extension
    {

        public function load(array $configs, ContainerBuilder $container)
        {
            $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
            $loader->load('services.yml');
            $loader->load('config.yml');
        }

        public function getAlias()
        {
            return 'your_bundle';
        }
    }

#config.yml

    parameters:
      my_bundle_config:
        data:
          DataOne: dummy
          foo: bar
        multilevel:
            data:
                inside:
                    here: done!

#services.yml

    services:
      config:
        class:                                YourBundle\Templating\Helper\ConfigHelper
        arguments:
          config:                             '%my_bundle_config%'
        tags:
          - { name: templating.helper, alias: config }


and now you can inject the 'config' service into all of your services, or call it from view

    $view['config']->get('data.foo') // => 'bar'
    $view['config']->get('multilevel.data.inside.here') // => 'done!'
    $view['config']->get('data')->toArray() // => array('DataOne' => 'dummy', 'foo' => 'bar')
