<?php
ini_set('upload_max_filesize', 10 * 1024 * 1024);
include_once '../include/tmp.inc';
include_once '../include/Category.class.php';
include_once '../include/Pict.class.php';
include_once '../include/View.class.php';
include_once '../include/config.inc';
include_once '../include/Html.class.php';
include_once '../include/Db.class.php';
$html_ins = new Html($_SERVER['PHP_SELF']);
$catg_ins = new Category();
$catg_ins->catg_make_script();
$view_ins = new View();
$db_ins = new Db();
$m_id = '';
$m_id = @$_SESSION["m_id"];
$m_id = @$_POST["sel_main"];
if($_GET["m_id"]){
	$m_id = $_GET["m_id"];
}

$s_id = '';
$s_id = @$_SESSION["s_id"];
$s_id = @$_POST["sel_sub"];
if($_GET["s_id"]){
	$s_id = $_GET["s_id"];
}
$message = '';
if($m_id){
	$view_ins->pict_set_id($m_id,$s_id);
	$catg_pankz=$catg_ins->catg_display_catg($m_id,$s_id);
}
if(@$_POST["submit_newfile"]){
	//print_r($_FILES);
	$img_tmp = $_FILES["f001"]["tmp_name"];
	$img_name = $_FILES["f001"]["name"];
	$img_size = $_FILES["f001"]["size"];
	$img_eroor = $_FILES["f001"]["error"];
	$upload_path = $setting["setting_upload_path"].$img_name;
	if($img_eroor==0){
		if(!isset($m_id)){
			$m_id=1;
		}
		$res = move_uploaded_file($img_tmp,$upload_path);
		if($res){
			$pict_ins = new Pict($upload_path);
			$pict_ins->pict_set_id($m_id,$s_id);
			$pict_ins->pict_set_seiz($_POST["size_set"]);
			$pict_ins->pict_set_title($_POST["t001"]);
			$save_name = $pict_ins->pict_reduced();
			$pict_ins->pict_reduced($save_name);
			$view_ins->pict_set_id($m_id,$s_id);
			$catg_pankz=$catg_ins->catg_display_catg($m_id,$s_id);
		}else{
			echo "upload false";
			return false;
		}
		$_SESSION["m_id"]=$m_id;
		$_SESSION["s_id"]=$s_id;
	}else{
		$message="エラーが発生しました。<br />ファイルが大きすぎるか、<br />回線が不安定か、<br />フォルダの設定ミスです。<br />そのあたりを改善し再度上げなおしてみてください。";
	}
	//print_r($_POST);
}else if(@$_POST["submit_edit"]){
	$pict_ins = new Pict();
	if(!$m_id){
		$m_id=$_SESSION["m_id"];
	}
	if(!$s_id){
		$s_id=$_SESSION["s_id"];
	}
	$pict_ins->pict_set_id($m_id,$s_id);
	$pict_ins->pict_set_id_list($_POST["c001"]);
	$pict_ins->pict_set_title($_POST["t001"]);
	$pict_ins->pict_bulk_setting_edit();
	$view_ins->pict_set_id($m_id,$s_id);
	$catg_pankz=$catg_ins->catg_display_catg($m_id,$s_id);
	$_SESSION["m_id"]=$m_id;
	$_SESSION["s_id"]=$s_id;
}else if(@$_POST["submit_search"]){
	$view_ins->pict_set_id($m_id,$s_id);
	$catg_pankz=$catg_ins->catg_display_catg($m_id,$s_id);
	$_SESSION["m_id"]=$m_id;
	$_SESSION["s_id"]=$s_id;
}else if (isset($_POST["submit_del"]) and is_array($_POST["c001"]) ){
	foreach($_POST["c001"] as $key => $value){
		$sql="update img_master set del_flag='Y' where img_mng_id = {$value}";
		$db_ins->exec_query($sql);
		$sql="select * from img_master left join thum_info on img_master.img_save_name = thum_info.img_name where img_mng_id={$value}";
		$res = $db_ins->exec_query($sql);
		$img_data = $db_ins->fetch($res);
		unlink($setting["setting_app_path"]."/img/".$img_data["img_name"]);
		unlink($setting["setting_app_path"]."/thum/".$img_data["thum_name"]);
	}
	$view_ins->pict_set_id($m_id,$s_id);
	$catg_pankz=$catg_ins->catg_display_catg($m_id,$s_id);
}else if(@$_GET["m_id"]){
	$_SESSION["m_id"]=$_GET["m_id"];
	$_SESSION["s_id"]=$_GET["s_id"];
	header("location:./editfile_1.php");
}

$header = $html_ins->html_header();
$content_header = $html_ins->html_content_header($catg_pankz);
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
					<form id="form1" method="post" action="" >
					<div class="setting0">
<?php
if($message){
	echo "<p>";
	echo $message;
	echo "</p>";
	$message="";
} ?>
						<ul>
<?php
	echo $view_ins->view_img_list_make_html();
?>
						</ul>
						<p class="select_menu">
							<span>カテゴリー：</span>
							<!-- set name and onchange -->
							<select name="sel_main" onchange="selectMain(this)" size="5" >
							<option value="">(メインカテゴリー)</option>
							</select>
							<select name="sel_sub" size="5" >
							<option value="">(サブカテゴリー)</option>
							</select>
							<br />
						</p>
						<p class="select_menu">
							<input class="submit0" type="submit" name="submit_search" value="検索" />
						</p>
						<p class="upload">
							<input class="submit0" type="submit" name="submit_edit" value="移動・更新" />
							<input class="submit0" type="submit" name="submit_del" value="削除" onclick="del_check();" />
						</p>
					</div>
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