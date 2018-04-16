<?php
function hourly($kerosin) {
//    Fuel (320 gph at $5.80 per gallon)    $2,204.00
//    Airframe Maintenance                  $243.39
//    Engine/APU Maintenance                $709.51
//    Crew Misc.                            $350.00
    $fuel = 320*$kerosin;
    $airframe = 250;
    $engine = 800;
    $crew = 400;
    return $fuel+$airframe+$engine+$crew;
}
function fix(){
/*
Pilots                          $231,750.00
Crew Training                   $69,380.80
Hangar                          $80,000.00
Insurance                       $60,000.00
Aircraft Miscellaneous          $28,500.00
 */
    $pilote = 2*120000;
    $training = 70000;
    $hangar = 80000;
    $insurance = 60000;
    $airframe = 30000;
    return $pilote+$training+$hangar+$insurance+$airframe;
}
function calculate($hours, $kerosinPrice) {
    global $out;
    $fix = fix();
    $out.= 'fix: <b>'.number_format( $fix, 2, ',', ' ').'</b><br>montly :<b> '.number_format(($fix / 12), 2, ',', ' ').'</b></br>';
    $hourly = hourly($kerosinPrice);
    $out .= 'hourly : <b>'. number_format($hourly, 2, ',', ' ') .'</b> </br>';
    $out .= '<hr>';
    foreach ($hours as $key => $value) {
        $out.= $value.' hours: <b>'.number_format((($fix / 12)+($hourly * $value)), 2, ',', ' ') .'</b> </br>';
    }
    $out .= '<hr>';    
    return $hourly;
}
function getGeocode($address){
    global $out;
    global $debug;
    $address = str_replace(' ', '+', $address).'+airport';
    
    $url = "http://nominatim.openstreetmap.org/search?q=".$address."&format=json&addressdetails=1&class=place&accept-language=en";
    $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_URL,$url);
        $result=json_decode(curl_exec($ch), true);
        curl_close($ch);
    $out .= '<pre>'. print_r($result[0]['address'], true) . '</pre>';
    $debug['getGeo'][] = array('link' => $url, 'return' => $result);
    return $result;
}
function calculateDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000){
  // convert from degrees to radians
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);
  $latDelta = $latTo - $latFrom;
  $lonDelta = $lonTo - $lonFrom;
  $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
  return $angle * $earthRadius/1000;
}

function destinations($param) {
    global $debug;
    $return = array();
    if(!empty($param)){
        foreach ($param as $key => $value) {
            $last = null;
            $ret = array();
            $res = getGeocode($value);
            $ret['lat'] = $res[0]['lat'];
            $ret['lon'] = $res[0]['lon'];
            $ret['place'] = $value;
            $return[] = $ret;
        }

    }
    $debug['destinations'] = $return;
    return $return;
}

$out = null;
$debug = array();
$hours =  array(100, 200, 300, 500, 1000);
$kerosinPrice = 5.3;
$hourly = calculate($hours, $kerosinPrice);
$destination = array();
$destination[] = 'ferihegy'; 
$destARR = explode(',',$_GET['destination']);
foreach ($destARR as $value) {
    $destination[] = $value;
}
$destination[] = 'ferihegy';
$ret = destinations($destination);
$distance = array();
foreach ($ret as $key => $value) {
    if($key == 0)        continue;
    else{
        $last = $ret[$key-1];
        $distance[$key]['from'] = $last['place'];
        $distance[$key]['to'] = $value['place'];
        $distance[$key]['distanceinKM'] = calculateDistance($last['lat'], $last['lon'], $value['lat'], $value['lon']);
        $distance[$key]['distanceinTime'] = ceil($distance[$key]['distanceinKM']/700);
        $distance[$key]['trueTime'] = $distance[$key]['distanceinKM']/700;
    }
}
$out.= '<hr>';
$out.= '<pre>'.print_r($distance, true).'</pre></br>';
$debug['distance'] = $distance;

$price = 0;
$priceTrue = 0;
$priceUser = 10000;
foreach ($distance as $key => $value) {
    $price++;
    $price+=$value['distanceinTime'];
    $priceTrue += $value['trueTime'];
    if(!in_array($key, array(1, count($distance)))) $priceUser += $value['distanceinTime']*10000;
    else $priceUser += $value['distanceinTime']*$hourly;
}

if($price>=$priceUser) $priceUser = $price*1.1;

$priceAvg = (($price*$hourly)+$priceUser)/2;

print 'sum price <b>'.number_format($price*$hourly, 2, ',', ' ').'</b><br>';
print 'true price <b>'.number_format($priceTrue*$hourly, 2, ',', ' ').'</b><br>';
print 'price user <b>'.number_format($priceUser, 2, ',', ' ').'</b><br>';
print 'price average <b>'.number_format($priceAvg, 2, ',', ' ').'</b><br>';
print '<pre>'; print_r($debug);print '<pre>';
print '<br><ul><ul><br>'.$out;

?>