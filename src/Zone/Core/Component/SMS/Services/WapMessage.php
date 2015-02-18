<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 11/28/2014
 * Time: 12:17 PM
 */

namespace Zone\Core\Component\SMS\Services;


class WapMessage extends SmsMessage
{
    public $url;
    public $validity = '172800000';
} 