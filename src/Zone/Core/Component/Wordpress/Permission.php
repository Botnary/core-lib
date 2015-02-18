<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 9/5/2014
 * Time: 4:05 PM
 */

namespace Zone\Core\Component\Wordpress;


use Zone\Core\Component\DependencyInjection\ContainerAware;

class Permission extends ContainerAware
{

    function __construct($container)
    {
        $this->container = $container;
    }

    public function isAllowed()
    {
        $access = false;
        for ($i = 0; $i < func_num_args(); $i++) {
            if (user_can($this->getUser()->ID, func_get_arg($i))) {
                $access = true;
                break;
            }
        }
        return $access;
    }

    public function userCan($id, $caps = '...')
    {
        $can = false;
        $caps = func_get_args();
        array_shift($caps);
        foreach ($caps as $cap) {
            if (user_can($id, $cap)) {
                $can = true;
                break;
            }
        }
        return $can;
    }

    /**
     * @return object
     * @throws \Exception
     */
    public function getUser()
    {
        if (!$this->container->has('user')) {
            throw new \Exception('User not registered in your application.');
        }
        return $this->container->get('user');
    }

    public function isOnline()
    {
        return $this->getUser()->ID > 0;
    }
} 