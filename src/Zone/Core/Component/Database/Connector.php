<?php
/**
 * Created by IntelliJ IDEA.
 * User: botnari
 * Date: 2014-08-16
 * Time: 6:12 PM
 */

namespace Zone\Core\Component\Database;


class Connector implements ConnectorInterface{

    private $name;
    private $host;
    private $user;
    private $password;
    private $port;
    private $driver;
    private $entityLocations = array();
    private $proxyDir;
    private $prefix;

    /**
     * @param $name
     * @return $this
     */
    function setDbName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    function getDbName()
    {
        return $this->name;
    }

    /**
     * @param $host
     * @return $this
     */
    function setDbHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return string
     */
    function getDbHost()
    {
        return $this->host;
    }

    /**
     * @param $user
     * @return $this
     */
    function setDbUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    function getDbUser()
    {
        return $this->user;
    }

    /**
     * @param $password
     * @return $this
     */
    function setDbPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    function getDbPassword()
    {
        return $this->password;
    }

    /**
     * @param $port
     * @return $this
     */
    function setDbPort($port)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return integer
     */
    function getDbPort()
    {
        return $this->port;
    }

    /**
     * @param $driver
     * @return ConnectorInterface
     */
    function setDriver($driver)
    {
        $this->driver = $driver;
        return $this;
    }

    /**
     * @return mixed
     */
    function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param $location
     * @return ConnectorInterface
     */
    function addEntityLocation($location)
    {
        $this->entityLocations[] = $location;
        return $this;
    }

    /**
     * @return array
     */
    function getEntityLocations()
    {
        return $this->entityLocations;
    }

    /**
     * @param $dir
     * @return ConnectorInterface
     */
    function setProxyDir($dir)
    {
        $this->proxyDir = $dir;
        return $this;
    }

    /**
     * @return string
     */
    function getProxyDir()
    {
        return $this->proxyDir;
    }

    function getTablePrefix()
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }
}