<?php
include '../include/tmp.inc';
include '../include/Category.class.php';
$catg_ins = new Category();
include_once '../include/Html.class.php';
$html_ins = new Html($_SERVER['PHP_SELF']);
$header = $html_ins->html_header();
$content_header = $html_ins->html_content_header();
$menu_text = $html_ins->html_menu();
$footer_text = $html_ins->html_footer();
$_SESSION["m_id"]="";
$_SESSION["s_id"]="";
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
<?php echo $catg_ins->catg_view_edit_html(); ?>
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