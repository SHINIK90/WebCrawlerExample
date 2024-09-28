<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:4200');       //avoids cors policy errors by allowing requests from the angular server in port 4200
header('Access-Control-Allow-Methods: GET');                        //server only spects Get requests
header('Access-Control-Allow-Headers: Content-Type');

include 'filter.php';

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    logMsg('GET REQUEST');

    $option = getParameter('option');                               //use the getParameter function to log the received parameters for debugging and getting their values
    $word_limit = getParameter('word_limit') ?? 5;                  //use null coalesing operator to define default values if needed
    $direction = getParameter('direction') ?? 'desc'; // asc or desc
    $entries = [];

    switch ($option) {                                              //get request returns different data depending on the option parameter sent
        case 'ALL':
            logMsg('request--GET-ALL');
            $entries = noFilter($direction);
            break;
        case 'FILTER_POINTS':
            logMsg('request--GET-FILTER_POINTS');
            $entries = filterPoints($direction, $word_limit);
            break;
        case 'FILTER_COMMENTS':
            logMsg('request--GET-FILTER_COMMENTS');
            $entries = filterComments($direction, $word_limit);
            break;
        
        default:
            logMsg('Unknown Get Option received');
            break;
    }
    logMsg(var_export(json_encode($entries, JSON_UNESCAPED_UNICODE)));
    echo json_encode($entries, JSON_UNESCAPED_UNICODE);             //send the entries array in json format to angular app
}

?>