<?php
require_once("Db.class.php");
/**
 * HTML表示クラス
 *
 * ヘッダーフッター等HTML別モジュール用クラス
 *
 *
 * @package Html
 * @access  public
 * @author  KOUNO Hiroomi <hk@angeltale.net>
 * @version
 *
 */
class Html{
/**
 * DBのインスタンスを格納
 *
 * @name DB_ins
 * @access public
 * @var instance
 */
	public $DB_ins;
/**
 * 呼び出し元のPHPを格納
 *
 * @name DB_ins
 * @access public
 * @var instance
 */
	public $phpself;
/**
 * 設定情報を格納
 *
 * @name setting
 * @access public
 * @var instance
 */
	public $setting;
/**
 * コンストラクト
 *
 * DBのインスタンスを作成する。
 *
 * @name __construct
 * @access public
 */
	function __construct($phpself=''){
		$this->DB_ins = new Db();
		$phpself = htmlspecialchars($phpself);
		$php_self_tmp = explode('/',$phpself);
		$this->phpself =array_pop($php_self_tmp);
		$setting=array();
		include 'config.inc';
		$this->setting = $setting;
	}
/**
 * HTMLヘッダー表示関数
 *
 * HTMLのヘッダーを表示
 *
 * @name html_header
 * @return str html_header
 * @access public
 */
	function html_header(){
		$no_cash_date = date("ymdHis");

		$html_header=<<<eof
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" >
	<head>
		<title>{$this->setting["setting_title"]}</title>
		<meta http-equiv="Content-Type" content="text/css" />
		<link rel="stylesheet" href="../css/styles-admin.css" type="text/css" />
		<link rel="stylesheet" href="../css/lightbox.css" type="text/css" />
		<link rel="stylesheet" type="text/css" href="../highslide/highslide.css" />
		<script type="text/javascript" src="../highslide/highslide-with-gallery.js"></script>
		<!--[if lt IE 7]>
		<link rel="stylesheet" type="text/css" href="../highslide/highslide-ie6.css" />
		<![endif]-->
		<script type="text/javascript" src="../js/select.js?{$no_cash_date}" ></script>
		<script type="text/javascript" src="../js/prototype.js"></script>
		<script type="text/javascript" src="../js/scriptaculous.js?load=effects"></script>
		<script type="text/javascript" src="../js/builder.js"></script>
		<script type="text/javascript" src="../js/lightbox.js"></script>
		<script type="text/javascript" src="../js/external.js" ></script>
		<script type="text/javascript" src="../js/pictscape.js" ></script>
		<script type="text/javascript" src="../js/pictscape.admin.hi.js"></script>
	</head>
eof;
		return $html_header;
	}
/**
 * ヘッダー表示関数
 *
 * コンテンツのヘッダーを表示
 *
 * @name html_content_header
 * @return str html_header
 * @access public
 */
	function html_content_header($catg_pankz=""){
		$pankz = $this->html_pankz($catg_pankz);
		if(isset($catg_pankz)){
			$spantag_open="<span style=\"cursor:pointer;\" onclick=\"BoxChecked()\">";
			$spantag_close = "</span>";
		}
		$html_content_header=<<<eof
		<div class="header0">
			<h1 class="title0"><a href="index.php" title="index"><img src="../images/pictscape_logo0.gif" alt="pictscape*" /></a></h1>
			<p>ピクトスケープ スター</p>
			{$pankz[0]}
			<h2 class="catg">{$spantag_open}{$pankz[1]}{$spantag_close}</h2>
		</div>
eof;

		return $html_content_header;
	}
/**
 * フロント画面のヘッダー表示関数
 *
 * フロント画面のコンテンツのヘッダーを表示
 *
 * @name html_front_content_header
 * @return str html_header
 * @access public
 */
	function html_front_content_header($catg_pankz=""){
		$pankz = $this->html_pankz($catg_pankz);
		$html_content_header=<<<eof
		<div class="header0">
			<h1 class="title0"><a href="index.php" title="index">{$this->setting["setting_h1_title"]}</a></h1>
			<p>{$this->setting["setting_caption_title"]}</p>
			{$pankz[0]}
			<h2 class="catg">{$pankz[1]}</h2>
		</div>
eof;

		return $html_content_header;
	}
/**
 * メニュー表示関数
 *
 * メニューを表示
 *
 * @name html_menu
 * @return str html_menu
 * @access public
 */
	function html_menu(){
		$menu = $this->html_make_menu();
		$html_menu=<<<eof
			<div class="menu0">
				<ul>
{$menu}
				</ul>
			</div>
eof;

		return $html_menu;
	}
/**
 * フッター表示関数
 *
 * フッターを表示
 *
 * @name html_footer
 * @return str $html_footer
 * @access public
 */
	function html_footer(){
		$versiontext = file_get_contents("{$this->setting['setting_app_path']}/include/issue.txt");
		$html_footer=<<<eof
		<div class="footer0">
			<p>{$versiontext}</p>
			<address>pictscape* by angeltale</address>
		</div>
eof;

		return $html_footer;
	}
/**
 * パンくず作成表示関数
 *
 *
 * @name html_pankz
 * @return str html_pankz
 * @access public
 */
	function html_pankz($catg_pankz=""){
		$html_pankz = array(0=>'',1=>'');
		switch ( $this->phpself) {
			case 'login.php':
				$html_pankz[0] = "<div class=\"pankz\">ログイン</div>";
			break;
			case 'index.php':
				$html_pankz[0] = "<div class=\"pankz\">ホーム</div>";
				$html_pankz[1] = "ホーム";
			break;
			case 'newfile_0.php':
				$html_pankz[0] = "<div class=\"pankz\"><a href=\"./index.php\">ホーム</a> &gt; アップロード</div>";
				$html_pankz[1] = "アップロード";
			break;
			case 'editfile_0.php':
				$html_pankz[0] = "<div class=\"pankz\"><a href=\"./index.php\">ホーム</a> &gt; カテゴリーの編集</div>";
				$html_pankz[1] = "カテゴリの編集";
			break;
			case 'editfile_1.php':
				$html_pankz[0] = "<div class=\"pankz\"><a href=\"./index.php\">ホーム</a> &gt; <a href=\"./editfile_0.php\">カテゴリーの編集</a>　&gt; {$catg_pankz}</div>";
				$h2_catg = strip_tags($catg_pankz);
				$html_pankz[1] = $h2_catg;
			break;
			case 'category_menu.php':
				$html_pankz[0] = "<div class=\"pankz\"><a href=\"./index.php\">ホーム</a> &gt; カテゴリーの作成と削除</div>";
				$html_pankz[1] = "カテゴリーの作成と削除";
			break;
			case 'view_img.php':
				$html_pankz[0] = "<div class=\"pankz\"><a href=\"./index.php\">ホーム</a> &gt; {$catg_pankz}</div>";
				$html_pankz[1] = $catg_pankz;
			break;
			case 'chgpassword.php':
				$html_pankz[0] = "<div class=\"pankz\"><a href=\"./index.php\">ホーム</a> &gt; ログインパスワードの変更</div>";
				$html_pankz[1] = "ログインパスワードの変更";
			break;
			case 'mail_form.php':
				$html_pankz[0] = "<div class=\"pankz\"><a href=\"./index.php\">ホーム</a> &gt; メールフォーム</div>";
				$html_pankz[1] = "メールフォーム";
			break;
			case 'mail_complate.php':
				$html_pankz[0] = "<div class=\"pankz\"><a href=\"./index.php\">ホーム</a> &gt; メールフォーム</div>";
				$html_pankz[1] = "メールフォーム";
			break;
			case 'new_category_0.php':
				$html_pankz[0] = "<div class=\"pankz\"><a href=\"./index.php\">ホーム</a> &gt; <a href=\"./category_menu.php\">カテゴリーの作成と削除</a> &gt; カテゴリーの追加</div>";
				$html_pankz[1] = "カテゴリーの追加";
			break;
			case 'add_sub_category_0.php':
				$html_pankz[0] = "<div class=\"pankz\"><a href=\"./index.php\">ホーム</a> &gt; <a href=\"./category_menu.php\">カテゴリーの作成と削除</a> &gt; サブカテゴリーの追加</div>";
				$html_pankz[1] = "サブカテゴリーの追加";
			break;

		}
		return $html_pankz;
	}
/**
 * メニュー作成表示関数
 *
 *
 * @name html_make_menu
 * @return str html_make_menu
 * @access public
 */
	function html_make_menu(){
		$menu_array['newfile_0.php'] = "					<li><a href=\"newfile_0.php\" title=\"newfile_0\"><span>アップロード</span></a></li>";
		$menu_array['editfile_0.php'] = "					<li><a href=\"editfile_0.php\" title=\"editfile_0\"><span>カテゴリーの編集</span></a></li>";
		$menu_array['category_menu.php'] = "					<li><a href=\"category_menu.php\" title=\"category_menu\"><span>カテゴリーの作成と削除</span></a></li>";
		$menu_array['chgpassword.php'] = "					<li><a href=\"chgpassword.php\" title=\"category_menu\"><span>パスワードの変更</span></a></li>";
		$menu_array['logout'] = "					<li><a href=\"index.php?logout=1\" title=\"logout\"><span>ログアウト</span></a></li>";
		$html_make_menu='';
		foreach($menu_array as $key => $value){
			if($this->phpself == "login.php"){
				$html_make_menu= "					<li><a href=\"../../pictscape/index.php\" title=\"index\">公開ページに戻る</a></li>";
			}else if( $this->phpself != $key){
				$html_make_menu.= $value."\n";
			}
		}
		return $html_make_menu;
	}
}
?>