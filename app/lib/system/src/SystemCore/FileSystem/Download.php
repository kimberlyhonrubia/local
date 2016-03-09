<?php namespace SystemCore\FileSystem;

/**
 * SystemCore\FileSystem\Download
 *
 * Handle Your Data for Saving/Downloading from
 * URL, File Object Instance or Base64Encode image.
 *
 * @package SystemCore\FileSystem\Download
 * @author  Anthony Pillos <dev.anthonypillos@gmail.com>
 * @version v1
**/

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use SystemCore\Exceptions\FileNotFound;
use SystemCore\Exceptions\HttpNotFound;
use SystemCore\Traits\Logger;


class Download {

    /* @method $this->log()->msg() */
    use Logger;

	private static $_instance = null;

    // Throw Exception Messages
    CONST START_TO_DL_FILE      = 'Download File from this path/url: %s saving to this path: %s';
    CONST FAILED_TO_SAVE_URL    = 'Failed to save ("%s"), File might be broken or not well-formed!';
    CONST FORCE_DOWNLOAD        = 'File Path %s to force download and save to customer computer.';
    CONST FORCE_FAILED_TO_DL    = 'Failed to download this file: %s';
    CONST HTTT_NOT_EXISTS       = 'The http Url (%s) you given does not exists from the server or the data is corrupted';

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
	 * Download helper to download files in chunks and save it.
	 * 
	 * @param  string  $srcName      Source Path/URL to the file you want to download
	 * @param  string  $dstName      Destination Path to save your file
	 * @param  integer $chunkSize    (Optional) How many bytes to download per chunk (In MB). Defaults to 1 MB.
	 * @param  boolean $returnbytes  (Optional) Return number of bytes saved. Default: true
	 * 
	 * @return integer               Returns number of bytes delivered.
	 */
	public function downloadFile($srcName, $dstName, $chunkSize = 5, $returnbytes = true) {
        $this->log(__METHOD__,'notice')->msg( sprintf(self::START_TO_DL_FILE,$srcName,$dstName) );
		try {

			$this->makedir(dirname($dstName));
			$chunksize = $chunkSize*(1024*1024); // How many bytes per chunk
			$data = '';
			$bytesCount = 0;
			$handle = fopen($srcName, 'rb');
			$fp = fopen($dstName, 'w');
			if ($handle === false) {
				return false;
			}
			while (!feof($handle)) {
				$data = fread($handle, $chunksize);
				fwrite($fp, $data, strlen($data));
				if ($returnbytes) {
				    $bytesCount += strlen($data);
				}
			}
			$status = fclose($handle);
			fclose($fp);
			if ($returnbytes && $status) {
				return $bytesCount; // Return number of bytes delivered like readfile() does.
			}
			return $status;

		} catch (\Exception $e) {
		        $error = [
		              'code'      => $e->getCode(),
		              'message'   => $e->getMessage(),
		              'line'      => $e->getLine(),
		              'file'      => $e->getFile(),
		              'class'     => get_class($e)
		          ];
		        throw new FileNotFound(sprintf(self::FAILED_TO_SAVE_URL,$srcName));
		}
		
	}

	/**
     * Force to download Data
     * 
     * @param  string  $url     URL to the file you want to check
     * @return bool             Returns boolean
     */
    public function forceDownload($path,$unlinkAfterDownload = false) {

        $this->log(__METHOD__,'notice')->msg(sprintf(self::FORCE_DOWNLOAD,$path));
        $fileExist = File::exists($path);
        if($fileExist)
        {
            header("Content-Description: File Transfer"); 
            header("Content-Type: application/octet-stream"); 
            header('Content-Disposition: attachment; filename="'.basename($path).'"');
            readfile($path);

            if($unlinkAfterDownload)
                unlink($path);
            exit;
        }
        throw new FileNotFound(sprintf(self::FORCE_FAILED_TO_DL,$path));
    }

    /**
	 * Check if Http is exist, and not 404.
	 * 
	 * @param  string  $url     URL to the file you want to check
	 * @return bool           	Returns boolean
	 */
	public function is_http_exist($url) {
        $this->log(__METHOD__,'notice')->msg(sprintf('Is http url %s is exists',$url));
		$ch = curl_init(); 
	    curl_setopt($ch, CURLOPT_URL, $url); 
	    curl_setopt($ch, CURLOPT_NOBODY, true); 
	    curl_exec($ch); 
	    $intReturnCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
	    curl_close($ch); 
	    if ($intReturnCode != 200 && $intReturnCode != 302 && $intReturnCode != 304) 
		    throw new HttpNotFound(sprintf(self::HTTT_NOT_EXISTS,$url));
		
		return true;
	}


