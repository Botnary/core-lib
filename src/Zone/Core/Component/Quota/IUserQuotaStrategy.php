<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 1/22/2015
 * Time: 11:46 AM
 */

namespace Zone\Core\Component\Quota;


interface IUserQuotaStrategy
{
    public function isValid();

    public function setQuota(IUserQuota $quota);
}