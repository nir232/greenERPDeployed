<?php
require 'config.php';
if(empty($_SESSION['user_id'])){header('Location:index.php');exit;}
$map=['admin'=>'admin.php','moderator'=>'moderator.php','accounts'=>'accounts.php','inventory'=>'inventory.php','hr'=>'hr.php','executive'=>'executive.php'];
header('Location: '.($map[$_SESSION['role']]??'index.php'));exit;
