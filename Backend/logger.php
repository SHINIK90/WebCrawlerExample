<?php

function logMsg($message) {
    $logfile = 'log.txt';
    $time = date('Y-m-d H:i:s');

    //get info from where the log was called
    $backtrace = debug_backtrace();
    $caller = $backtrace[0];
    $caller_file = isset($caller['file']) ? basename($caller['file']) : 'Unknown';
    $caller_line = isset($caller['line']) ? $caller['line'] : '--.';

    $log = "[$time] $caller_file:$caller_line--->> $message\n";
    file_put_contents($logfile, $log, FILE_APPEND);
}
// function getParameter($paramName, $parameters){
//     $param = $parameters[$paramName] ?? null;
//     logMsg($paramName . ": " . $param);
//     return $param;
// }
function getParameter($paramName){
    $param = $_GET[$paramName] ?? null;
    logMsg($paramName . ": " . $param);
    return $param;
}

?>