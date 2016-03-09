<?php

/*
|--------------------------------------------------------------------------
| Application Common Functions
|--------------------------------------------------------------------------
|
| Here you may used different methods that can be used in your controllers,
| blade templates directly. If you have something to add here and might be
| usefull for the team, feel free to add it. 
| And Let the team knows it. Cheers!
|
| @package System Core / functions.php
| @author  Anthony Pillos <dev.anthonypillos@gmail.com>
| @version v1
*/
 

/**
* pre() 
* short hand for printing array/string data
*
* @return array/string
**/
if(!function_exists('pre')){
    function pre($str) {
        echo '<pre/>';
        return print_r($str);
    }
}

/**
* mlocale()
* get current locale with default to en
*
* @return void
**/
if(!function_exists('mlocale')){
    function mlocale() {
        return Session::get('locale', 'en');
    }
}

/**
* cnf()
* Get system config, best to used in templates and in controller
*
* @return void
**/
if(!function_exists('cnf')){
    function cnf($name) {
      return Config::get($name);
    }
}

/**
* build_path()
* Create File path from array collections
*
* @return string
**/
if(!function_exists('build_path')){
    function build_path(array $pathName = array()) {
      return implode(DIRECTORY_SEPARATOR, $pathName);
    }
}

/**
* t()
* Get current session token
*
* @return void
**/
if(!function_exists('t')){
    function t() {
        return Session::token();
    }
}

/**
* encrypt()
* Encrypt string
*
* @return void
**/
if(!function_exists('encrypt')){
    function encrypt($str) {
       return Crypt::encrypt($str);
    }
}

/**
* decrypt()
* Decrpyt string
*
* @return void
**/
if(!function_exists('decrypt')){
    function decrypt($str) {
        try {
            return Crypt::decrypt($str);
        } catch (Illuminate\Encryption\DecryptException $e) {
            App::abort(404);
        }
    }
}

/**
* divisibleBy()
* get divisible of a number, this method used in magazine finishing
*
* @return void
**/
if(!function_exists('divisibleBy')){
    function divisibleBy($number, $defaultDivisible = 2)
    {
        if($number % $defaultDivisible == 0) {
            return true;
        }
        return false;
    }
}

/**
* removeHiddenCharacterInString()
* Remove Hidden Character, this occured when creating files and this
* might help you to remove hidden character string embed in a file.
*
* @return void
**/
if(!function_exists('removeHiddenCharacterInString')){
    function removeHiddenCharacterInString($str) {
       return preg_replace('/(?!\n)[\p{Cc}]/', '', $str);
    }
}


/**
* generateFileNameFromUrl()
* Extract url/path and return path informations
*
* @return void
**/
if(!function_exists('generateFileNameFromUrl')){
    function generateFileNameFromUrl($url) {
        $path       = @pathinfo($url);
        $ext        = @$path['extension'];
        $basename   = @$path['basename'];
        $fileName   = md5($basename.time().t()).uniqid();
        return [
            'filename'  => $fileName,
            'basename'  => $basename,
            'extension' => $ext
        ];
    }
}


/**
* elixir()
* We used this for getting our js/css file assets and for generating build
* in our productions.
*
* @return void
**/
if (!function_exists('elixir')) {
 
  function elixir($file)
  {
    if(App::environment() == 'production')
    {
        static $manifest = null;
        if (is_null($manifest)) {
          $manifest = json_decode(file_get_contents(public_path() . '/build/rev-manifest.json'), true);
        }
        if (isset($manifest[$file])) {
          return '/build/' . $manifest[$file];
        }
        throw new InvalidArgumentException("File {$file} not defined in asset manifest.");
    }
    return asset($file);
  }
}