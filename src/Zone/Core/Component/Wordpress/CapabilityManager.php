<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 9/4/2014
 * Time: 11:48 AM
 */

namespace Zone\Core\Component\Wordpress;


use Symfony\Component\Yaml\Parser;

class CapabilityManager {
    private $caps = array();
    private $roles = array();
    private $wpRole;

    function __construct()
    {
        global $wp_roles;
        if (!isset($wp_roles)) {
            $wp_roles = new \WP_Roles();
        }
        $this->wpRole = $wp_roles;
        $this->wpRole->use_db = true;
    }

    function readRoles($location){
        $parser = new Parser();
        $roles = $parser->parse(file_get_contents($location));
        foreach($roles as $role){
            $this->addRole($role);
        }
    }

    function readPermissions($location){
        $parser = new Parser();
        $permissions = $parser->parse(file_get_contents($location));
        foreach($permissions as $permission){
            $this->addCap($permission);
        }
    }

    private function addRole($role){
        if(!in_array($role,$this->roles)){
            $this->roles[] = $role;
        }
    }
    private function addCap($cap){
        if(!in_array($cap,$this->caps)){
            $this->caps[] = $cap;
        }
    }

    function register(){
        foreach($this->roles as $role){
            $this->wpRole->add_role(strtolower($role),$role);
        }
        $administrator = $this->wpRole->get_role('administrator');
        foreach($this->caps as $cap){
            if (!$administrator->has_cap($cap)){
                $this->wpRole->add_cap('administrator', $cap);
            }
        }
    }
} 