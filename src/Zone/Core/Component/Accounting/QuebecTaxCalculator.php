<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 9/3/2014
 * Time: 12:58 PM
 */

namespace Zone\Core\Component\Accounting;

use Che\Math\Decimal\Decimal;

class QuebecTaxCalculator
{
    private $amount;
    private $tps;
    private $tvq;
    private $context;

    function __construct($data = array())
    {
        $this->amount = new Decimal($data['amount']);
        $this->tps = new Decimal($data['tps']);
        $this->tvq = new Decimal($data['tvq']);
        $this->context = new Tax\TaxContext($this->amount);
    }

    function getTps()
    {
        $this->context->setTaxStrategy(new Tax\TaxCalculator($this->tps));
        return $this->context->calculateTax();
    }

    function getTvq()
    {
        $this->context->setTaxStrategy(new Tax\TaxCalculator($this->tvq));
        return $this->context->calculateTax();
    }

    function getTotalWithTax()
    {
        $total = $this->amount + $this->getTps() + $this->getTvq();
        return round($total * 100) / 100;
    }

} 