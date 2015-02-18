<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 9/4/2014
 * Time: 11:25 AM
 */

namespace Zone\Core\Component\Templating;


use Zone\Core\Component\DependencyInjection\ContainerAware;

class Template extends ContainerAware{
    private $processor;

    function __construct($processor)
    {
        $this->processor = $processor;
    }

    /**
     * @return \Zone\Core\Application\IApplication
     * @throws \Exception
     */
    function getApplication(){
        if (!$this->container->has('application')) {
            throw new \Exception('Unknown application.');
        }
        return $this->container->get('application');
    }

    function render($view, $args = array()){
        $path = $this->getApplication()->getWorkingPath();
        $args['_views'] = $path.'/Views';
        $view = explode(':',$view);
        $path = sprintf('%s/Views/%s/%s.tpl',$path,$view[0],$view[1]);
        if(!is_file($path)){
            throw new \Exception(sprintf('View file is missing on path %s',$path));
        }

        switch(get_class($this->processor)){
            case 'Smarty':
                return $this->renderSmarty($path,$args);
                break;
        }
        throw new \Exception("Unknown template processor submitted.");
    }

    function renderSmarty($tpl,$args){
        foreach ($args as $var => $value) {
            $this->processor->assign($var, $value);
        }
        return $this->processor->fetch($tpl);
    }
} 