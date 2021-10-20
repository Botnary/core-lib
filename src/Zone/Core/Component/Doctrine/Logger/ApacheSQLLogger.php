<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 12/8/2014
 * Time: 4:41 PM
 */

namespace Zone\Core\Component\Doctrine\Logger;


use Doctrine\DBAL\Logging\SQLLogger;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;

class ApacheSQLLogger implements  SQLLogger {
    private $_logger;

    function __construct()
    {
        $this->_logger = new Logger('Gestion Minute');
        $this->_logger->pushHandler(new ErrorLogHandler());
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        return $this->_logger;
    }

    /**
     * {@inheritdoc}
     */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        $this->getLogger()->debug($sql,array($params, $types));
    }

    /**
     * {@inheritdoc}
     */
    public function stopQuery()
    {
    }
} 