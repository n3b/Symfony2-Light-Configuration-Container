light symfony2 configuration

how to use it:

/my_bundle_dir/Resources/config/services.yml:

    services:
      n3b_config:
        class:                          ConfigBuilderNamespace\ConfigContainerBuilder
        arguments:
          container:                    '@service_container'
      another_service:
        ...


MyBundleNamespace\DependencyInjection\MyBundleExtension:

    <?php

    namespace MyBundleNamespace\DependencyInjection;

    use Symfony\Component\DependencyInjection\ContainerBuilder;
    use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
    use Symfony\Component\HttpKernel\DependencyInjection\Extension;
    use Symfony\Component\Config\FileLocator;

    class MyBundleExtension extends Extension
    {

        public function load(array $configs, ContainerBuilder $container)
        {
            $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
            $loader->load('services.yml');
            $container->get('n3b_config')->init($configs, $container, $this->getAlias());
        }

        public function getAlias()
        {
            return 'my_bundle';
        }
    }

and now, if you inject this container in your service:

/my_bundle_dir/Resources/config/services.yml:

    services:
      n3b_config:
        class:                          ConfigBuilderNamespace\ConfigContainerBuilder
        arguments:
          container:                    '@service_container'
      blog:
        class:                          n3b\Bundle\Blog\Service\BlogService
        arguments:
          services:
            em:                         '@doctrine.orm.entity_manager'
            templating:                 '@templating'
            security:                   '@security.context'
            router:                     '@router'
            http_kernel:                '@http_kernel'
          config:                       '@n3b_config'

and you have something in your app config file:

/app/config/config.yml:

    ...
    n3b_blog:
        posts_per_page: 10
        max_notices: 5
        twitter:
          articles_translate: false
          app_name: N3b.ru exchange app
          app_key: abcde
    ...

you can get it this way:

n3b\Bundle\Blog\Service\BlogService:

    public function __construct($services, $config)
    {
        var_dump($config->get('n3b_blog'));
    }

result:

    object(n3b\Bundle\Util\Service\Config\ConfigContainer)#4022 (1) {
      ["container":protected]=>
      array(5) {
        ["posts_per_page"]=>
        int(10)
        ["max_notices"]=>
        int(5)
        ["twitter"]=>
        object(n3b\Bundle\Util\Service\Config\ConfigContainer)#4023 (1) {
          ["container":protected]=>
          array(6) {
            ["articles_translate"]=>
            bool(false)
            ["app_name"]=>
            string(19) "N3b.ru exchange app"
            ["app_key"]=>
            string(5) "abcde"
          }
        }
      }

`var_dump($config->get('n3b_blog')->get('twitter'));`:

result:

    object(n3b\Bundle\Util\Service\Config\ConfigContainer)#68 (1) {
      ["container":protected]=>
      array(6) {
        ["articles_translate"]=>
        bool(false)
        ["app_name"]=>
        string(19) "N3b.ru exchange app"
        ["app_key"]=>
        string(5) "abcde"
      }
    }

`var_dump($config->get('n3b_blog')->get('twitter.app_name'));`:

result:

    string(19) "N3b.ru exchange app"

`var_dump($config->get('n3b_blog')->get('twitter')->getArray());`:

result:

    array(6) {
      ["articles_translate"]=>
      bool(false)
      ["app_name"]=>
      string(19) "N3b.ru exchange app"
      ["app_key"]=>
      string(5) "abcde"
    }
