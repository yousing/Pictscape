<?php
ini_set( 'display_errors', 'on' );
include_once './include/Front.class.php';
include_once './include/Category.class.php';
$front_ins = new Front($_SERVER['PHP_SELF']);
$catg_ins = new Category();
$sort = @$_GET["sort"];
$m_id = @$_GET["m_id"];
$s_id = @$_GET["s_id"];
if($sort){
	$front_ins->view_ins->pict_set_sort($sort);
	$_SESSION["sort"]=(int)$sort;
}

if($m_id){
	$front_ins->view_ins->pict_set_id($m_id,$s_id);
	$catg_pankz=$catg_ins->catg_front_display_catg($m_id,$s_id);
}else{
	$m_id=1;
	$front_ins->view_ins->pict_set_id($m_id,$s_id);
	$catg_pankz=$catg_ins->catg_front_display_catg($m_id,$s_id);
}
$header = $front_ins->front_html_header();
$content_header = $front_ins->html_front_content_header($catg_pankz);
$catg_list = $front_ins->front_catg_list();
$menu_text = $front_ins->front_menu_html();
$footer_text = $front_ins->html_footer();
$req_url = @$_SERVER['REQUEST_URI'];
$req_url = htmlspecialchars($req_url, ENT_QUOTES);
//echo $req_url;
$sort_menu = $front_ins->front_sort_menu($req_url);
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
				<div class="contents1">
					<div class="content0">
						<!-- <form id="form1" method="post" action="" > -->
						<div class="setting0">
							
							
<?php
echo $sort_menu;
?>

<?php
if(@$message){
	echo "<p>";
	echo $message;
	echo "</p>";
	$message="";
} ?>

							<div class="highslide-gallery">
							<ul>
							
<?php
echo $front_ins->front_img_list();
?>
							</ul>
							</div> <!-- /highslide -->
 						</div>
 						<!-- </form> -->
 					</div>
				</div>
 				<div class="content2">
<?php echo $catg_list; ?>
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