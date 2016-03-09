<?php namespace SystemCore\Exceptions;

/**
 * SystemCore\Exceptions\FileNotFound
 * Handle File that Does not Exists
 *
 * @package SystemCore\Exceptions\FileNotFound
 * @author  Anthony Pillos <dev.anthonypillos@gmail.com>
 * @version v1
**/

class FileNotFound extends Base {

	protected $message = 'Ooops File Not Found';  // Default Exception message
    protected $code    = 404;                    // Default User-defined exception code
}