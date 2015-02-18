<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 1/22/2015
 * Time: 11:34 AM
 */

namespace Zone\Core\Component\Quota;


interface IQuotaFactory {
    public function create($plan);
}