<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 11/28/2014
 * Time: 10:49 AM
 */

namespace Zone\Core\Component\SMS\Services;


interface ISmsService
{
    function sendText(SmsMessage $message);
    function sendBinary(BinaryMessage $message);
    function sendWapPush(WapMessage $message);
} 