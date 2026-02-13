<?php
defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\Component;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Component\Router\RouterFactoryInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

return new class implements ServiceProviderInterface {
    public function register(Container $container) {
        $container->registerServiceProvider(new MVCFactory('\\Joomla\\Component\\Crm'));
        $container->registerServiceProvider(new ComponentDispatcherFactory('\\Joomla\\Component\\Crm'));
        $container->registerServiceProvider(new RouterFactory('\\Joomla\\Component\\Crm'));

        $container->set(
            ComponentInterface::class,
            function (Container $container) {
                $component = new Component(
                    $container->get(ComponentDispatcherFactoryInterface::class),
                    $container->get(MVCFactoryInterface::class),
                    $container->get(RouterFactoryInterface::class)
                );
                
                return $component;
            }
        );
    }
};
