<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 9/12/2014
 * Time: 2:34 PM
 */

namespace Zone\Core\Util\Helpers;


class ArrayHelper extends AbstractHelper
{
    function collectionToArray($collection)
    {
        $items = array();
        foreach ((array)$collection as $item) {
            if (method_exists($item, 'toArray'))
                $items[] = $item->toArray();
        }
        return $items;
    }

    function arraySearch($array = array(), $callback, $recursive = false)
    {
        foreach ((array)$array as $key => $value) {
            if (call_user_func_array($callback, array($value))) {
                return array($key => $value);
            } else {
                if (is_array($value) && $recursive) {
                    $result = $this->arraySearch($value, $callback);
                    if ($result) {
                        return $result;
                    }
                }
            }
        }
    }
} 