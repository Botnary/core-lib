<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 1/22/2015
 * Time: 11:42 AM
 */

namespace Zone\Core\Component\Quota;


class UnlimitedQuota implements IUserQuota
{
    public $isFullPackage = true;

    public function check(IUserQuotaStrategy $strategy)
    {
        return true;
    }
}