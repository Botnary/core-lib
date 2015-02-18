<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 12/3/2014
 * Time: 4:01 PM
 */

namespace Zone\Core\Component\Service;


interface IServiceInterface {
    public function setup();
    public function getConfigPath();
    public function setConfigPath($configPath);
} 