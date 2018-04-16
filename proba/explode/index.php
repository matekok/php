<?php
$ret=array();
$ret['message'] = '';

$u = "A szerzodés díja megváltozott, a helyes díj: 64140, tarifált díj 106906#Erroronline#kw#A megadott gépjármu teljesítménye téves. Megadott: 74 Helyes: 47#47#83382#Erroronline##Erroronline#ccm#A megadott hengerurtartalom téves. Megadott: 1390 Helyes: 1198#1198#83382#Erroronline##Erroronline#ossztomeg#A megadott össztömeg téves. Megadott: 1615 Helyes: 1605#1605#83382#Erroronline##netYearPrem#64140#netYearPrem#";

//          Vissztérõ évesdíj: 34077,szerzõdés évesdíj: 29103     #Erroronline#kw#A megadott gépjármû teljesítménye téves. Megadott: 83 Helyes: 103#103#34077#Erroronline#. Díjeltérés csatolva a winliznbe, ezen a néven: Dijelteres_1510320605.html
            $array = explode('#', $u);
            $ret['price'] = $array[0];
            //az elsõ és az utolsó elem eldobása
            $arraySlice = array_slice($array, 1, -1);
            //tördelés 7-es csoportokra
            $retArray = array_chunk($arraySlice, 7);
            //list array
            foreach ($retArray as $key => $value) {
                $keys = array('errorFlag', 'ErrorKey', 'ErrorMessage', 'ErrorValue', 'ErrorPrice');
                $count = min(count($retArray[$key]), count($keys));
                $ret[$key] = array_combine(array_slice($keys, 0, $count), array_slice($retArray[$key], 0, $count));
                if($ret[$key]['errorFlag'] == 'Erroronline'){ 
                    $ret['success'] = FALSE;
                    $ret['message'] .= $ret[$key]['ErrorMessage'].'<br>';
//                  $ret['price'] .= 'Az ár megváltozott <b>'.$this->ini->setSessionValue('').'-ról '.$ret[$key]['ErrorPrice'].'-ra </b>';
                }
            }
print '<pre>';
	print_r($ret);
print '</pre>';
?>