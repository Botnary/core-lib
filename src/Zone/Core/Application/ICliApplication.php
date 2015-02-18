<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 12/9/2014
 * Time: 2:45 PM
 */

namespace Zone\Core\Application;


interface ICliApplication {

    function getWorkingPath();

    public function getPermission();
} 