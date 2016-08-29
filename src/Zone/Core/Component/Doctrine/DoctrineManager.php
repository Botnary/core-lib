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
    private $cache;
    private $connector;

    /**
     * @param \Zone\Core\Component\Database\Connector $connector
     * @param bool $devMode
     * @throws \Doctrine\ORM\ORMException
     */
    function __construct($connector, $devMode = true)
    {
        $this->isDevMode = true;
        $this->connector = $connector;
    }

    function initialize(){
        $dbParams = array(
            'driver' => $this->connector->getDriver(),
            'user' => $this->connector->getDbUser(),
            'password' => $this->connector->getDbPassword(),
            'dbname' => $this->connector->getDbName(),
            'host'=>$this->connector->getDbHost(),
        );
        $evm = new \Doctrine\Common\EventManager;
        $tablePrefix = new TablePrefix($this->connector->getTablePrefix());
        $evm->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, $tablePrefix);

        $config = Setup::createAnnotationMetadataConfiguration($this->connector->getEntityLocations(), $this->isDevMode());
        //$config->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());
        //$config->setSQLLogger(new \Zone\Core\Component\Doctrine\Logger\ApacheSQLLogger());
        $config->addCustomStringFunction('CONCAT_WS', '\Zone\Core\Component\Doctrine\Extensions\Mysql\ConcatWs');
        $config->addCustomStringFunction('DATE', '\Zone\Core\Component\Doctrine\Extensions\Mysql\Date');
        $config->addCustomStringFunction('DAY', '\Zone\Core\Component\Doctrine\Extensions\Mysql\Day');
        $config->addCustomStringFunction('MONTH', '\Zone\Core\Component\Doctrine\Extensions\Mysql\Month');
        $config->addCustomStringFunction('YEAR', '\Zone\Core\Component\Doctrine\Extensions\Mysql\Year');
        $config->setProxyDir($this->connector->getProxyDir());
        $config->setAutoGenerateProxyClasses(true);
        if($this->cache){
            $config->setMetadataCacheImpl($this->cache);
            $config->setQueryCacheImpl($this->cache);
        }
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

    /**
     * @param mixed $cache
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param boolean $isDevMode
     */
    public function setIsDevMode($isDevMode)
    {
        $this->isDevMode = $isDevMode;
    }

    /**
     * @return mixed
     */
    public function getConnector()
    {
        return $this->connector;
    }

    /**
     * @param mixed $connector
     */
    public function setConnector($connector)
    {
        $this->connector = $connector;
    }

}