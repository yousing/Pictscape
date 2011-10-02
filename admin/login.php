<?php
include_once '../include/Html.class.php';
$html_ins = new Html($_SERVER['PHP_SELF']);
$header = $html_ins->html_header();
$content_header = $html_ins->html_content_header();
$menu_text = $html_ins->html_menu();
$footer_text = $html_ins->html_footer();
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
					<form id="form1" method="post" action="index.php">
						<p class="select_menu">
							ID:<input class="login" type="text" name="user_id" size="20" value="" />
							パスワード：<input class="login" type="password" name="user_pass" size="20" value="" />
							<input class="login" type="submit" value="ログイン" />
						</p>
					</form>
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