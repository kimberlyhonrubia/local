<?php namespace SystemCore\Exceptions;

/**
 * SystemCore\Exceptions\BaseInterface
 *
 * Inferace Exception that easily extends and modified from
 * the default built-in Exception.
 * 
 * @source: http://php.net/manual/en/language.exceptions.php
 */
interface BaseInterface
{
    /* Protected methods inherited from Exception class */
    public function getMessage();                 // Exception message 
    public function getCode();                    // User-defined Exception code
    public function getFile();                    // Source filename
    public function getLine();                    // Source line
    public function getTrace();                   // An array of the backtrace()
    public function getTraceAsString();           // Formated string of trace
    
    /* Overrideable methods inherited from Exception class */
    public function __toString();                 // formated string for display
    public function __construct($message = null, $code = 0);
}