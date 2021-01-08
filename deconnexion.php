<?php
require_once 'config/config.conf.php';
require_once 'config/bdd.conf.php';

setcookie('sid', '',-1);

$_SESSION['notification']['message']='Vous êtes déconnecté';
$_SESSION['notification']['result']='danger';
header("Location: index.php?p=1");
exit();