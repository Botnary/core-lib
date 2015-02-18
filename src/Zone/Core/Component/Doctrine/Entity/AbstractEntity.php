<?php
/**
 * Created by PhpStorm.
 * User: botnari
 * Date: 2014-08-08
 * Time: 10:46 PM
 */

namespace Zone\Core\Component\Doctrine\Entity;


use Zone\Core\Util\Helpers\ArrayHelper;

abstract class AbstractEntity implements EntityInterface
{
    /**
     * @return array
     */
    abstract function toArray();

    protected static function getNameFromReflection($className)
    {
        return sprintf('%s',$className);
    }

    function getHelper(){
        return new ArrayHelper();
    }
}