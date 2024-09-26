<?php
include 'logger.php';

function getEntries(){
    $url = 'https://news.ycombinator.com/';
    $ch = curl_init();
    
    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $html = curl_exec($ch);
    if(curl_errno($ch)) {
        logMsg('Error:' . curl_error($ch));
    } else {
        logMsg('SUCCESS --- Got ycombinator html data');
        // logMsg('website info is:\n' . var_export($html, true));
    }
    curl_close($ch);
    
    //parse the HTML
    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    
    $aThings = $dom->getElementsByTagName('tr');
    $entries = [];

    foreach ($aThings as $aThingElement) {
        if($aThingElement instanceof DOMElement && $aThingElement->getAttribute('class') === 'athing'){
            // $rank = $aThingElement
            //     ->getElementsByTagName('span')->item(0);
            // $rank = $rank ? $rank->nodeValue : 'N/A';

            $rank = null;
            $rankSpans = $aThingElement->getElementsByTagName('span');
            foreach($rankSpans as $span){
                if($span instanceof DOMElement && $span->getAttribute('class') === 'rank'){
                    $rank = $span;
                }
            }
            $rank = $rank ? $rank->nodeValue : 'N/A';

            $title = null;
            $titleSpans = $aThingElement->getElementsByTagName('span');
            foreach($titleSpans as $span){
                if($span instanceof DOMElement && $span->getAttribute('class') === 'titleline'){
                    $title = $span->getElementsByTagName('a')->item(0);
                }
            }
            $title = $title ? $title->nodeValue : 'N/A';

            $nextTr = $aThingElement->nextSibling;
            while ($nextTr && $nextTr->nodeType !== XML_ELEMENT_NODE) {
                $nextTr = $nextTr->nextSibling;
            }

            $points = '';
            $comments = '';

            if($nextTr && $nextTr instanceof DOMElement){
                $points = $nextTr
                    ->getElementsByTagName('span')->item(1);
                $points = $points ? $points->nodeValue : 'N/A';

                $comments = $nextTr
                    ->getElementsByTagName('a')->item(3);
                $comments = $comments ? $comments->nodeValue : 'N/A';

            }

            $entry = [
                'rank' => $rank,
                'title' => $title,
                'points' => $points,
                'comments' => $comments
            ];
            $entry = cleanEntry($entry);
            $entries[] = $entry;
            
            // logMsg('Rank:' . $entry['rank'] . ' Title:' . $entry['title'] . ' Points:' . $entry['points'] . ' Comments:' . $entry['comments']);
        }
    }
    // logMsg(json_encode($entries, JSON_UNESCAPED_UNICODE));
    return $entries;
}

function cleanEntry($entry){
    $rank = extractNumber($entry['rank']);
    $title = str_replace(
        ['â', 'â', 'â¦'], // List of common incorrect characters
        ['–', '’', '…'],       // Replacement characters
        $entry['title']
    );
    $points = extractNumber($entry['points']);
    $comments = extractNumber($entry['comments']);

    return [
        'rank' => $rank,
        'title' => $title,
        'points' => $points,
        'comments' => $comments
    ];
}

function extractNumber($str) {
    preg_match('/\d+/', $str, $matches);
    return isset($matches[0]) ? (int)$matches[0] : 0;  // Return 0 if no number found, or in the case of comments if value is "discuss"
}

// getEntries();

?>