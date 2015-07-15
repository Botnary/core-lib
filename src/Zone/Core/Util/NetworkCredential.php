<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * E-mail: my.test@live.ca
 * Date: 7/15/2015
 * Time: 2:42 PM
 */

namespace Zone\Core\Util;


class NetworkCredential
{
    private $user;
    private $password;
    private $uri;

    /**
     * NetworkCredential constructor.
     * @param $name
     * @param $password
     * @param $uri
     */
    public function __construct($name, $password, $uri)
    {
        $this->user = $name;
        $this->password = $password;
        $this->uri = $uri;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param mixed $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

}