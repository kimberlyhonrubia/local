<?php namespace SystemCore\Exceptions;

/**
 * SystemCore\Exceptions\HttpNotFound
 *
 * Handle Your Data for Saving/Downloading from
 * URL, File Object Instance or Base64Encode image.
 *
 * @package SystemCore\Exceptions\HttpNotFound
 * @author  Anthony Pillos <dev.anthonypillos@gmail.com>
 * @version v1
**/

class HttpNotFound extends Base {

	protected $message = 'Ooops Http Not Found';  // Default Exception message
    protected $code    = 404;                     // Default User-defined exception code
}