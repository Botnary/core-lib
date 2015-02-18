<?php
/**
 * Created by IntelliJ IDEA.
 * User: botnari
 * Date: 2014-08-16
 * Time: 6:12 PM
 */

namespace Zone\Core\Component\Database;


interface ConnectorInterface {
    /**
     * @param $name
     * @return ConnectorInterface
     */
    function setDbName($name);

    /**
     * @return string
     */
    function getDbName();

    /**
     * @param $host
     * @return ConnectorInterface
     */
    function setDbHost($host);

    /**
     * @return string
     */
    function getDbHost();

    /**
     * @param $user
     * @return ConnectorInterface
     */
    function setDbUser($user);

    /**
     * @return string
     */
    function getDbUser();

    /**
     * @param $password
     * @return ConnectorInterface
     */
    function setDbPassword($password);

    /**
     * @return string
     */
    function getDbPassword();

    /**
     * @param $port
     * @return ConnectorInterface
     */
    function setDbPort($port);

    /**
     * @return integer
     */
    function getDbPort();

    /**
     * @param $driver
     * @return ConnectorInterface
     */
    function setDriver($driver);

    /**
     * @return mixed
     */
    function getDriver();

    /**
     * @param $location
     * @return ConnectorInterface
     */
    function addEntityLocation($location);

    /**
     * @return array
     */
    function getEntityLocations();

    /**
     * @param $dir
     * @return ConnectorInterface
     */
    function setProxyDir($dir);

    /**
     * @return string
     */
    function getProxyDir();
    function getTablePrefix();
}