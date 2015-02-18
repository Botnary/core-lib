<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 8/8/2014
 * Time: 4:24 PM
 */

namespace Zone\Core\Util;


class RouteParser {
    private $requestType;
    private $controller;
    private $path;
    private $conditions;

    function __construct($path, $controller, $requestType = array('GET'), $conditions = array())
    {
        $this->path = $path;
        $this->controller = $controller;
        $this->requestType = $requestType;
        $this->conditions = $conditions;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param mixed $class
     */
    public function setController($class)
    {
        $this->controller = $class;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        if($this->hasParam()){
            return $this->parsed();
        }
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return array
     */
    public function getRequestType()
    {
        return $this->requestType;
    }

    /**
     * @param mixed $requestType
     */
    public function setRequestType($requestType)
    {
        $this->requestType = $requestType;
    }

    function hasParam(){
        return strstr($this->path,'{');
    }

    private function parsed()
    {
        return str_replace(array("{","}"),array(":",""),$this->path);
    }

    public function getConditions()
    {
        return $this->conditions;
    }

    function getControllerInstance(){
        $split = explode(':',$this->getController());
        return $split[0];
    }
    function getControllerMethod(){
        $split = explode(':',$this->getController());
        return $split[1];
    }
} 