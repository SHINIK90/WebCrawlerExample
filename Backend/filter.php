<?php

include 'crawler.php';

function filterPoints($direction = 'desc', $word_limit = 5){
    $entries = getEntries();
    $filter = [];
    foreach($entries as $entry){
        $words = str_word_count(preg_replace('/-/', ' ', $entry['title']));
        if($words <= $word_limit){
            $filter[] = $entry;
        }
    }

    switch ($direction) {
        case 'desc':
            usort($filter, function($a, $b) {
                return $b['points'] <=> $a['points'];  // Descending order
            });
            return $filter;
            break;
        
        case 'asc':
            usort($filter, function($a, $b) {
                return $a['points'] <=> $b['points'];  // Ascending order
            });
            return $filter;
            break;
        
        default:
            break;
    }
}

function filterComments($direction = 'desc', $word_limit = 5){
    $entries = getEntries();
    $filter = [];
    foreach($entries as $entry){
        $words = str_word_count(preg_replace('/-/', ' ', $entry['title']));
        if($words > $word_limit){
            $filter[] = $entry;
        }
    }

    switch ($direction) {
        case 'desc':
            usort($filter, function($a, $b) {
                return $b['comments'] <=> $a['comments'];  // Descending order
            });
            return $filter;
            break;
        
        case 'asc':
            usort($filter, function($a, $b) {
                return $a['comments'] <=> $b['comments'];  // Ascending order
            });
            return $filter;
            break;
        
        default:
            break;
    }
    
}

function noFilter($direction = 'desc'){
    $entries = getEntries();
    switch ($direction) {
        case 'desc':
            return $entries;
            break;
        
        case 'asc':
            return array_reverse($entries);
            break;
        
        default:
            break;
    }
}


?>