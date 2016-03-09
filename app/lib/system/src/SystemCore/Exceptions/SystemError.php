<?php namespace SystemCore\Exceptions;

/**
 * SystemCore\Exceptions\SystemError
 * Handle default Errors in all throughout the system
 *
 * @package SystemCore\Exceptions\SystemError
 * @author  Anthony Pillos <dev.anthonypillos@gmail.com>
 * @version v1
**/

class SystemError extends Base {

	protected $message = 'System Failure';  // Default Exception message
    protected $code    = 500;               // Default User-defined exception code
}