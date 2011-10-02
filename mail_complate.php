<?php
ini_set( 'display_errors', 'on' );
include_once './include/Front.class.php';
include_once './include/Mail.class.php';
session_start();
$front_ins = new Front($_SERVER['PHP_SELF']);
$header = $front_ins->front_html_header();
$content_header = $front_ins->html_front_content_header();
$catg_list = $front_ins->front_catg_list();
$menu_text = $front_ins->front_menu_html();
$footer_text = $front_ins->html_footer();
$new_thum_list = $front_ins->front_img_thum_list();
$main_img = $front_ins->front_news_view();
$errmsg="";
if($_SESSION["post"]){
	$mail_ins = new Mail();
	$res = $mail_ins->mail_send($_SESSION["post"]);
	if($res){
		$msg = "正常にメールは送信されました。";
		$_SESSION["post"]=array();
	}else{
		$msg = "メール送信にエラーが発生しました。";
		$_SESSION["post"]=array();
	}
}else{
	header("Location:./index.php");
}
?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" >

<?php
echo $header;
 ?>

	<body>
		<div class="body0">

 <?php
echo $content_header;
?>
			<div class="contents0">
				<div class="content0">
					<div class="setting0">
					<?php echo $msg; ?>
					</div>
				</div>
			</div>
<?php
echo $menu_text;
?>

<?php
echo $footer_text;
?>
		</div>
	</body>
</html>