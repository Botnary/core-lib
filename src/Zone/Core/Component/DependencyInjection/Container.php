<?php
/**
 * Created by IntelliJ IDEA.
 * User: botnari
 * Date: 2014-08-16
 * Time: 5:46 PM
 */

namespace Zone\Core\Component\DependencyInjection;


class Container implements ContainerInterface
{
    private $services = array();

    /**
     * Sets a service.
     * @param string $id The service identifier
     * @param object $service The service instance
     */
    public function set($id, $service)
    {
        $id = strtolower($id);
        $this->services[$id] = $service;
    }

    /**
     * Gets a service.
     * @param string $id The service identifier
     * @return object The associated service
     */
    public function get($id)
    {
        $id = strtolower($id);
        // re-use shared service instance if it exists
        if (array_key_exists($id, $this->services)) {
            return $this->services[$id];
        }
        return null;
    }

    /**
     * Returns true if the given service is defined.
     * @param string $id The service identifier
     * @return Boolean true if the service is defined, false otherwise
     */
    public function has($id)
    {
        $id = strtolower($id);
        return array_key_exists($id, $this->services);
    }
}