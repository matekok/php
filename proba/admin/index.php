<?php
session_start();

$_SESSION['admin']['page'] = (isset($_GET['page']))?$_GET['page']:array('index');

include __DIR__ . '/config/ClassAutoLoader.php';
$ClassAutoLoader = new ClassAutoLoader();
$ClassAutoLoader->register();
$ClassAutoLoader->doIni();
$ClassAutoLoader->CallFunc();
?>
