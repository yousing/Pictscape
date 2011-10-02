<?php
require_once("Db.class.php");
require_once("Pict.class.php");
require_once ('Category.class.php');

/**
 * 画像表示管理クラス
 *
 * DB上のデータを下に画像の表示を制御する。
 *
 *
 * @package View
 * @access  public
 * @author  KOUNO Hiroomi <hk@angeltale.net>
 * @version
 *
 */
class View extends Pict {
/**
 * コンストラクト
 *
 *
 * @name __construct
 * @access public
 */
	function __construct(){
		Pict::__construct();
	}
/**
 * 管理ページ画像一覧表示関数
 *
 * 管理ページで使用する画像の一覧を作成する。
 * 表示する画像の一覧は、別関数によりDBから取り出す。
 *
 * @name view_img_list_make_html
 * @return str img_html
 * @access public
 */
	function view_img_list_make_html(){
		$img_list_arr = $this->view_img_db_list();
		$img_html='';
		if(is_array($img_list_arr)){
			foreach ($img_list_arr as $key => $img_data){
				if($img_data["img_alt_name"]){
					$title=$img_data["img_alt_name"];
				}else{
					$title="タイトルを入力します。";
				}
$img_html.=<<<eof
							<li>
								<input type="checkbox" name="c001[]" id="c001" value="{$img_data["img_mng_id"]}" />
								<span><a class='highslide' href="../img/{$img_data["img_name"]}" onclick="return hs.expand(this)" title="{$img_data["img_alt_name"]}"><img src="../thum/{$img_data["thum_name"]}" alt="{$img_data["img_alt_name"]}" /></a></span>
								<input class="comment0" type="text" name="t001[{$img_data["img_mng_id"]}]" size="20" value="{$title}" />
							</li>

eof;
			}
		}else{
			$img_html="<li>No Image</li>";
		}
		return $img_html;
	}
/**
 * 画像リスト作成関数
 *
 * DBへ問い合わせ、画像パス等の一覧を返す。
 *
 * @name view_img_db_list
 * @return str img_html
 * @access public
 */
	function view_img_db_list(){
		$main_id = (int)$this->main_id;
		$sub_id = (int)$this->sub_id;
		if(!$main_id){
			$main_id=1;
		}
		if($sub_id){
			$sub_search="and catg_img_info.sub_catg_name_id={$sub_id}";
		}else{
			$sub_search="";
		}

		switch ( $this->sort_num) {
		case 1:
			$order_by="order by img_master.img_alt_name";
		break;
		case 2:
			$order_by="order by img_master.img_DateTimeOriginal";
		break;
		case 3:
			$order_by="order by img_master.img_alt_name desc";
		break;
		case 4:
			$order_by="order by img_master.img_DateTimeOriginal desc";
		break;
		default:
			$order_by="order by img_master.insert_date desc";
		break;
}


		$sql=<<<eof
select * from (img_master
left outer join thum_info on img_master.img_save_name = thum_info.img_name)
left outer join catg_img_info on img_master.img_mng_id = catg_img_info.img_mng_id
where del_flag='N'
and catg_img_info.catg_master_id={$main_id}
{$sub_search}
{$order_by}
;
eof;

		//echo $sql;
		$res = $this->db_ins->exec_query($sql);
		$img_list_arr = $this->db_ins->fetchall($res);
		return $img_list_arr;
	}
/**
 * フロントページ画像一覧表示関数
 *
 * フロントページで使用する画像の一覧を作成する。
 * 表示する画像の一覧は、別関数によりDBから取り出す。
 *
 * @name view_front_img_list_make_html
 * @return str img_html
 * @access public
 */
	function view_front_img_list_make_html(){
		$img_list_arr = $img_html = null;
		$img_list_arr = $this->view_img_db_list();
		if(is_array($img_list_arr)){
			foreach ($img_list_arr as $key => $img_data){
				$title=$img_data["img_alt_name"];

$img_html.=<<<eof
							<li>
								<span><a class='highslide' href="./img/{$img_data["img_save_name"]}" onclick="return hs.expand(this)" title="{$img_data["img_alt_name"]}"><img src="./thum/{$img_data["thum_name"]}" alt="{$img_data["img_alt_name"]}" /></a></span>
								<p>{$title}</p>
								<p><a href="http://twitter.com/home?status={$this->setting['setting_root_url']}img/{$img_data["img_save_name"]}" target="_blank">Tweetする</a></p>
							</li>

eof;
			}
		}else{
			$img_html="<li>No Image</li>";
		}
		return $img_html;
	}
/**
 * フロントページサムネイル表示関数
 *
 * フロントページで使用するサムネイルの一覧を作成する。
 *
 * @name view_front_img_list_make_html
 * @return str img_html
 * @access public
 */
	function view_front_img_thum_list_make_html(){
		$img_html='';
		$sql=<<<eof
 select img_save_name,img_alt_name,img_mng_id from img_master
 where del_flag='N'
 order by img_master.img_mng_id desc limit 6
eof;
		$res = $this->db_ins->exec_query($sql);
		$img_list_arr = $this->db_ins->fetchall($res);
		$catg_ins = new Category();

		if(is_array($img_list_arr)){
			foreach ($img_list_arr as $key => $img_data){
				//var_dump($img_data);
				$sql_img=<<<eof
select * from thum_info where img_name = '{$img_data["img_save_name"]}'
eof;
				//echo $sql_img;
				$res1 = $this->db_ins->exec_query($sql_img);
				$thum_info = $this->db_ins->fetch($res1);
				$sql_catg=<<<eof
select * from catg_img_info where img_mng_id = '{$img_data["img_mng_id"]}'
eof;
				$res2 = $this->db_ins->exec_query($sql_catg);
				$catg_info = $this->db_ins->fetch($res2);
				$catg_name = $catg_ins->catg_get_main_name($catg_info["catg_master_id"]);
				$sub_cag_name = $catg_ins->catg_get_sub_catg($catg_info["sub_catg_name_id"]);
				if($sub_cag_name){
					$spacer="＞";
				}else{
					$spacer="";
				}
				$catg_name=$catg_name.$spacer.$sub_cag_name;
	$img_html.=<<<eof
							<li>
								<span><a class='highslide' href="./img/{$img_data["img_save_name"]}"  onclick="return hs.expand(this)" title="{$img_data["img_alt_name"]}"><img src="./thum/{$thum_info["thum_name"]}" alt="{$img_data["img_alt_name"]}" /></a></span>
								<p>{$img_data["img_alt_name"]}</p>
								<p><a href="view_img.php?m_id={$catg_info["catg_master_id"]}&amp;s_id={$catg_info["sub_catg_name_id"]}">{$catg_name}</a></p>
							</li>

eof;
		}
	}
		return $img_html;
	}
/**
 * フロントページメイン画像表示関数
 *
 * フロントページで使用するメイン画像のHTMLを作成する。
 *
 * @name view_front_main_img_make_html
 * @return str $main_img_html
 * @access public
 */
	function view_front_main_img_make_html(){
		$sql=<<<eof
select * from img_master left outer join catg_img_info on img_master.img_mng_id = catg_img_info.img_mng_id
where del_flag = 'N'
and catg_img_info.catg_master_id='2'
order by img_master.img_mng_id desc
eof;

		$res = $this->db_ins->exec_query($sql);
		$main_img_arr = $this->db_ins->fetch($res);
		if(isset($main_img_arr["img_save_name"])){
			$main_img_html = <<<eof
				<img src="./img/{$main_img_arr["img_save_name"]}" alt="{$main_img_arr["img_alt_name"]}" />
eof;

		}else{
			$main_img_html="No IMAGE";
		}
		return $main_img_html;
	}
}
?>
