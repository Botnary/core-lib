<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 1/19/2015
 * Time: 10:29 AM
 */

namespace Zone\Core\Component\Accounting\Tax;


class TaxCalculator implements ITaxStrategy
{

    private $rate;

    public function calcTax($amount)
    {
        $t = ($amount * $this->rate) / 100;
        return round($t * 100) / 100;
    }

    function __construct($rate)
    {
        $this->rate = $rate;
    }
}