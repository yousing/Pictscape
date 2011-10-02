<?php
ini_set( 'display_errors', 'on' );
session_start();
require_once("../include/Mail.class.php");
$mail_ins = new Mail();
$mail_ins->mail_make_random_img();
?>