	/**
	 * Download File From Ftp using Username and Password
	 * 
	 * @param  string  $url     	Url Path that you're going to download
	 * @param  string  $username    FTP username
	 * @param  string  $password    FTP password
	 * @return bool/object          Returns boolean or response object
	 */
	public function downloadFromFtp($url,$username,$password) {
        $this->log(__METHOD__,'notice')->msg(sprintf('Downloading FTP File from this url %s, with username: %s, and password: %password',$url,$username,$password));
		set_time_limit(0);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "".$username.":".$password."");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($httpCode == 404) {
            throw new HttpNotFound(sprintf(self::HTTT_NOT_EXISTS,$url));
        }
        return $response;
	}

	/**
     * makedir()
     *
     * @param (string)  $uploadPath        Path that you want to create your folder
     * @param (array)   $folderArrayName   Arrays of name for recursive folder creation
     * @param (boolean) $cleanDirectory    If folder exists and we set it to true, the folder will be clean
     * @return string
     * @access public
     */
    public function makedir($uploadPath, $folderArrayName = [],$cleanDirectory = false)
    {   
        $name = '';
        if(!empty($folderArrayName))
            $name = implode(DIRECTORY_SEPARATOR, $folderArrayName);

        $uploadPath = $uploadPath.$name;
        if(!File::exists($uploadPath))
            File::makeDirectory($uploadPath,0777,true,true);

        if($cleanDirectory)
        	File::cleanDirectory($uploadPath);

        return $uploadPath;
    }

    /**
     * isValidUrl()
     * 
     *  Check if a url is in a valid format,
     *  We can check also if the url is responsive and can be
     *	download by our system
     *
     * @param (string) $url
     * @param (boolean) $httpResponseCheck
     * @access public
     * @return boolean
     */
    public function isValidUrl($url,$httpResponseCheck = false){
        // first do some quick sanity checks:
        if(!$url || !is_string($url)){
            return false;
        }
        // quick check url is roughly a valid http request: ( http://blah/... ) 
        if( ! preg_match('/^http(s)?:\/\/[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(\/.*)?$/i', $url) ){
            return false;
        }

        if($httpResponseCheck)
        {
        	// the next bit could be slow:
	        if($this->getHttpResponseCode_using_curl($url) != 200){
			// if(getHttpResponseCode_using_getheaders($url) != 200){  // use this one if you cant use curl
	            throw new HttpNotFound(sprintf(self::HTTT_NOT_EXISTS,$url));
	        }
        }
        // all good!
        return true;
    }


    /**
     * getHttpResponseCode_using_curl()
     * 
     *  Check if a url is in a valid format,
     *  We can check also if the url is responsive and can be
     *	download by our system
     *
     * @param (string) $url
     * @param (boolean) $httpResponseCheck
     * @access public
     * @return boolean
     */
    public function getHttpResponseCode_using_curl($url, $followredirects = true){
        // returns int responsecode, or false (if url does not exist or connection timeout occurs)
        // NOTE: could potentially take up to 0-30 seconds , blocking further code execution (more or less depending on connection, target site, and local timeout settings))
        // if $followredirects == false: return the FIRST known httpcode (ignore redirects)
        // if $followredirects == true : return the LAST  known httpcode (when redirected)
        if(! $url || ! is_string($url)){
            return false;
        }
        $ch = @curl_init($url);
        if($ch === false){
            return false;
        }
        @curl_setopt($ch, CURLOPT_HEADER         ,true);    // we want headers
        @curl_setopt($ch, CURLOPT_NOBODY         ,true);    // dont need body
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER ,true);    // catch output (do NOT print!)
        if($followredirects){
            @curl_setopt($ch, CURLOPT_FOLLOWLOCATION ,true);
            @curl_setopt($ch, CURLOPT_MAXREDIRS      ,10);  // fairly random number, but could prevent unwanted endless redirects with followlocation=true
        }else{
            @curl_setopt($ch, CURLOPT_FOLLOWLOCATION ,false);
        }
     	
     	@curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,10);   // fairly random number (seconds)... but could prevent waiting forever to get a result
        // @curl_setopt($ch, CURLOPT_TIMEOUT        ,6);   // fairly random number (seconds)... but could prevent waiting forever to get a result
        // @curl_setopt($ch, CURLOPT_USERAGENT      ,"Mozilla/5.0 (Windows NT 6.0) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1");   // pretend we're a regular browser
        @curl_exec($ch);
        if(@curl_errno($ch)){   // should be 0
            @curl_close($ch);
            return false;
        }
        $code = @curl_getinfo($ch, CURLINFO_HTTP_CODE); // note: php.net documentation shows this returns a string, but really it returns an int
        @curl_close($ch);
        return $code;
    }

    /**
     * getHttpResponseCodeUsingUrl()
     * 
     *  Check if a url is in a valid format,
     *  We can check also if the url is responsive and can be
     *	download by our system
     *
     * @param (string) $url
     * @param (boolean) $httpResponseCheck
     * @access public
     * @return boolean
    */
    public function getHttpResponseCodeUsingUrl($url)
    {   
        $ch = curl_init($url);

	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	    curl_setopt($ch, CURLOPT_HEADER, TRUE);
	    curl_setopt($ch, CURLOPT_NOBODY, TRUE);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

	    $data = curl_exec($ch);
	    $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

	    curl_close($ch);

	    return $size;
    }

}