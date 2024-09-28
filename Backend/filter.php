<?php

include 'crawler.php';

function filterPoints($direction = 'desc', $word_limit = 5){                        //filter the full entries array by points
    $entries = getEntries();
    $filter = [];
    foreach($entries as $entry){
        $words = str_word_count(preg_replace('/-/', ' ', $entry['title']));         //replace '-' characters with  space so that str_word_count concider strings like 'a-b' like 2 different words
        if($words <= $word_limit){
            $filter[] = $entry;                                                     //save entries with equal or less than the amount of words in the word limit to the filtered entries array
        }
    }

    switch ($direction) {                                                           //order the entries by points in ascending or descending order depending on the specified direction
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
            logMsg('string for direction parameter is neither "desc" or "asc"');
            break;
    }
}

function filterComments($direction = 'desc', $word_limit = 5){                      //filter the full entries array by comments
    $entries = getEntries();
    $filter = [];
    foreach($entries as $entry){
        $words = str_word_count(preg_replace('/-/', ' ', $entry['title']));         //replace '-' characters with  space so that str_word_count concider strings like 'a-b' like 2 different words
        if($words > $word_limit){
            $filter[] = $entry;                                                     //save entries with more than the amount of words in the word limit to the filtered entries array
        }
    }

    switch ($direction) {                                                           //order the entries by points in ascending or descending order depending on the specified direction
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
            logMsg('string for direction parameter is neither "desc" or "asc"');
            break;
    }
    
}

function noFilter($direction = 'desc'){                 //return the entire entries array sorted in ascending or descending order simply by reversing their order, they are descending by default
    $entries = getEntries();
    switch ($direction) {
        case 'desc':
            return $entries;
            break;
        
        case 'asc':
            return array_reverse($entries);
            break;
        
        default:
            logMsg('string for direction parameter is neither "desc" or "asc"');
            break;
    }
}


?>