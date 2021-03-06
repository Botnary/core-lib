<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 1/19/2015
 * Time: 10:25 AM
 */

namespace Zone\Core\Component\Accounting\Tax;


use Che\Math\Decimal\Decimal;

class TaxContext
{
    private $taxStrategy;
    private $amount;

    function __construct(Decimal $amount)
    {
        $this->amount = $amount;
    }

    /**
     * @param ITaxStrategy $taxStrategy
     * @return TaxContext
     */
    public function setTaxStrategy($taxStrategy)
    {
        $this->taxStrategy = $taxStrategy;
        return $this;
    }

    /**
     * @return Decimal
     */
    public function calculateTax()
    {
        return $this->taxStrategy->calcTax($this->amount);
    }

}