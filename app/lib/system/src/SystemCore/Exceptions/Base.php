<?php namespace SystemCore\Exceptions;

/**
 * SystemCore\Exceptions\Base
 *
 * Abstract Exceptions that ensures all exceptions are preserved in child classes
 * take a look from source.
 * 
 * @source: http://php.net/manual/en/language.exceptions.php
 */

abstract class Base extends \Exception implements BaseInterface
{
    protected $message = 'Unknown exception';     // Exception message
    private   $string;                            // Unknown
    protected $code    = 0;                       // User-defined exception code
    protected $file;                              // Source filename of exception
    protected $line;                              // Source line of exception
    private   $trace;                             // Unknown

    public function __construct($message = null, $code = 0)
    {
        if (!$message)
            throw new $this($this->message.' '. get_class($this));
        parent::__construct($message, $code);
    }
    
    public function __toString()
    {
        return get_class($this) . " '{$this->message}' in {$this->file}({$this->line})\n"
                                . "{$this->getTraceAsString()}";
    }
}