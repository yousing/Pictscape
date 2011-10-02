<?php
include '../include/tmp.inc';
include '../include/Category.class.php';
$catg_ins = new Category();
$catg_ins->catg_make_script();
include_once '../include/Html.class.php';
$html_ins = new Html($_SERVER['PHP_SELF']);
$header = $html_ins->html_header();
$content_header = $html_ins->html_content_header();
$menu_text = $html_ins->html_menu();
$footer_text = $html_ins->html_footer();
include_once '../include/config.inc';
unset($_SESSION["m_id"]);
unset($_SESSION["s_id"]);
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
					<form id="form1" method="post" action="./editfile_1.php" enctype="multipart/form-data">
						<h3>ブラウザーでアップロード</h3>
						<div class="setting0">
							<p class="select_menu">
								<span>アップロードするファイル：</span><input type="file" name="f001" size="50" />
							</p>
							<p class="select_menu">
								<span>画像のタイトル：</span><input type="text" name="t001" size="25" />
								<br />
								<span>カテゴリー：</span>
								<!-- set name and onchange -->
								<select name="sel_main" onchange="selectMain(this)" size="5">
								<option value="">(メインカテゴリー)</option>
								</select>
								<select name="sel_sub" size="5">
								<option value="">(サブカテゴリー)</option>
								</select>
								<br />
							</p>
							<p class="select_menu">
								<span>表示画像のサイズ：</span><input type="text" name="size_set" size="10" value="<?php echo $setting["setting_img_size"];?>" />px
							</p>
							<p class="upload">
								<input class="submit0" type="submit" name="submit_newfile" value="保存" />
							</p>
						</div>
						<h3>FTPでアップロード</h3>
						<div class="setting0">
							<p class="select_menu">
								<span>カテゴリー：</span>
								<!-- set name and onchange -->
								<select name="sel_main2" onchange="selectMain2(this)" size="5">
								<option value="">(メインカテゴリー)</option>
								</select>
								<select name="sel_sub2" size="5">
								<option value="">(サブカテゴリー)</option>
								</select>
								<br />
							</p>
							<p class="select_menu">
								<span>表示画像のサイズ：</span><input type="text" name="size_set" size="10" value="<?php echo $setting["setting_img_size"];?>" />px
							</p>
							<p class="upload">
								<input class="submit0" type="button" name="newfile_bulk" onclick="bulk_save();" value="一括保存"   />
								<input type="hidden" name="newfile_bulk" value="1" />
							</p>
						</div>
					</form>
				</div>
				<div class="content3">
					<h3>ヘルプ</h3>
					<p>「FTPでアップロード」は、先にFTPで既定のフォルダに画像ファイルをアップロードしてから、「一括保存」をクリックします。</p>
					<p>もしカテゴリーの選択がない場合、自動で「未登録」のカテゴリーに分類されます。</p>
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