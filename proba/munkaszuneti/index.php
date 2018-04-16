<?php 
function MunkaszunetiNapLeptetes(&$munkanap, &$aktualis){
        $fixUnnep=array('01-01','05-01','08-20','10-23','11-01','12-25','12-26');
        $vandorUnnep = array('2017-06-05');
        foreach ($fixUnnep as $value) {
            if($aktualis == date('Y', strtotime($aktualis)).'-'.$value){
                
                $munkanap++; 
                $aktualis = date('Y-m-d', strtotime($aktualis. " +1 day"));
                $this->MunkaszunetiNapLeptetes($munkanap, $aktualis);
            }
        }
        foreach ($vandorUnnep as $value) {
            if($aktualis == $value){
                $munkanap++; 
                $aktualis = date('Y-m-d', strtotime($aktualis. " +1 day"));
                $this->MunkaszunetiNapLeptetes($munkanap, $aktualis);
            }
        }
        return array($munkanap, $aktualis);
    }
?>