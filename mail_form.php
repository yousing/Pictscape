<?php
ini_set( 'display_errors', 'on' );
include_once './include/Front.class.php';
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
if(@$_POST["form_submit"]){
	$key_string=@$_POST["from_key"];
	$md5_key_string=md5($key_string);
	if($md5_key_string==$_SESSION["$md5_key_string"]){
		$_SESSION["post"]=$_POST;
		header("Location:mail_complate.php");
	}else{
		$form=$_POST;
		$errmsg = "keyが異なります。";
	}
}else if(@$_SESSION["post"]){
	$form=@$_SESSION["post"];
	$_SESSION["post"]=array();
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
						<?php
							echo $errmsg;
						?>
						<form action="mail_form.php" method="post">
							<table border="1" cellpadding="3" cellspacing="0" class="form">

								<tr>
									<th>お名前</th>
								</tr>
								<tr>
									<td><input name="name_kanji" type="text" size="24" value="<?php echo @$form["name_kanji"];?>" /></td>
								</tr>
								<tr>
									<th>メールアドレス</th>

								</tr>
								<tr>
									<td><input name="mail_address" type="text" size="36" value="<?php echo @$form["mail_address"];?>" /></td>
								</tr>
								<tr>
									<th>メッセージ</th>
								</tr>
								<tr>
									<td><textarea name="message" rows="8" cols="48"><?php echo nl2br(@$form["message"]);?></textarea></td>
								</tr>
								<tr>
									<td>
										<img src="./images/captcha.php" alt="captcha" />
										<input type="text" name="from_key">
									</td>
								</tr>
								<tr>
									<td colspan="2" class="input0">
										<input type="reset" value="入力内容をクリアする" onclick="return confirm('入力内容を元に戻します。よろしいですか？')" />
										<input type="submit" name="form_submit" value="送信する" />
										<input type="hidden" name="prs_btn" value="1" />
									</td>
								</tr>
							</table>
						</form>
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