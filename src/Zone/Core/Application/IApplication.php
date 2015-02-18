<?php
/**
 * Created by IntelliJ IDEA.
 * User: botnari
 * Date: 2014-08-16
 * Time: 6:35 PM
 */

namespace Zone\Core\Application;

interface IApplication {
    /**
     * @return \Slim\Slim
     */
    function getSlim();

    function getWorkingPath();

    public function getPermission();
}