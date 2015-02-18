<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 8/8/2014
 * Time: 2:52 PM
 */

namespace Zone\Core\Component\Doctrine\Extensions;

use \Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use \Doctrine\ORM\Mapping\ClassMetadataInfo;

class TablePrefix {
    protected $prefix = '';

    public function __construct($prefix)
    {
        $this->prefix = (string) $prefix;
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();
        $classMetadata->setTableName($this->prefix . $classMetadata->getTableName());
        foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
            if ($mapping['type'] == ClassMetadataInfo::MANY_TO_MANY) {
                $mappedTableName = $classMetadata->associationMappings[$fieldName]['joinTable']['name'];
                $classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix . $mappedTableName;
            }
        }
    }
} 