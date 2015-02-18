<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 9/4/2014
 * Time: 12:23 PM
 */

namespace Zone\Core\Component\Wordpress;


class Rewrite {
    private $rules = array();

    function add($regex,$path){
        $this->rules[$regex] = $path;
    }

    function register(){
        global $wp_rewrite;
        foreach($this->rules as $regex => $redirect){
            $wp_rewrite->non_wp_rules[$regex] = $redirect;
        }
    }
} 