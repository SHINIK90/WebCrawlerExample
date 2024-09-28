<?php

function logMsg($message) {
    $logfile = 'log.txt';
    $time = date('Y-m-d H:i:s');

    //get info from where the log was called
    $backtrace = debug_backtrace();
    $caller = $backtrace[0];
    $caller_file = isset($caller['file']) ? basename($caller['file']) : 'Unknown';          //get the php file that called the log
    $caller_line = isset($caller['line']) ? $caller['line'] : '--.';                        //get the line where this log was called

    $log = "[$time] $caller_file:$caller_line--->> $message\n";                             //log message structure
    file_put_contents($logfile, $log, FILE_APPEND);
}
function getParameter($paramName){                                                          //helper function to get values from the Get request and log them for debugging
    $param = $_GET[$paramName] ?? null;
    logMsg($paramName . ": " . $param);
    return $param;
}

?>