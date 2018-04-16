<?php
header('Content-Type: text/html; charset=utf-8');
require_once('lang/eng.php');
if($_GET['lang'] != 'eng' && isset($_GET['lang']) && file_exists('lang/'.$_GET['lang'].'.php'))require_once('lang/'.$_GET['lang'].'.php');
print_r($lang['text']['welcome']);
?>