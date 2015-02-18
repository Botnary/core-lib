<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 12/3/2014
 * Time: 4:02 PM
 */

namespace Zone\Core\Component\Service;


use Zone\Core\Component\DependencyInjection\ContainerAware;
use Zone\Core\Component\DependencyInjection\ContainerInterface;
use Zone\Core\Component\Doctrine\DoctrineManager;
use Zone\Core\Component\Options\Options;

abstract class ServiceComponent extends ContainerAware implements IServiceInterface
{
    private $configPath;

    function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    /**
     * @return mixed
     */
    public function getConfigPath()
    {
        return $this->application()->getWorkingPath() . '/Config/';
    }

    /**
     * @param mixed $configPath
     */
    public function setConfigPath($configPath)
    {
        $this->configPath = $configPath;
    }

    /**
     * @throws \Exception
     * @return DoctrineManager
     */
    public function getDoctrine()
    {
        if (!$this->container->has('doctrine')) {
            throw new \Exception('The DoctrineBundle is not registered in your application.');
        }

        return $this->container->get('doctrine');
    }

    /**
     * @return Options
     * @throws \Exception
     */
    function getOption()
    {
        if (!$this->container->has('options')) {
            throw new \Exception('Options class not registered for this application.');
        }
        return $this->container->get('options');
    }

    /**
     * @return \Zone\Core\Application\IApplication
     * @throws \Exception
     */
    public function application()
    {

        if (!$this->container->has('application')) {
            throw new \Exception('Application not registered.');
        }
        return $this->container->get('application');
    }
}