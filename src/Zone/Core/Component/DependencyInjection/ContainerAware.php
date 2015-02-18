<?php
/**
 * Created by IntelliJ IDEA.
 * User: botnari
 * Date: 2014-08-16
 * Time: 5:32 PM
 */
namespace Zone\Core\Component\DependencyInjection;

class ContainerAware {
    const paged = 10;
    /**
     * @var  ContainerInterface
     */
    protected $container;

    /**
     * Sets the Container associated with this Controller.
     * @param ContainerInterface $container A ContainerInterface instance
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
} 