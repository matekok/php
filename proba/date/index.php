<style>
    .DateHeader{
        background-color: gainsboro;
    }
    .weakNo{
        background-color: gainsboro;
        text-align: center;
    }
    .datepicker{
        cursor: pointer;
    }
    .today{
        font-size: large;
        font-style: revert;
        color: red;
    }
    .saturday{
        background-color: antiquewhite;
    }
    .sunday{
        background-color: aqua;
    }

</style>
<?php
class calendar {
    protected $DateArr = array();
    public function __construct(){
         if(empty($this->DateArr))$this->setParams('monday');
    }
    private function setParams($start = 'monday'){
        //set timezone of user
        $ip     = $_SERVER['REMOTE_ADDR']; // means we got user's IP address
        if($ip=='::1'){
            //localhost!!!!!!!!!!!!!!!!!!!!
            $ip = '85.238.70.20';
        }
        $jsondata = file_get_contents("http://timezoneapi.io/api/ip/?" . $ip);
        // Decode
        $data = json_decode($jsondata, true);
        $this->DateArr['defaultTimezone'] = $data['data']['timezone']['id'];
        date_default_timezone_set($data['data']['timezone']['id']);
        //set timezone of user/

        //store parameter
        //store - get params
        //format - date format
        $this->DateArr['weakStart']['store'] = $start;
        if(strtolower($start) == 'sunday') $this->DateArr['weakStart']['format'] = 'w';
        else $this->DateArr['weakStart']['format'] = 'N';
        //store days of weak
        $this->DateArr['daysOfWeak'] = array(0=>'Sunday', 1=>'Monday', 2=>'Tuesday', 3=>'Wednesday', 4=>'Thursday', 5=> 'Friday', 6=> 'Saturday', 7=>'Sunday');
        //store actual date
        $this->DateArr['ActualDate']['date'] = date('j/n/Y');
        $this->DateArr['ActualDate']['weak'] = date('W');
    }
    public function DaysToArr($year = 1900, $month = 01, $day=01){
        $this->DateArr['setDate'] = array('Y'=>$year, 'm'=>$month, 'd'=>$day);
        $this->DateArr['dayLast'] = 0;
        $weakInMount = 1;
        //store date in multi array
        for($a=1; $a<=31; $a++){
            if(checkdate($month, $a, $year)){
                $dayInWeak =  date($this->DateArr['weakStart']['format'], mktime(0, 0, 0, $month, $a, $year));
                if($this->DateArr['dayLast'] >= $dayInWeak) $weakInMount++;
                $this->DateArr['Days'][$year][$month][$weakInMount][$dayInWeak] = $a;
                $this->DateArr['dayLast'] = $dayInWeak;
            }
        }
        //store date format
        $this->DateArr['keysOfDate'] = array_keys($this->DateArr['Days'][$year][$month][2]);
    }

    public function dumpHTML(){
        $year = $this->DateArr['setDate']['Y'];
        $month = $this->DateArr['setDate']['m'];
        print '<b>'.date('Y-F', mktime(0, 0, 0, $month, 1, $year)).'</b><br>';
        print '<table>';
        //calendar header
        print '<tr>';
        print '<td class="DateHeader Weak">Weak No.</td>';
        foreach ($this->DateArr['keysOfDate'] as $day){
            print '<td class="DateHeader Days">'.$this->DateArr['daysOfWeak'][$day].'</td>';
        }
        print '</tr>';
        //calendar header/
        //calendar body
        foreach ($this->DateArr['Days'][$year][$month] as $key=>$value){
            print '<tr>';
            $weak = (int) date( 'W', mktime(0,0,0,$month,max($this->DateArr['Days'][$year][$month][$key]),$year));
            $weakClass = ($weak==(int) $this->DateArr['ActualDate']['weak'])?' actualWeak':'';
            print '<td class="weakNo '.$weakClass.'">';
            print $weak;
            print '</td>';
            foreach ($this->DateArr['keysOfDate'] as $key2=>$value2){
                $weak = $key;
                $day = $value2;
                print '<td>';
                if(isset($this->DateArr['Days'][$year][$month][$weak][$day])){
                    $splitDate = $this->DateArr['Days'][$year][$month][$weak][$day].'/'.$month.'/'.$year;
                    $today = ($this->DateArr['ActualDate']['date']==$splitDate)?' today':'';
                    $saturday = (date('N', mktime(0, 0, 0, $month, $this->DateArr['Days'][$year][$month][$weak][$day], $year))==6)?' saturday ':'';
                    $sunday = (date('N', mktime(0, 0, 0, $month, $this->DateArr['Days'][$year][$month][$weak][$day], $year))==7)?' sunday ':'';
                    print '<div class="datepicker '.$today.$saturday.$sunday.'" id="'.$splitDate.'">';
                    print $this->DateArr['Days'][$year][$month][$weak][$day];
                    print '</div>';
                }

                print '</td>';
            }
            print '</tr>';
        }
        //calendar body/
        print '</table>';
    }
    public function DumpArr(){
        print '<pre>';
        print_r($this->DateArr);
        print '</pre>';
    }
}
$calendar = new calendar();
for ($a=1; $a<=12; $a++){
    $calendar->DaysToArr(date('Y'),$a,1);
    $calendar->dumpHTML();
}
$calendar->DumpArr();

