<?php
/**
 * Created by IntelliJ IDEA.
 * User: botnari
 * Date: 2014-08-16
 * Time: 5:32 PM
 */

namespace Zone\Core\Component\DependencyInjection;

interface ContainerAwareInterface {
    /**
     * Sets the Container.
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null);
} 