<?php
/*
 *
 * ユーザー認証用ファイル
 *
 */
session_start();
ini_set( 'display_errors', 'off' );
ini_set("gd.jpeg_ignore_warning", true);
include '../include/Auth.class.php';
$auth_ins = new Auth();
$logout=null;
$logout=htmlspecialchars(@$_GET["logout"]);

$user_id = $password = null;
if(isset($_POST["user_id"]) && isset($_POST["user_pass"])){
	$user_id = @$_POST["user_id"];
	$password = @$_POST["user_pass"];
}
$auth_ins->auth($user_id,$password,$logout);
//var_dump($_SESSION);
?>