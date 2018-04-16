<?php 
$string = 'Vissztérő évesdíj: 34077,szerződés évesdíj: 29103     #Erroronline#kw#A megadott gépjármű teljesítménye téves. Megadott: 83 Helyes: 103#103#34077#Erroronline##Erroronline#kw#A megadott gépjármű teljesítménye téves. Megadott: 83 Helyes: 103#103#34077#Erroronline#. Díjeltérés csatolva a winliznbe, ezen a néven: Dijelteres_1510320605.html';
$string = 'Vissztérő évesdíj: 96494,szerződés évesdíj: 81871     #Erroronline#kw#A megadott gépjármű teljesítménye téves. Megadott: 155 Helyes: 215#215#96494#Erroronline##Erroronline#szemelyek#A megadott szállítható szemnélyek száma téves. Megadott: 5 Helyes: 7#7#96494#Erroronline##Erroronline#ossztomeg#A megadott össztömeg téves. Megadott: 2535 Helyes: 3150, így a szerződés díja megváltozott, a helyes díj: 96494, tarifált díj 81871#3150#96494#Erroronline#. Díjeltérés csatolva a winliznbe, ezen a néven: D';
//array #-ént
$array = explode('#', $string);
//az eslő és az utolsó elem eldobása
$arraySlice = array_slice($array, 1, -1);
//tördelsé 7-es csoportokra
$retArray = array_chunk($arraySlice, 7);
$retArray2 = array();
//list array
foreach ($retArray as $key => $value) {
    $keys = array('errorFlag', 'ErrorKey', 'ErrorMessage', 'ErrorValue', 'ErrorPrice');
    $count = min(count($retArray[$key]), count($keys));
    $retArray[$key] = array_combine(array_slice($keys, 0, $count), array_slice($retArray[$key], 0, $count));
}
//print
print '<pre>';
print_r($retArray);
print '</pre>';
exit();
?>