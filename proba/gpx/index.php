<!DOCTYPE html>
<html>
    <body>

        <form action="#" method="post" enctype="multipart/form-data">
            Select image to upload:
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload Image" name="submit">
        </form>

    </body>
</html>
<?php
set_time_limit (0);
function StoreData() {
    global $data;
    $xml = simplexml_load_file($_FILES['fileToUpload']['tmp_name']);
    $data['GpxSource'] = json_decode(json_encode($xml), TRUE);
    if (isset($xml->trk->trkseg)) {
        foreach ($xml->trk->trkseg->{'trkpt'} as $trkpt) {
            $trkptlat = (string) $trkpt->attributes()->lat;
            $trkptlon = (string) $trkpt->attributes()->lon;
            $data['allPosition'][] = array('lat' => $trkptlat, 'lon' => $trkptlon);
        }
    }
    if (isset($xml->wpt)) {
        foreach ($xml->wpt as $pt) {
            $trkptlat = (string) $pt['lat'];
            $trkptlon = (string) $pt['lon'];
            $ele = (string) $pt->ele;
            $name = (string) $pt->name;
            $data['allPosition'][] = array('lat' => $trkptlat, 'lon' => $trkptlon);
        }
    }
    if (isset($xml->rte->rtept)) {
        foreach ($xml->rte->rtept as $pt) {
            $trkptlat = (string) $pt->attributes()->lat;
            $trkptlon = (string) $pt->attributes()->lat;
            $data['allPosition'][] = array('lat' => $trkptlat, 'lon' => $trkptlon);
        }
    }
}
function getAddressFromPosition() {
    global $data;
    foreach ($data['allPosition'] as $key => $value) {
        $url = "http://nominatim.openstreetmap.org/reverse?format=json&lat=".$value['lat']."&lon=".$value['lon']."&zoom=18&addressdetails=1&accept-language=en";
        //$url = "http://nominatim.openstreetmap.org/search?q=".$address."&format=json&addressdetails=1&class=place&accept-language=en";
        $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_URL,$url);
        $result = null;
        $result=json_decode(curl_exec($ch), true);
        curl_close($ch);
        $data['getAddressFromPosition'][] = array('url'=>$url, 'result'=>$result);
    }
}
$data = array();
$data['duration']['start'] = date('Y-m-d H:i:s');
StoreData();
getAddressFromPosition();
$data['duration']['end'] = date('Y-m-d H:i:s');
print '<pre>';
print_r($data['duration']);
print_r($data['getAddressFromPosition']);
print '</pre>';
?>