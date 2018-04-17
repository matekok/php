<?php

class Language {

    protected $HTML;
    protected $ini;

    public function __construct($ini = NULL, $html = NULL) {
        $this->ini = $ini;
        $this->HTML = $html;
    }
    public function translate(){
        $start = '#lang#';
        $end = '#vlang#';
        $parsed = $this->get_string_between($this->HTML, $start, $end);
        $parsed2 = $this->removeDuplicated($parsed);
        $newstring = $this->HTML;
        foreach ($parsed2 as $value) {
            $search = $start.$value.$end;
            $replace = $this->getWord($value);
            $newstring = $this->changeLangFile($newstring, $search, $replace);
        }
        $this->HTML = $newstring;
    }
    private function get_string_between($str, $start, $end) {
        $matches = array();
        $regex = "/$start([a-zA-Z0-9_]*)$end/";
        preg_match_all($regex, $str, $matches);
        return $matches[1];
    }
    private function removeDuplicated($array){
        return array_unique($array);
    }
    private function changeLangFile($str, $search, $replace){
        return str_replace($search,$replace,$str);
    }
    private function getWord($value){
        $deflang = $_SESSION['baseLang'];
        $value = $this->getLangWord($value,$deflang);
        return $value;
    }
    private function getLangWord($str, $deflang){
        $count = rand(0,1);
        if($count>0){$ret = $deflang.'-'.$str;
        }else{$ret = 'eng-'.$str;}
        return ' '.$ret.' ';
    }

    public function getHTML(){
        return $this->HTML;
    }
}
?>