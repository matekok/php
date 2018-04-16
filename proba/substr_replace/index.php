<?php
$string = "({1}==1&&{2}=={1})&&in_array('1', {3})&&in_array(,{1})&&in_array";
$seperate = 'in_array';
$search1 = "/in_array/";
$replace1 = 'inArray';
$search2 = ")";
$replace2 = ')>=0';
print $string;
$strArray = explode($seperate, $string);
if(count($strArray)>=1){
	$strEND = '';
	foreach($strArray as $key => $string){
		if($key>0) $string = $seperate.$string;
		$string = preg_replace($search1,  $replace1, $string, 1);
		if(count(explode($search2, $string))>1 && strpos($string, $replace1)!==false){
			preg_match('#\{(.*?)\}#', $string, $match);
			preg_match('#\((.*?)\,#', $string, $match2);
			$relation = substr($string, -2);
			$strEND .= '{'.$match[1].'}'.'.indexOf('.$match2[1].')'.$relation;
		}else{
			$strEND .= $string;
		}
	}
	$string = $strEND;
}
print '<br>'.$string;
?>