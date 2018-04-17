<?php
class errorHandler
{
    public $ini;
    public function __construct($_ini)
    {
        $this->ini = $ini;
        // set to the user defined error handler
        set_error_handler($this->errorHandler());
        return 'test';
    }

    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        //don't display error if no error number
        if (!(error_reporting() & $errno)) {
            return;
        }
        $message = '';
        //display errors according to the error number
        switch ($errno)
        {
            case E_USER_ERROR:
                $message .= "<b>ERROR</b> [$errno] $errstr<br />\n";
                $message .= "  Fatal error on line $errline in file $errfile";
                $message .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
                $message .= "Aborting...<br />\n";
                print $message;
                exit(1);
                break;

            case E_USER_WARNING:
                $message .= "<b>WARNING</b> [$errno] $errstr<br />\n";
                break;

            case E_USER_NOTICE:
                $message .= "<b>NOTICE</b> [$errno] $errstr<br />\n";
                break;

            default:
                $message .= "<b>UNKNOWN ERROR</b> [$errno] $errstr<br />\n";
                break;
        }
        print $message;
        //don't execute PHP internal error handler
        return true;
    }
}
?>