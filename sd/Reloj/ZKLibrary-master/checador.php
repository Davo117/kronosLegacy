<?php
include "zklibrary.php";
$zk = new ZKLibrary('192.168.1.201', 4370);
$zk->connect();
$zk->disableDevice();
$zk->setTime(date("Y-m-d H:i:s"));
$zk->testVoice();
$zk->enableDevice();
$zk->disconnect();
?>