<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 1/22/2015
 * Time: 11:33 AM
 */

namespace Zone\Core\Component\Quota;


class UserQuotaFactory implements IQuotaFactory
{

    public function create($plan)
    {
        switch ($plan) {
            case Plan::UNLIMITED:
                return new UnlimitedQuota();
                break;
        }
    }
}