<?php
include_once ('Html.class.php');
include_once ('Category.class.php');
include_once ('View.class.php');
/**
 * 表用HTML表示クラス
 *
 * 通常表示用HTML作成クラス
 *
 *
 * @package Front
 * @access  public
 * @author  KOUNO Hiroomi <hk@angeltale.net>
 * @version
 *
 */

class Front extends Html {
/**
 * カテゴリーのインスタンスを格納
 *
 * @name catg_ins
 * @access public
 * @var instance
 */
	public $catg_ins;
/**
 * Viewのインスタンスを格納
 *
 * @name view_ins
 * @access public
 * @var instance
 */
	public $view_ins;
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
 * 親クラスHtmlのコンストラクトを呼び出し
 *
 * @name __construct
 * @param str phpself
 * @access public
 */
	function __construct($phpself=""){
		Html::__construct($phpself);
		$this->catg_ins = new Category();
		$this->view_ins = new View();
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
	function front_html_header(){
		$no_cash_date = date("ymdHis");
		$html_header=<<<eof
	<head>
		<title>{$this->setting["setting_title"]}</title>
		<meta http-equiv="Content-Type" content="text/css" />
		<link rel="stylesheet" href="./css/type01/styles.css" type="text/css" />
		<link rel="stylesheet" href="./css/lightbox.css" type="text/css" />
		<link rel="stylesheet" type="text/css" href="./highslide/highslide.css" />
		<script type="text/javascript" src="./highslide/highslide-with-gallery.js"></script>
		<!--[if lt IE 7]>
		<link rel="stylesheet" type="text/css" href="./highslide/highslide-ie6.css" />
		<![endif]-->		
		<script type="text/javascript" src="./js/select.js?{$no_cash_date}" ></script>
		<script type="text/javascript" src="./js/prototype.js"></script>
		<script type="text/javascript" src="./js/scriptaculous.js?load=effects"></script>
		<script type="text/javascript" src="./js/builder.js"></script>
		<script type="text/javascript" src="./js/lightbox.js"></script>
		<script type="text/javascript" src="./js/external.js" ></script>
		<script type="text/javascript" src="./js/pictscape.js" ></script>
		<script type="text/javascript" src="./js/pictscape.hi.js"></script>
	</head>
eof;

		return $html_header;
	}
/**
 * カテゴリー一覧表示関数
 *
 * Categoryクラス関数により、カテゴリーの一覧を取得し表示させる
 *
 * @name front_catg_list
 * @return str html_catg_list
 * @access public
 */
	function front_catg_list(){
		$html_catg_list = $this->catg_ins->catg_view_img_html();
		$html_catg_list_table=<<<eof
						<ul>
{$html_catg_list}
						</ul>

eof;

		return $html_catg_list_table;
	}
/**
 * 画像一覧表示関数
 * Viewクラス関数により、カテゴリーの一覧を取得し表示させる
 *
 * @name front_img_list
 * @return str html_catg_list
 * @access public
 */
	function front_img_list(){
		$front_img_list = $this->view_ins->view_front_img_list_make_html();
		return $front_img_list;
	}
/**
 * サムネイル画像一覧表示関数
 *
 * Viewクラス関数により、最新5枚のサムネイルを取得し表示させる
 *
 * @name front_img_list
 * @return str html_catg_list
 * @access public
 */
	function front_img_thum_list(){
		$front_img_html = $this->view_ins->view_front_img_thum_list_make_html();
		return $front_img_html;
	}
/**
 * サムネイル画像一覧表示関数
 *
 * Viewクラス関数により、最新5枚のサムネイルを取得し表示させる
 *
 * @name front_img_list
 * @return str html_catg_list
 * @access public
 */
	function front_menu_html(){
		$menu_html=<<<eof

			<div class="menu0">
				<ul>
					<li><a href="./info_pictscape.html">pictscapeについて</a></li>
					<li><a href="./mail_form.php">連絡先</a></li>
				</ul>
				<p><a class="adm0" href="./admin/" title="index" >Login</a></p>
			</div>

eof;

		return $menu_html;
	}
/*
 * ホームメイン画面表示
 *
 * Viewクラス関数により、ニュースに設定されている画像を表示させる
 *
 * @name front_news_view
 * @return str html_news_view
 * @access public
 */
	function front_news_view(){
		$main_img = $this->view_ins->view_front_main_img_make_html();
		return $main_img;
	}
/*
 * 表示画面ソートメニュー生成関数
 *
 *　表示URLを引数で取り、画像ソートメニューを生成する。
 *
 * @name front_sort_menu
 * @return str sort_menu
 * @access public
 */
	function front_sort_menu($url){
		$url=preg_replace("/&sort.*$/",'',$url);

		$sort_name_url = $url."&amp;sort=1";
		$sort_name_url_desc = $url."&amp;sort=3";
		$sort_time_url = $url."&amp;sort=2";
		$sort_time_url_desc = $url."&amp;sort=4";

	 	$sort_menu_html=<<<eof
						<ol class="select_menu">
							<li>タイトル<ol>
								<li><a href="{$sort_name_url}">▲</a></li>
								<li><a href="{$sort_name_url_desc}">▼</a></li>
							</ol></li>
							<li>掲載日付<ol>
								<li><a href="{$sort_time_url}">▲</a></li>
								<li><a href="{$sort_time_url_desc}">▼</a></li>
							</ol></li>
						</ol>
eof;

		return $sort_menu_html;
	}

}
?>