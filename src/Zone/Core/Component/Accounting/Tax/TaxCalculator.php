<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 1/19/2015
 * Time: 10:29 AM
 */

namespace Zone\Core\Component\Accounting\Tax;


use Che\Math\Decimal\Decimal;

class TaxCalculator implements ITaxStrategy
{

    private $rate;

    public function calcTax(Decimal $amount)
    {
        return $amount->mul($this->rate)->div(new Decimal("100", 16));
    }

    function __construct(Decimal $rate)
    {
        $this->rate = $rate;
    }
}