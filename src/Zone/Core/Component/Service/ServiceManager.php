<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 12/3/2014
 * Time: 4:02 PM
 */

namespace Zone\Core\Component\Service;


use Symfony\Component\Yaml\Parser;
use Zone\Core\Component\DependencyInjection\ContainerInterface;

class ServiceManager
{
    private $collection = array();
    private $container;
    private $workingPath;

    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->workingPath = $this->container->get('application')->getWorkingPath();
    }

    function register($serviceGroup)
    {
        $parser = new Parser();
        $services = $parser->parse(file_get_contents($this->workingPath . '/Config/services.yaml'));
        foreach ($services[$serviceGroup]['services'] as $service) {
            $serviceInstance = $service['class'];
            $this->registerService(new $serviceInstance($this->container));
        }
    }

    private function registerService(IServiceInterface $component)
    {
        $this->collection[] = $component;
        $component->setConfigPath($this->workingPath . '/Config/');
        $component->setup();
    }

    /**
     * @return IServiceInterface[]
     */
    public function getCollection()
    {
        return $this->collection;
    }
} 