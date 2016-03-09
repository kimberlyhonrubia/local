<?php namespace SystemCore\Traits;

/**
 * SystemCore\Traits\Logger
 *
 * Handle Logger for our applications anywhere in our system.
 *
 * @package SystemCore\Traits\Logger
 * @author  Anthony Pillos <dev.anthonypillos@gmail.com>
 * @version v1
**/

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

trait Logger {
	
    private $logMethod;
    private $logType;


    /**
    * getIPAddress()
    * Get IP ADDRESS of the current Request...
    *
    * @return string
    * @access  public
    **/
	public function getIPAddress()
    {
        return sprintf(' - IPADDRESS:(%s) -> ',Request::getClientIp());
    }

    /**
    * log()
    * Default set, is current method which the log()
    * But you can pass any value here.. or a Class or Any Route or Controller. etc.
    *
    * @param    (string) $method
    * @return   (string) $logType
    * @access  public
    **/
    public function log($method = __METHOD__, $logType = 'info')
    {
        $this->logMethod = $method;
        $this->logType   = $logType;
        return $this;
    }

    /**
    * log()
    * This will be your message that will be shown in your logs.
    * it can be array, json or string.. 
    *
    * @param    (string) $logMessage
    * @access  public
    **/
    public function msg( $logMessage = '')
    {
        if(is_array($logMessage))
            $logMessage = var_export($logMessage,true);

        if(is_object($logMessage))
            $logMessage = json_encode($logMessage);

        $logType = $this->logType;
        return Log::$logType($this->logMethod.$this->getIPAddress().$logMessage);
    }
}