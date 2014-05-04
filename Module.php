<?php
namespace ZdLayouts;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature;
use Zend\Mvc\MvcEvent;

class Module implements Feature\BootstrapListenerInterface
{
    public function onBootstrap(EventInterface $e)
    {
        $eventManager = $e->getApplication()->getEventManager();        
        $eventManager->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', MvcEvent::EVENT_DISPATCH, array($this, 'onDispatch'), 100);
    }
    
    public function onDispatch(MvcEvent $e)
    {
        $application    = $e->getApplication();
        $serviceManager = $application->getServiceManager();
        $config         = $serviceManager->get('Config');
        
        if(!isset($config['module_layouts'])) {
            return;
        }
        
        $controller = $e->getTarget();
        $config     = $config['module_layouts'];
        
        if(isset($config['routes'])) {
            $routeName = $e->getRouteMatch()->getMatchedRouteName();
            
            if(isset($config['routes'][$routeName])) {
                $controller->layout($config['routes'][$routeName]);
                return;
            }
            
            foreach($config['routes'] as $route => $layout) {
                if(substr($route, 0, -2) == substr($routeName, 0, strpos($route, '/*'))) {
                    $controller->layout($layout);
                    return;
                }
            }
        }       
            
        $class     = get_class($controller);
        $namespace = substr($class, 0, strpos($class, '\\'));
   
        if(isset($config[$namespace])) {
            $controller->layout($config[$namespace]);
        }
    }
}
