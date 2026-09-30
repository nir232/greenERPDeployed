<?php
session_start();
$DB_HOST='sql203.infinityfree.com';
$DB_USER='if0_43044013';
$DB_PASS='vp3SIS1hOrNeq';
$DB_NAME='if0_43044013_green_erp';
$conn=null;
if (class_exists('mysqli')) {
    $conn=@new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME);
    if($conn && !$conn->connect_errno){$conn->set_charset('utf8mb4');}
    else {$conn=null;}
}
function db(){global $conn; if(!$conn){jsonResponse(false,'Database connection failed. Import database.sql and check MySQL/mysqli configuration.',[],500);} return $conn;}
function jsonResponse($ok,$message='',$data=[],$code=200){http_response_code($code);header('Content-Type: application/json; charset=utf-8');echo json_encode(['success'=>$ok,'message'=>$message]+$data,JSON_UNESCAPED_UNICODE);exit;}
function requireLogin(){if(empty($_SESSION['user_id'])) jsonResponse(false,'Authentication required.',[],401);}
function requireRole($roles=[]){requireLogin();if($roles && !in_array($_SESSION['role'],$roles,true)) jsonResponse(false,'Permission denied.',[],403);}
function clean($v){return trim((string)($v??''));}
function currentUser(){return ['id'=>(int)($_SESSION['user_id']??0),'name'=>$_SESSION['full_name']??'','username'=>$_SESSION['username']??'','role'=>$_SESSION['role']??''];}
function envv($name){$v=getenv($name);return $v===false?'':trim($v);}
?>
