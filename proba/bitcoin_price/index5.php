<?php
function getxml($feed){
    $xml = simplexml_load_file($feed);
    $array = json_decode(json_encode((array)$xml), TRUE);
    return $array;
}
function items2array($items){
    $out = $items['channel']['item'];
    return $out;
}
function printFormatedArray($array){
    print '<pre>';
    print_r($array);
    print '</pre>';    
}
//https://ncore.cc/rss/rssdd.xml
//$feed = 'http://stackoverflow.com/opensearch.xml';
$feed = 'https://ncore.cc/rss/rssdd.xml';
$feed_to_array = getxml($feed);
$feed_to_array = items2array($feed_to_array);
printFormatedArray($feed_to_array);
?>