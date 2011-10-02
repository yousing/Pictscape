<?php
include '../include/tmp.inc';
include_once '../include/Html.class.php';
include_once '../include/Setting.class.php';
$html_ins = new Html($_SERVER['PHP_SELF']);
$setting_ins = new Setting();
$header = $html_ins->html_header();
$content_header = $html_ins->html_content_header();
$menu_text = $html_ins->html_menu();
$footer_text = $html_ins->html_footer();
//var_dump($_SESSION);
$compmsg="";
if(@$_POST["newpasssubmit"]){
	$return = $setting_ins->set_update_pass(@$_POST,@$_SESSION["user_name"]);
	if($return == "true"){
		$compmsg="正常にアップデートは行われました。";
	}else{
		$compmsg = $return;
	}
}
?>
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
						<form id="form" action="" method="post">
							<?php echo $compmsg; ?>
							<p class="select_menu">
							<span>新しいパスワード:</span><input type="password" class="text" name="newpass" id="newpass" value="" size="30" maxlength="30" /><br />
							<span>新しいパスワード確認:</span><input type="password" class="text" name="renewpass" id="renewpass" value="" size="30" maxlength="30" /><br />
							</p>
							<p class="select_menu">
							<input type="submit" class="submit button" name="newpasssubmit" value="パスワード変更" />
							</p>
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