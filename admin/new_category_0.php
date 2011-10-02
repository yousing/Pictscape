<?php
include '../include/tmp.inc';
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
					<div class="setting0">
<?php
if(@$_SESSION["error_msg"]){
	echo "<p>";
	echo $_SESSION["error_msg"];
	echo "</p>";
	$_SESSION["error_msg"]="";
} ?>
						<form action="category_menu.php" method="post">
						<h3>登録するメインカテゴリーを入力してください。</h3>
						<p class="select_menu">
						<input type="text" size="40" name="new_category" />
						<input type="submit" value="登録" onclick=" return  confirm('入力された内容をカテゴリーに登録します。\nよろしいですか？'); <?php if(@$_GET["from_new_window"]){ echo "window.close();";} ?>" />
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