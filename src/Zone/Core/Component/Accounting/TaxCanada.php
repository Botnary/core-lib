<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 1/19/2015
 * Time: 10:11 AM
 */

namespace Zone\Core\Component\Accounting;


use Zone\Core\Util\Singleton;

class TaxCanada extends Singleton{
    private $federal;
    private $provincial;

    /**
     * @return float
     */
    public function getFederal()
    {
        return $this->federal;
    }

    /**
     * @return float
     */
    public function getProvincial()
    {
        return $this->provincial;
    }

    /**
     * @param mixed $federal
     */
    public function setFederal($federal)
    {
        $this->federal = $federal;
    }

    /**
     * @param mixed $provincial
     */
    public function setProvincial($provincial)
    {
        $this->provincial = $provincial;
    }

}