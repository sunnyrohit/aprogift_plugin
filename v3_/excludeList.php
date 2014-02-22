<?php


$conjunctions = array(
'for', 'and', 'nor', 'but', 'or', 'yet', 'so','after','although','as','because','before','even','if','inasmuch',
'lest','now','once','provided','since','supposing','than','that','though','til','unless','until','when',
'whenever','where','whereas','wherever','whether','which','while','who','whoever','why',
);



$prepositions = array(
'aboard','about','above','across','after','against','along','amid','among','anti','around','as','at','before',
'behind','below','beneath','beside','besides','between','beyond','but','by','concerning','considering','despite',
'down','during','except','excepting','excluding','following','for','from','in','inside','into','like','minus','near',
'of','off','on','onto','opposite','outside','over','past','per','plus','regarding','round','save','since','than','through',
'to','toward','towards','under','underneath','unlike','until','up','upon','versus','via','with','within','without'
);

$articles = array(
    'a', 'an', 'the'
);

$noiseWords = array(
    'institute', 'school', 'college', 'university', 'design' , 'management', 'technology'
);

$excludeListHash = array();

function initializeExcludeListHash() {
    global $prepositions;
    global $conjunctions;
    global $articles;
    global $noiseWords;

    global $excludeListHash;
    
    foreach($prepositions as $preposition)
        $excludeListHash[$preposition] = true;
    foreach($conjunctions as $conjunction)
        $excludeListHash[$conjunction] = true;
    foreach($articles as $article)
        $excludeListHash[$article] = true;
    foreach($noiseWords as $noiseWord)
        $excludeListHash[$noiseWord] = true;
    
}

function cleanExtraneous($str) {
    global $excludeListHash;
    
    $str = strtolower($str);
    $str = preg_replace('/[^a-zA-Z0-9]/', ' ', $str);
    $str = preg_replace('/ +(?= )/', '', $str);
    
    if (strlen($str) <3 )
        return '';
    
    $words = explode(' ', $str);
    
    $len = sizeof($words);
    
    
    $finalStr = '';
    $count = 0;
    for ($i=0; $i<$len; $i++) {
        $word = $words[$i];
        if (isset($excludeListHash[$word]) || strlen($word)<2) {
            continue;
        }        
        $finalStr .= $word.' ';
        $count++;
    }
    
    if ($count > 4)
        return '';
    else
        return trim($finalStr);
}


?>