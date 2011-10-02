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
				<div class="content3">
					<h3>pictscape* バージョン1の管理画面へようこそ</h3>
					<p>「アップロード」では、画像ファイルのアップロードや、FTPでアップロード済みの画像ファイルを一括でカテゴリーに登録できます。</p>
					<p>「カテゴリーの編集」では、すでにアップロードされた画像ファイルの、カテゴリーの変更や、タイトルの変更、削除ができます。</p>

					<p>「カテゴリーの作成と削除」では、新たにメインカテゴリーやサブカテゴリーを作ったり、カテゴリーの削除ができます。</p>
					<p>「ログアウト」は、管理画面から、ゲストが閲覧する公開ページに戻ります。</p>
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