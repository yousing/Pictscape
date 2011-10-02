<?php
ini_set( 'display_errors', 'on' );
include_once './include/Front.class.php';
$front_ins = new Front($_SERVER['PHP_SELF']);
$header = $front_ins->front_html_header();
$content_header = $front_ins->html_front_content_header();
$catg_list = $front_ins->front_catg_list();
$menu_text = $front_ins->front_menu_html();
$footer_text = $front_ins->html_footer();
$new_thum_list = $front_ins->front_img_thum_list();
$main_img = $front_ins->front_news_view();
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
					<div class="content1">
<?php  echo $main_img	;?>
					</div>
					<div class="content0">
						<div class="setting0">
						<h2>Latest images...</h2>
							<div class="highslide-gallery">
							<ul>
<?php  echo $new_thum_list; ?>
							</ul>
							</div>
						</div>
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