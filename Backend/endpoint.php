<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

include 'filter.php';
// include 'logger.php';

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    logMsg('GET REQUEST');

    $option = getParameter('option');
    $word_limit = getParameter('word_limit') ?? 5;
    $direction = getParameter('direction') ?? 'desc'; // asc or desc
    $entries = [];

    switch ($option) {
        case 'ALL':
            $entries = noFilter($direction);
            break;
        case 'FILTER_POINTS':
            $entries = filterPoints($direction, $word_limit);
            break;
        case 'FILTER_COMMENTS':
            $entries = filterComments($direction, $word_limit);
            break;
        
        default:
            //error
            break;
    }

    echo json_encode($entries, JSON_UNESCAPED_UNICODE);
}

?>