<?php
include 'logger.php';

function getEntries(){
    $url = 'https://news.ycombinator.com/';
    $ch = curl_init();
    
    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                    //avoid ssl certificate errors
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                     //return all data as a string to process the html data
    
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
    @$dom->loadHTML($html);                                             //create a DOM representation of the html data to process it
    
    $aThings = $dom->getElementsByTagName('tr');                        // all requires information is inside <tr> elements, rank and title have class aThing
    $entries = [];                                                      // Comments and points are locater in a <tr> that follows the aThing <tr>

    foreach ($aThings as $aThingElement) {
        if($aThingElement instanceof DOMElement && $aThingElement->getAttribute('class') === 'athing'){     //filter <tr> of class aThing and verify it's a DOMElement to avoid errors
            //Rank
            $rank = null;
            $rankSpans = $aThingElement->getElementsByTagName('span');
            foreach($rankSpans as $span){
                if($span instanceof DOMElement && $span->getAttribute('class') === 'rank'){        //rank is found in the only <span> of class rank inside a <tr> of class aThing
                    $rank = $span;
                }
            }
            $rank = $rank ? $rank->nodeValue : 'N/A';
            //Tittle
            $title = null;
            $titleSpans = $aThingElement->getElementsByTagName('span');
            foreach($titleSpans as $span){
                if($span instanceof DOMElement && $span->getAttribute('class') === 'titleline'){    //tittle is found in the first <a> inside a <span> of class tittleline inside a <tr> of class aThing
                    $title = $span->getElementsByTagName('a')->item(0);
                }
            }
            $title = $title ? $title->nodeValue : 'N/A';

            $nextTr = $aThingElement->nextSibling;                                                  //obtain the <tr> that follows aThing to obtain comments and points
            while ($nextTr && $nextTr->nodeType !== XML_ELEMENT_NODE) {                             //check the next element obtained is the correct type to avoid warnings or errors
                $nextTr = $nextTr->nextSibling;
            }

            $points = '';
            $comments = '';

            if($nextTr && $nextTr instanceof DOMElement){                                          //verify it's a DOMElement to avoid errors
                //Points
                $pointsSpan = $nextTr->getElementsByTagName('span');
                foreach($pointsSpan as $span){
                    if($span instanceof DOMElement && $span->getAttribute('class') === 'score'){    //points are found in the only <span> of class score inside this <tr>
                        $points = $span;
                    }
                }
                $points = $points ? $points->nodeValue : 'N/A';

                //Comments
                $commentsSpan = $nextTr->getElementsByTagName('span');
                foreach($commentsSpan as $span){
                    if($span instanceof DOMElement && $span->getAttribute('class') === 'subline'){    //comments are found in the 4th <a> inside the only <span> of class subline inside this <tr>
                        $comments = $span->getElementsByTagName('a')->item(3);
                    }
                }                       
                $comments = $comments ? $comments->nodeValue : 'N/A';

            }

            $entry = [                                        //save the information in a json like structure since that's gonna be used to respond the request
                'rank' => $rank,
                'title' => $title,
                'points' => $points,
                'comments' => $comments
            ];
            $entry = cleanEntry($entry);                      //clean unwanted characters from the strings for each atribute                                                    
            $entries[] = $entry;
            
            logMsg('Rank:' . $entry['rank'] . ' Title:' . $entry['title'] . ' Points:' . $entry['points'] . ' Comments:' . $entry['comments']);
        }
    }
    return $entries;
}

function cleanEntry($entry){
    $rank = extractNumber($entry['rank']);
    $title = str_replace(                                    //I encountered this mistakes in reading the strings while debugging, aparently due to character format, and corrected it
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

function extractNumber($str) {                         //separate only the numbers from the string and eliminate any other characters
    preg_match('/\d+/', $str, $matches);
    return isset($matches[0]) ? (int)$matches[0] : 0;  // Return 0 if no number found, or in the case of comments if value is "discuss"
}

?>