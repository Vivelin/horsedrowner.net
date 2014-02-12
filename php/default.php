<?php
function error_handler($errno, $errstr, $errfile, $errline ) 
{
    switch ($errno) {
        case E_NOTICE:
        case E_USER_NOTICE:
            Debug::WriteLine("E_NOTICE at " . $errfile . " line " . $errline . ": " . $errstr);
            return true;
    }

    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}

function exception_handler($exception) {
    Debug::WriteLine("Uncaught " . $exception);

    // Screw the output
    ob_end_clean(); 

    // Show a 500 page instead
    try {
        header("HTTP/1.1 500 Internal Server Error");
        include "500.htm";
    }
    catch (Exception $e) {}
    exit();
}

set_error_handler("error_handler");
set_exception_handler("exception_handler");
spl_autoload_register(function ($class) {
    include "php/" . $class . ".class.php";
});
ob_start();
    