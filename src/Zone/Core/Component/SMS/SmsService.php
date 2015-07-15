<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 11/28/2014
 * Time: 10:49 AM
 */

namespace Zone\Core\Component\SMS;


use Zone\Core\Component\SMS\Services\BinaryMessage;
use Zone\Core\Component\SMS\Services\ISmsService;
use Zone\Core\Component\SMS\Services\NexmoService;
use Zone\Core\Component\SMS\Services\SmsMessage;
use Zone\Core\Component\SMS\Services\WapMessage;
use Zone\Core\Util\NetworkCredential;

class SmsService implements ISmsService
{
    const Nexmo = 1;

    private $service;

    function __construct($service, NetworkCredential $credential)
    {
        $validServices = array(self::Nexmo);
        if (!in_array($service, $validServices)) {
            throw new \Exception('Invalid SMS service provider!');
        }
        switch ($service) {
            case self::Nexmo:
                $this->service = new NexmoService($credential);
                break;
        }
    }

    /**
     * @param Services\SmsMessage $message
     * @return bool
     */
    function send(SmsMessage $message)
    {
        return $this->service->sendText($message);
    }

    function sendText(SmsMessage $message)
    {
        return $this->service->sendText($message);
    }

    function sendBinary(BinaryMessage $message)
    {
        return $this->service->sendBinary($message);
    }

    function sendWapPush(WapMessage $message)
    {
        return $this->service->sendWapPush($message);
    }
}