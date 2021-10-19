<?php
/**
 * Created by IntelliJ IDEA.
 * User: botnari
 * Date: 2014-08-16
 * Time: 5:56 PM
 */

namespace Zone\Core\Controller;


use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Zone\Core\Component\DependencyInjection\ContainerAware;
use Zone\Core\Component\Doctrine\DoctrineManager;
use Zone\Core\Component\Options\Options;
use Zone\Core\Component\Templating\Template;
use Zone\Core\Component\Quota\IUserQuota;

class Controller extends ContainerAware
{
    private $_logger;

    function __construct($container)
    {
        $this->setContainer($container);
        $this->_logger = new Logger('Gestion Minute');
        $this->_logger->pushHandler(new ErrorLogHandler());
        $this->_logger->pushHandler(new StreamHandler('app.log', Logger::WARNING));
        $this->container->set('_logger', $this->_logger);
    }

    /**
     * @throws \Exception
     * @return DoctrineManager
     */
    public function getDoctrine()
    {
        if (!$this->container->has('doctrine')) {
            throw new \Exception('The DoctrineBundle is not registered in your application.');
        }

        return $this->container->get('doctrine');
    }

    /**
     * @return ServerRequestInterface
     */
    public function getRequest()
    {
        return $this->application()->getSlim()->request();
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->application()->getSlim()->response();
    }

    /**
     * @return \Zone\Core\Application\IApplication
     * @throws \Exception
     */
    public function application()
    {

        if (!$this->container->has('application')) {
            throw new \Exception('Application not registered.');
        }
        return $this->container->get('application');
    }

    /**
     * @return object
     * @throws \Exception
     */
    public function getUser()
    {
        if (!$this->container->has('user')) {
            throw new \Exception('User not registered in your application.');
        }
        return $this->container->get('user');
    }

    public function render($view, $params = array())
    {
        if (!$this->container->has('templating')) {
            throw new \Exception('Templating not registered in your application.');
        }
        /** @var $template Template */
        $template = $this->container->get('templating');
        return $template->render($view, $params);
    }

    /**
     * @return \wpdb
     * @throws \Exception
     */
    public function wordpressDb()
    {

        if (!$this->container->has('wpdb')) {
            throw new \Exception('WPDB not registered.');
        }
        return $this->container->get('wpdb');
    }

    /**
     * @return Options
     * @throws \Exception
     */
    function getOption()
    {
        if (!$this->container->has('options')) {
            throw new \Exception('Options class not registered for this application.');
        }
        return $this->container->get('options');
    }

    function getLogger()
    {
        return $this->_logger;
    }

    /**
     * @return EventDispatcher
     * @throws \Exception
     */
    function getEventDispatcher()
    {
        if (!$this->container->has('dispatcher')) {
            throw new \Exception('EventDispatcher is not registered for this application.');
        }
        return $this->container->get('dispatcher');
    }

    /**
     * @return IUserQuota
     * @throws \Exception
     */
    function getUserPlan()
    {
        if (!$this->container->has('user_plan')) {
            throw new \Exception('A user plan is not registered for this application.');
        }
        return $this->container->get('user_plan');
    }


    /**
     * @return \Swift_Mailer
     * @throws \Exception
     */
    function getMailer()
    {
        if (!$this->container->has('swift_mailer')) {
            throw new \Exception('A Swift_Mailer is not registered for this application.');
        }
        return $this->container->get('swift_mailer');
    }
}