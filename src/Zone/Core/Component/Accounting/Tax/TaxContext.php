<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 1/19/2015
 * Time: 10:25 AM
 */

namespace Zone\Core\Component\Accounting\Tax;


class TaxContext
{
    private $taxStrategy;
    private $amount;

    function __construct($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @param ITaxStrategy $taxStrategy
     */
    public function setTaxStrategy($taxStrategy)
    {
        $this->taxStrategy = $taxStrategy;
    }

    public function calculateTax()
    {
        return $this->taxStrategy->calcTax($this->amount);
    }

}