<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 12/9/2014
 * Time: 1:07 PM
 */

namespace Zone\Core\Component\Options;


use Symfony\Component\Yaml\Parser;

class Options
{
    private $options = array();

    function __construct($optionsFile = null)
    {
        if (!$optionsFile || !is_writable($optionsFile)) {
            throw new \Exception("Options files is missing");
        }

        $parser = new Parser();
        $config = $parser->parse(file_get_contents($optionsFile));
        $this->options = $config['options'];
    }

    function __get($name)
    {
        if (isset($this->options[$name])) {
            return $this->options[$name];
        }
        return null;
    }

    function __set($name, $value)
    {
        $exists = $this->__get($name);
        if (!isset($exists)) {
            $this->options[$name] = $value;
        }
    }
}