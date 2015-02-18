<?php
/**
 * Created by IntelliJ IDEA.
 * User: botnari
 * Date: 2014-08-16
 * Time: 5:31 PM
 */

namespace Zone\Core\Component\DependencyInjection;

interface ContainerInterface {
    /**
     * Sets a service.
     * @param string $id      The service identifier
     * @param object $service The service instance
     */
    public function set($id, $service);

    /**
     * Gets a service.
     * @param string $id The service identifier
     * @return object The associated service
     */
    public function get($id);

    /**
     * Returns true if the given service is defined.
     * @param string $id The service identifier
     * @return Boolean true if the service is defined, false otherwise
     */
    public function has($id);

} 