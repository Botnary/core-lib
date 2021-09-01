<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 11/28/2014
 * Time: 10:49 AM
 */

namespace Zone\Core\Component\SMS\Services;


use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Zone\Core\Util\NetworkCredential;

class NexmoService implements ISmsService
{
    private $user;
    private $pass;
    private $server;
    private $_logger;

    function __construct(NetworkCredential $credential)
    {
        $this->user = $credential->getUser();
        $this->pass = $credential->getPassword();
        $this->server = $credential->getUri();
        $this->_logger = new Logger('Nexmo SMS');
        $this->_logger->pushHandler(new ErrorLogHandler());
    }

    /**
     * @return mixed
     */
    public function getLogger()
    {
        return $this->_logger;
    }

    /**
     * Prepare and send new text message.
     */
    private function sendRequest($post)
    {
        $to_nexmo = curl_init($this->server);
        curl_setopt($to_nexmo, CURLOPT_POST, true);
        curl_setopt($to_nexmo, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($to_nexmo, CURLOPT_POSTFIELDS, $post);
        curl_setopt($to_nexmo, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($to_nexmo, CURLOPT_SSL_VERIFYPEER, 0);
        $from_nexmo = curl_exec($to_nexmo);
        curl_close($to_nexmo);
        return str_replace('-', '_', $from_nexmo);
    }

    function sendText(SmsMessage $message)
    {
        $from = $message->fromPhone;
        if (!is_numeric($from))
            $from = $from; //Must be UTF-8 Encoded if not a continuous number
        $text = $message->message; //Must be UTF-8 Encoded
        $from = urlencode($from); // URL Encode
        $text = urlencode($text); // URL Encode
        $post = 'username=' . $this->user . '&password=' . $this->pass . '&from=' . $from . '&to=' . $message->toPhone . '&text=' . $text;
        $request = json_decode($this->sendRequest($post));
        $this->getLogger()->addDebug('SMS: send status', array($request));
        $sent = true;
        foreach ($request->messages as $message) {
            if ($message->status == 1) {
                $this->getLogger()->addDebug('SMS: You have exceeded the submission capacity allowed on this account, please back-off and retry', array($request));
                $sent = false;
            } elseif ($message->status > 1) {
                $sent = false;
            }
        }
        return $sent;
    }

    function sendBinary(BinaryMessage $message)
    {
        //Binary messages must be hex encoded
        $body = bin2hex($message->message); //Must be hex encoded binary
        $udh = bin2hex($message->udh); //Must be hex encoded binary
        $post = 'username=' . $this->user . '&password=' . $this->pass . '&from=' . $message->fromPhone . '&to=' . $message->toPhone . '&type=binary&body=' . $body . '&udh=' . $udh;
        $request = json_decode($this->sendRequest($post));
        $sent = true;
        foreach ($request->messages as $message) {
            if ($message->status == 1) {
                $this->getLogger()->addDebug('SMS: You have exceeded the submission capacity allowed on this account, please back-off and retry', array($request));
                $sent = false;
            } elseif ($message->status > 1) {
                $sent = false;
            }
        }
        return $sent;
    }

    function sendWapPush(WapMessage $message)
    {
        //WAP Push title and URL must be UTF-8 Encoded
        $title = utf8_encode($message->message); //Must be UTF-8 Encoded
        $url = utf8_encode($message->url); //Must be UTF-8 Encoded
        $post = 'username=' . $this->user . '&password=' . $this->pass . '&from=' . $message->fromPhone . '&to=' . $message->toPhone . '&type=wappush&url=' . $url . '&title=' . $title . '&validity=' . $message->validity;
        $request = json_decode($this->sendRequest($post));
        $sent = true;
        foreach ($request->messages as $message) {
            if ($message->status == 1) {
                $this->getLogger()->addDebug('SMS: You have exceeded the submission capacity allowed on this account, please back-off and retry', array($request));
                $sent = false;
            } elseif ($message->status > 1) {
                $sent = false;
            }
        }
        return $sent;
    }
}