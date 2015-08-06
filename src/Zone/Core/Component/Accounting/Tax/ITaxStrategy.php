<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 1/19/2015
 * Time: 10:23 AM
 */

namespace Zone\Core\Component\Accounting\Tax;


use Che\Math\Decimal\Decimal;

interface ITaxStrategy
{
    function __construct(Decimal $rate);

    public function calcTax(Decimal $amount);
}