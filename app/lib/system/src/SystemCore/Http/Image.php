<?php namespace SystemCore\Http;

/**
 * SystemCore\Http\Image
 * Handle image/files for rendering in customer browser.
 *
 * @package SystemCore\Http\Image
 * @author  Anthony Pillos <dev.anthonypillos@gmail.com>
 * @version v1
**/

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use SystemCore\Exceptions\FileNotFound;
use SystemCore\FileSystem\Mime;

class Image {


	// Throw Exception Messages
    CONST FAILED_BASE64ENCODE      = 'Failed to transform this path (%s) into base64 image, Path Does Not Exists.';

    // @var SystemCore\FileSystem\Mime
	public $mime;


	/**
    * __construct()
    * Initialize our Class Here for Dependecy Injection
    *
    * @return void
    * @access  public
    **/
	public function __construct(Mime $mime)
	{
		$this->mime 	= $mime;
	}

	/**
    * getInstance()
    * Singleton pattern,
    * If you want to instantiate a new instance once 
    *
    * @return object
    * @access  public
    **/ 
	public static function getInstance()
    {
        if (self::$_instance === null)
        	self::$_instance = new self;

        return self::$_instance;
    }

    /**
    * render()
    * Singleton pattern,
    * If you want to instantiate a new instance once 
    *
    * @return object
    * @access  public
    **/
	public function render( $path)
	{
		$path = decrypt( $path );
		if( File::exists($path) )
	        return $this->response($path);
	}

	/**
    * base64EncodedImage()
    * Use it if you want to generate base64 image
    * from File Path.
    *
    * @return object
    * @access  public
    **/
	public function base64EncodedImage($path)
	{
		$path = decrypt( $path );
		if( File::exists($path) ){
			$content = File::get($path);
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($content);
			return $base64;
		}
		throw new FileNotFound(sprintf(self::FAILED_BASE64ENCODE,$path));
	}

	/**
    * response()
    * Handling Image Data for returning proper headers and
    * add caching for the response.
    *
    * @return object
    * @access  protected
    **/
	protected function response( $path )
	{
		$lifetime = 86400;
		$mimeType 	= $this->mime->getType($path);

        if (is_null(@$name))
        	$name = basename($path);
		
        $filetime 	= File::lastModified($path);
        $etag 		= md5($path);
        $time 		= gmdate('r', $filetime);
        $expires 	= gmdate('r', $filetime + $lifetime);
        $length 	= File::size($path);
 
        $headers = [
	            'Content-Disposition' 	=> 'inline; filename="' . $name . '"',
	            'Last-Modified' 		=> $time,
	            'Cache-Control' 		=> 'must-revalidate',
	            'Expires' 				=> $expires,
	            'Pragma' 				=> 'public',
	            'Etag' 					=> $etag,
	        ];

        $headerTest1 = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $time;
        $headerTest2 = isset($_SERVER['HTTP_IF_NONE_MATCH']) && str_replace('"', '', stripslashes($_SERVER['HTTP_IF_NONE_MATCH'])) == $etag;
        if ($headerTest1 || $headerTest2) { //image is cached by the browser, we dont need to send it again
            return Response::make('', 304, $headers);
        }
 
        $headers = array_merge($headers, [
		            'Content-Type' 		=> $mimeType,
		            'Content-Length' 	=> $length,
            		]);
 		
        return Response::make(File::get($path), 200, $headers);
	}

}