<?php
include '../include/tmp.inc';
include '../include/Category.class.php';
$catg_ins = new Category();
if(@$_GET["delete_main"]){
	$main_id=(int)$_GET["delete_main"];
	$catg_ins->catg_main_catg_delete($main_id);
	header("location:./category_menu.php");
}
 if(@$_POST["main_id"] && @$_POST["new_sub_category"]){
	$res = $catg_ins->catg_insert_sub_catg(@$_POST["new_sub_category"],@$_POST["main_id"]);
 	if($res==false){
		$_SESSION["error_msg"]="このサブカテゴリーはもう登録されています。";
		$_SESSION["main_id"]=$_POST["main_id"];
		header("location:./add_sub_category_0.php");
	}
}
if(@$_GET["delete_sub"]){
	$sub_id=@$_GET["delete_sub"];
	$catg_ins->catg_sub_catg_delete($sub_id);
	header("location:./category_menu.php");
}
if(@$_POST["new_category"]){
	$res = $catg_ins->catg_insert_catg(@$_POST["new_category"]);
	if(!$res){
		$_SESSION["error_msg"]="このカテゴリーはもう登録されています。";
		header("location:./new_category_0.php");
	}
}
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
				<div class="content2">
						<ul>
							<li><a class="add0" href="new_category_0.php" title="new_category_0">メインカテゴリーを作成する</a></li>
							<?php echo $catg_ins->catg_view_html(); ?>
						</ul>
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