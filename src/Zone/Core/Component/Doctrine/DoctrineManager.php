<?php
/**
 * Created by IntelliJ IDEA.
 * User: botnari
 * Date: 2014-08-16
 * Time: 5:59 PM
 */

namespace Zone\Core\Component\Doctrine;

use Zone\Core\Component\DependencyInjection\ContainerAware;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Zone\Core\Component\Doctrine\Extensions\TablePrefix;


class DoctrineManager extends ContainerAware{
    private $manager;
    private $isDevMode;

    /**
     * @param \Zone\Core\Component\Database\Connector $connector
     */
    function __construct($connector)
    {
        $this->isDevMode = true;
        $dbParams = array(
            'driver' => $connector->getDriver(),
            'user' => $connector->getDbUser(),
            'password' => $connector->getDbPassword(),
            'dbname' => $connector->getDbName(),
            'host'=>$connector->getDbHost(),
        );
        $evm = new \Doctrine\Common\EventManager;
        $tablePrefix = new TablePrefix($connector->getTablePrefix());
        $evm->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, $tablePrefix);

        $config = Setup::createAnnotationMetadataConfiguration($connector->getEntityLocations(), $this->isDevMode);
        //$config->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());
        //$config->setSQLLogger(new \Zone\Core\Component\Doctrine\Logger\ApacheSQLLogger());
        $config->addCustomStringFunction('CONCAT_WS', '\Zone\Core\Component\Doctrine\Extensions\Mysql\ConcatWs');
        $config->addCustomStringFunction('DATE', '\Zone\Core\Component\Doctrine\Extensions\Mysql\Date');
        $config->addCustomStringFunction('DAY', '\Zone\Core\Component\Doctrine\Extensions\Mysql\Day');
        $config->addCustomStringFunction('MONTH', '\Zone\Core\Component\Doctrine\Extensions\Mysql\Month');
        $config->addCustomStringFunction('YEAR', '\Zone\Core\Component\Doctrine\Extensions\Mysql\Year');
        $config->setProxyDir($connector->getProxyDir());
        $config->setAutoGenerateProxyClasses(true);
        $this->manager = EntityManager::create($dbParams, $config, $evm);
    }

    /**
     * @return boolean
     */
    public function isDevMode()
    {
        return $this->isDevMode;
    }

    /**
     * @return EntityManager
     */
    public function getManager()
    {
        return $this->manager;
    }
}