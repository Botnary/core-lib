<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 1/19/2015
 * Time: 10:23 AM
 */

namespace Zone\Core\Component\Accounting\Tax;


interface ITaxStrategy
{
    function __construct($rate);

    public function calcTax($amount);
}