<?php namespace SystemCore\Http;

/**
 * SystemCore\Http\RequestCurl
 * Handle image/files for rendering in customer browser.
 *
 * @package SystemCore\Http\Image
 * @author  Anthony Pillos <dev.anthonypillos@gmail.com>
 * @version v1
**/

use GuzzleHttp\Client;
use Illuminate\Support\Collection;

class RequestCurl {

	/** @var GuzzleHttp\Client **/
 	protected $client;

 	// @var $isDebug, show data that you send
 	protected $isDebug = false;

	/**
    * __construct()
    * Initialize our Class Here for Dependecy Injection
    *
    * @return void
    * @access  public
    **/
	public function __construct(Client $client)
	{
		$this->client 			= $client;
	}


	/**
    * setHeaders()
    * SetCustom Headers
    *
    * @return void
    * @access  public
    **/
	public function setHeaders( $headerOptions = array() )
	{
		if($headerOptions)
			$this->client->setDefaultOption('headers', $headerOptions);

		return $this;
	}


	/**
    * setCurlOptions()
    * Set Custom Curl Options you want to Add
    *
    * @return void
    * @access  public
    **/
	public function setCurlOptions( $curlOptions = array() )
	{
		if($curlOptions)
			$this->client->setDefaultOption('config/curl', $curlOptions);

		return $this;
	}

	/**
    * setDataFields()
    * Set Data Post Files to be processed when you send request.
    *
    * @return void
    * @access  public
    **/
	public function setDataFields( $dataFields = array(),$isPostData = false)
	{	
		$requestBody = 'query';
		if($isPostData)
			$requestBody = 'body';
			
		if($dataFields)
			$this->client->setDefaultOption($requestBody, $dataFields);

		return $this;
	}

	/**
    * send()
    * Process Final Request and return Http response.
    *
    * @return void
    * @access  public
    **/
	public function send( $url,$requestType = 'get',$arrayResult = false)
	{
		try {

			if($this->isDebug)
				$this->client->setDefaultOption('debug', $this->isDebug);
			
			$response = $this->client->$requestType($url);

			$processResponse = $response->json();
			if( is_string($processResponse))
				$processResponse = json_decode($processResponse, $arrayResult);

			return empty($processResponse) ? ($arrayResult ? [] : new \stdClass) : new Collection($processResponse, $arrayResult);

		} catch (\Guzzle\Http\Exception\BadResponseException $e) {
			pre($e);
		}
	}
}
