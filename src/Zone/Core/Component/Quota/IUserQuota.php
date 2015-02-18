<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 1/22/2015
 * Time: 11:44 AM
 */

namespace Zone\Core\Component\Quota;


interface IUserQuota
{
    public function check(IUserQuotaStrategy $strategy);
}