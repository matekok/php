<?php
$ret=array();
$ret['message'] = '';

$u = "A szerzod�s d�ja megv�ltozott, a helyes d�j: 64140, tarif�lt d�j 106906#Erroronline#kw#A megadott g�pj�rmu teljes�tm�nye t�ves. Megadott: 74 Helyes: 47#47#83382#Erroronline##Erroronline#ccm#A megadott hengerurtartalom t�ves. Megadott: 1390 Helyes: 1198#1198#83382#Erroronline##Erroronline#ossztomeg#A megadott �sszt�meg t�ves. Megadott: 1615 Helyes: 1605#1605#83382#Erroronline##netYearPrem#64140#netYearPrem#";

//          Visszt�r� �vesd�j: 34077,szerz�d�s �vesd�j: 29103     #Erroronline#kw#A megadott g�pj�rm� teljes�tm�nye t�ves. Megadott: 83 Helyes: 103#103#34077#Erroronline#. D�jelt�r�s csatolva a winliznbe, ezen a n�ven: Dijelteres_1510320605.html
            $array = explode('#', $u);
            $ret['price'] = $array[0];
            //az els� �s az utols� elem eldob�sa
            $arraySlice = array_slice($array, 1, -1);
            //t�rdel�s 7-es csoportokra
            $retArray = array_chunk($arraySlice, 7);
            //list array
            foreach ($retArray as $key => $value) {
                $keys = array('errorFlag', 'ErrorKey', 'ErrorMessage', 'ErrorValue', 'ErrorPrice');
                $count = min(count($retArray[$key]), count($keys));
                $ret[$key] = array_combine(array_slice($keys, 0, $count), array_slice($retArray[$key], 0, $count));
                if($ret[$key]['errorFlag'] == 'Erroronline'){ 
                    $ret['success'] = FALSE;
                    $ret['message'] .= $ret[$key]['ErrorMessage'].'<br>';
//                  $ret['price'] .= 'Az �r megv�ltozott <b>'.$this->ini->setSessionValue('').'-r�l '.$ret[$key]['ErrorPrice'].'-ra </b>';
                }
            }
print '<pre>';
	print_r($ret);
print '</pre>';
?>