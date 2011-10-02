<?php
require_once 'Db.class.php';

/**
 * カテゴリー管理クラス
 *
 * カテゴリーの入出力を担当するクラス。
 *
 *
 * @package Category
 * @access  public
 * @author  KOUNO Hiroomi <hk@angeltale.net>
 * @version
 *
 */
class Category{
/**
 * DBのインスタンスを格納
 *
 * @name DB_ins
 * @access public
 * @var instance
 */
	public $DB_ins;
/**
 * カテゴリーのリストを格納
 *
 * @name catg_main_list
 * @access public
 * @var array
 */
	public $catg_main_list;
/**
 * サブカテゴリーのリストを格納
 *
 * @name catg_main_list
 * @access public
 * @var array
 */
	public $catg_sub_list;
/**
 * 設定値を格納を格納
 *
 * @name setting_arr
 * @access public
 * @var array
 */
	public $setting_arr;
/**
 * コンストラクト
 *
 * DBのインスタンスを作成する。
 *
 * @name __construct
 * @access public
 */
	function __construct(){
		$setting=array();
		include 'config.inc';
		$this->DB_ins = new Db();
		if(!$this->catg_sub_list){
			$this->catg_make_sub_catg_list();
		}
		if(!$this->catg_main_list){
			$this->catg_make_main_catg_list();
		}
		$this->setting_arr = $setting;
	}
/**
 * カテゴリー登録関数
 *
 * 引数を新規にメインカテゴリーを登録し、戻り値としてクエリの実行結果を返す。
 *
 *
 * @name catg_insert_catg
 * @param str catg_str
 * @return str res
 * @access public
 */
	function catg_insert_catg($catg_str){
		$chk_res = $this->catg_check_catg($catg_str);
		if($chk_res){
			return false;
		}else{
			$catg_str=$this->DB_ins->escape($catg_str);
			$sql="insert into catg_master (catg_name,del_flag) values ('$catg_str','N');";
			$res = $this->DB_ins->exec_query($sql);
			return $res;
		}
	}
/**
 * カテゴリー重複チェック関数
 *
 * 引数にカテゴリーを取り同一カテゴリーがある場合は、trueを返す。
 *
 *
 * @name catg_check_catg
 * @param str catg_str
 * @return resource res
 * @access public
 */
function catg_check_catg($catg_str){
	$sql="select * from catg_master where catg_name = '{$catg_str}' and del_flag='N'";
	$res = $this->DB_ins->exec_query($sql);
	$return= $this->DB_ins->fetch($res);
	if($return){
		return true;
	}else{
		return false;
	}
}
/**
 * カテゴリー一覧表示関数
 *
 * 登録されているカテゴリー、サブカテゴリーを表示させる。
 *
 *
 * @name catg_view_html
 * @return str catg_html
 * @access public
 */
	function catg_view_html(){
		$sql = " select * from catg_master where del_flag = 'N' order by catg_master_id desc";
		$res = $this->DB_ins->exec_query($sql);
		$catg_arr = $this->DB_ins->fetchall($res);
		$catg_html = null;
		if(is_array($catg_arr)){
			foreach ($catg_arr as $key => $main_catg){
				// $main_catgがメインカテゴリーの中身
				$catg_master_id = @$main_catg[catg_master_id];
				$sql ="select * from catg_info left outer join  sub_catg_name on catg_info.sub_catg_name_id=sub_catg_name.sub_catg_name_id  where catg_master_id = {$catg_master_id} and del_flag ='N' order by catg_info.sub_catg_name_id desc";
				$res = $this->DB_ins->exec_query($sql);
				if($res){
					$sub_catg_arr = $this->DB_ins->fetchall($res);
				}
				$sub_cnt=count($sub_catg_arr);
				if($sub_cnt){
					$catg_html.="							<li><span class=\"name0\">{$main_catg["catg_name"]}</span><a class=\"delete0\" href=\"#\" onclick=\"return delete_main('{$main_catg["catg_name"]}','{$main_catg["catg_master_id"]}');\">×</a><ul>\n";
					//$catg_html.="							<li><span class=\"name0\">{$main_catg["catg_name"]}</span><a class=\"delete0\" href=\"javascript:if(confirm('メインカテゴリー:[{$main_catg["catg_name"]}]とそのサブカテゴリーをすべて削除してもよろしいですか？')){location.href='./category_menu.php?delete_main={$main_catg["catg_master_id"]}'}\" >×</a><ul>\n";
					foreach($sub_catg_arr as $sub_key => $sub_catg){
						$catg_html.="								<li><span class=\"name0\">{$sub_catg["sub_catg_name"]}</span><a class=\"delete0\" href=\"#\" onclick=\"return delete_sub('{$sub_catg["sub_catg_name"]}','{$sub_catg["sub_catg_name_id"]}');\">×</a></li>\n";
						//$catg_html.="					<li><span class=\"name0\">{$sub_catg["sub_catg_name"]}</span><a class=\"delete0\" href=\"javascript:if(confirm('サブカテゴリー:[{$sub_catg["sub_catg_name"]}]を削除してもよろしいですか？')){location.href='./category_menu.php?delete_sub={$sub_catg["sub_catg_name_id"]}';}\">×</a></li>\n";
					}
					$catg_html.="							<li><a class=\"add0\" href=\"./add_sub_category_0.php?main_id={$main_catg["catg_master_id"]}\">サブカテゴリー追加</a></li></ul></li>\n";
				}else{
					if($main_catg["catg_master_id"]==1 or $main_catg["catg_name"]=="NEWS"){
						//未登録カテゴリーは削除できない
						//$catg_html.="";
					}else{
						$catg_html.="							<li><span class=\"name0\">{$main_catg["catg_name"]}</span><a class=\"delete0\" href=\"#\" onclick=\"return delete_main('{$main_catg["catg_name"]}','{$main_catg["catg_master_id"]}');\">×</a><ul>\n";
						//$catg_html.="							<li><span class=\"name0\">{$main_catg["catg_name"]}</span><a class=\"delete0\" href=\"javascript:if(confirm('メインカテゴリー:[{$main_catg["catg_name"]}]を削除してもよろしいですか？')){location.href='./category_menu.php?delete_main={$main_catg["catg_master_id"]}';}\" >×</a><ul>\n";
						$catg_html.="							<li><a class=\"add0\" href=\"./add_sub_category_0.php?main_id={$main_catg["catg_master_id"]}\">サブカテゴリー追加</a></li></ul></li>\n";
					}
				}
			}
		}
		return $catg_html;
	}
/**
 * メインカテゴリー名表示関数
 *
 * メインカテゴリーのIDを引数とし、メインカテゴリーの名前を返す。
 *
 *
 * @name catg_get_main_name
 * @param int catg_id
 * @return str catg_name
 * @access public
 */
	function catg_get_main_name($catg_id){
		$sql = "select catg_name from catg_master where catg_master_id = '{$catg_id}'";
		$res = $this->DB_ins->exec_query($sql);
		$catg_name = $this->DB_ins->fetch($res);
		return $catg_name["catg_name"];
	}
/**
 * サブカテゴリー登録関数
 *
 * 引数を新規にサブカテゴリーを登録し、同時にcatg_infoにメインカテゴリーのIDと
 * サブカテゴリーのIDを登録する。
 * 戻り値としてクエリの実行結果を返す。
 *
 *
 * @name catg_insert_sub_catg
 * @param str catg_str
 * @return str res
 * @access public
 */
	function catg_insert_sub_catg($catg_str,$main_id){
		$res=$this->catg_check_sub_catg($catg_str,$main_id);
		if($res){
			return false;
		}else{
			$catg_str=$this->DB_ins->escape($catg_str);
			$sql="insert into sub_catg_name (sub_catg_name,del_flag) values ('$catg_str','N');";
			$new_sub_id = $this->DB_ins->exec_query_id($sql);
			if($new_sub_id){
				$sql="insert into catg_info (catg_master_id,sub_catg_name_id) values ('$main_id','$new_sub_id');";
				$res = $this->DB_ins->exec_query($sql);
			}
			return $res;
		}
	}
/**
 * サブカテゴリー重複チェック関数
 *
 * 引数にサブカテゴリー名とメインカテゴリIDを取り
 * 同一名サブカテゴリーがある場合は、trueを返す。
 *
 *
 * @name catg_check_sub_catg
 * @param str catg_str
 * @return resource res
 * @access public
 */
function catg_check_sub_catg($sub_catg_str,$main_id){
	$sql="select * from sub_catg_name left outer join catg_info using(sub_catg_name_id) where catg_master_id = '{$main_id}' and sub_catg_name='{$sub_catg_str}' and del_flag ='N'";
	$res = $this->DB_ins->exec_query($sql);
	$return= $this->DB_ins->fetch($res);
	if($return){
		return true;
	}else{
		return false;
	}
}
/**
 * メインカテゴリー削除関数
 *
 * メインカテゴリーIDを引数として取り、メインカテゴリーとそれに繋がるサブカテゴリーのdel_flagを
 * 'Y'にし削除状態にアップデートする。
 *
 *
 * @name catg_main_catg_delete
 * @param int main_id
 * @return str res
 * @access public
 */
	function catg_main_catg_delete($main_id){
		$main_id=$this->DB_ins->escape($main_id);
		$main_id=(int)$main_id;
		$sql="update catg_master set del_flag = 'Y' where catg_master_id = '{$main_id}'";
		$res = $this->DB_ins->exec_query($sql);
		$setting = $this->setting_arr;
		if($res){
			$sql2="select * from (img_master left outer join catg_img_info on img_master.img_mng_id=catg_img_info.img_mng_id) left outer join thum_info on img_master.img_save_name=thum_info.img_name where  catg_master_id ={$main_id}";
			$res2 = $this->DB_ins->exec_query($sql2);
			$dal_img_list_arr = $this->DB_ins->fetchall($res2);
			if(is_array($dal_img_list_arr)){
				foreach($dal_img_list_arr as $key => $del_img){
					$del_sql=" update img_master set del_flag = 'Y' where img_save_name = '{$del_img["img_save_name"]}'";
					$res=$this->DB_ins->exec_query($del_sql);
					if($res){
						$img_name = $setting["setting_app_path"]."/img/".$del_img["img_save_name"];
						$thum_name = $setting["setting_app_path"]."/thum/".$del_img["thum_name"];
						if(is_file($img_name)){
							unlink($img_name);
						}
						if(is_file($thum_name)){
							unlink($thum_name);
						}
					}else{
						echo $res;
					}
				}
			}
			$sql="select sub_catg_name_id from catg_info where catg_master_id = '{$main_id}'";
			$res = $this->DB_ins->exec_query($sql);
			$sub_catg_id_arr =$this->DB_ins->fetchall($res);
			foreach($sub_catg_id_arr as $key => $sub_catg_name_id){
				$this->catg_sub_catg_delete($sub_catg_name_id["sub_catg_name_id"]);
			}
		}
		return $res;
	}
/**
 * サブカテゴリー削除関数
 *
 * サブテゴリーIDを引数として取り、サブカテゴリーのdel_flagを'Y'にし削除状態にアップデートする。
 *
 *
 * @name catg_sub_catg_delete
 * @param int sub_id
 * @return str res
 * @access public
 */
	function catg_sub_catg_delete($sub_id){
		$sub_id=$this->DB_ins->escape($sub_id);
		$sub_id=(int)$sub_id;
		$sql="update sub_catg_name set del_flag = 'Y' where sub_catg_name_id = '{$sub_id}'";
		$res = $this->DB_ins->exec_query($sql);
		if($res){
			$sql2="select * from (img_master left outer join catg_img_info on img_master.img_mng_id=catg_img_info.img_mng_id) left outer join thum_info on img_master.img_save_name=thum_info.img_name where sub_catg_name_id ={$sub_id}";
			$res2 = $this->DB_ins->exec_query($sql2);
			$dal_img_list_arr = $this->DB_ins->fetchall($res2);
			$setting_arr = $this->setting_arr;
			if(is_array($dal_img_list_arr)){
				foreach($dal_img_list_arr as $key => $del_img){
					$del_sql="update img_master set del_flag = 'Y' where img_save_name = '{$del_img["img_save_name"]}'";
					$res = $this->DB_ins->exec_query($del_sql);
					if($res){
						$img_name = $setting_arr["setting_app_path"]."/img/".$del_img["img_save_name"];
						$thum_name = $setting_arr["setting_app_path"]."/thum/".$del_img["thum_name"];
						if(is_file($img_name)){
							unlink($img_name);
						}
						if(is_file($thum_name)){
							unlink($thum_name);
						}
					}else{
						echo $res;
						break;
					}
				}
			}
		}
	}
/**
 * javascript用カテゴリ一覧作成
 *
 * メインカテゴリーの一覧をDBから取得し、catg_master_idから
 * 配下のサブカテゴリーの一覧を取得。
 * それぞれをjavascriptで使う形に加工をする。
 *
 * @name catg_main_list
 * @return arr main_list
 * @access public
 */
	function catg_script_list(){
		$sql=" select * from catg_master where del_flag='N' order by catg_master_id desc" ;
		$res = $this->DB_ins->exec_query($sql);
		$main_catg_arr = $this->DB_ins->fetchall($res);
		foreach($main_catg_arr as $key=>$main_catg){
			$catg_master_id_arr[$key]= "\"".$main_catg["catg_master_id"]."\"";
			$catg_name_arr[$key]="\"".$main_catg["catg_name"]."\"";
			$sql_sub="select * from sub_catg_name left outer join catg_info on sub_catg_name.sub_catg_name_id=catg_info.sub_catg_name_id where catg_master_id = {$main_catg["catg_master_id"]} and sub_catg_name.del_flag='N' order by  catg_info_id desc";
			$res_sub = $this->DB_ins->exec_query($sql_sub);
			$sub_catg_arr = $this->DB_ins->fetchall($res_sub);
			if(is_array($sub_catg_arr) && count($sub_catg_arr)>0){
				foreach($sub_catg_arr as $sub_key=>$sub_catg){
					//echo $main_catg["catg_master_id"].$sub_catg["sub_catg_name"]."\n<br/>";
					$sub_catg_master_id_arr[$main_catg["catg_master_id"]][]= "\"".$sub_catg["sub_catg_name_id"]."\"";
					$sub_catg_name_arr[$main_catg["catg_master_id"]][]="\"".$sub_catg["sub_catg_name"]."\"";
				}
				$sub_catg_name_list =implode(",",$sub_catg_name_arr[$main_catg["catg_master_id"]]);
				$sub_catg_id_list=implode(",",$sub_catg_master_id_arr[$main_catg["catg_master_id"]]);
			}else{
				$sub_catg_name_list="";
				$sub_catg_id_list="";
			}
			//echo $main_catg["catg_master_id"].$sub_catg_name_list."<br/>\n";
			//echo $main_catg["catg_master_id"].$sub_catg_id_list."<br/>\n";
			$sub_catg_name[$key]="bunruiB[\"{$main_catg["catg_master_id"]}\"]= new Array({$sub_catg_name_list});";
			$sub_catg_id[$key]="bunruiBid[\"{$main_catg["catg_master_id"]}\"]= new Array({$sub_catg_id_list});";
		}
		//var_dump($sub_catg_name_arr);
		$catg_master_id_csv=implode(",",$catg_master_id_arr);
		$catg_master_name_csv=implode(",",$catg_name_arr);
		$main_list["catg_name"]="bunruiA = new Array(".$catg_master_name_csv.")\n";
		$main_list["catg_master_id"]="bunruiAid = new Array(".$catg_master_id_csv.")\n";
		$main_list["sub_catg_name"]=implode("\n",$sub_catg_name) ;
		$main_list["sub_catg_id"]=implode("\n",$sub_catg_id) ;
		return $main_list;
	}
/**
 * javascript作成関数
 *
 * カテゴリー自動変異用javascript作成関数
 *
 *
 * @name catg_sub_catg_delete
 * @access public
 * @see catg_script_list
 */
	function catg_make_script(){
	$main_list_arr=$this->catg_script_list();
	$setting_arr =$this->setting_arr;
	if(file_exists($setting_arr["setting_app_path"]."/js/select.js")){
		unlink($setting_arr["setting_app_path"]."/js/select.js");
	}
$script=<<<eof
window.onload  = function(){
		// メインカテゴリーリストを定義
		var bunruiA = new Array();
		var bunruiAid = new Array();
		{$main_list_arr["catg_name"]}
		{$main_list_arr["catg_master_id"]}
		// 分類Aの選択リストを作成
		var element = document.getElementById("form1");
	try{
		createSelection(element.elements['sel_main'], "(メインカテゴリー)", bunruiAid, bunruiA);
		createSelection(element.elements['sel_main2'], "(メインカテゴリー)", bunruiAid, bunruiA);
	}catch( e ){
	}
}
	////////////////////////////////////////////////////
	//
	// 選択ボックスに選択肢を追加する関数
	//	引数: ( selectオブジェクト, value値, text値)
	function addSelOption( selObj, myValue, myText )
	{
			selObj.length++;
			selObj.options[ selObj.length - 1].value = myValue ;
			selObj.options[ selObj.length - 1].text  = myText;

	}
	/////////////////////////////////////////////////////
	//
	//	選択リストを作る関数
	//	引数: ( selectオブジェクト, 見出し, value値配列 , text値配列 )
	//
	function createSelection( selObj, midashi, aryValue, aryText )
	{
			selObj.length = 0;
			addSelOption( selObj, 0, midashi);
			// 初期化
			for( var i=0; i < aryValue.length; i++)
			{
					addSelOption ( selObj , aryValue[i], aryText[i]);
			}
	}
	///////////////////////////////////////////////////
	//
	// 	メインカテゴリーが選択されたときに呼び出される関数
	//
	function selectMain(obj)
	{
		 // サブカテゴリーを定義
		var bunruiB=new Array();
		var bunruiBid=new Array()
		{$main_list_arr["sub_catg_name"]}
		{$main_list_arr["sub_catg_id"]}
		var element = document.getElementById("form1");
			// 選択肢を動的に生成
			createSelection(element.elements['sel_sub'], "(サブカテゴリー)",bunruiBid[obj.value], bunruiB[obj.value]);

	}
	///////////////////////////////////////////////////
	//
	// 	メインカテゴリーが選択されたときに呼び出される関数
	//
	function selectMain2(obj)
	{
		 // サブカテゴリーを定義
		var bunruiB=new Array();
		var bunruiBid=new Array()
		{$main_list_arr["sub_catg_name"]}
		{$main_list_arr["sub_catg_id"]}
		var element = document.getElementById("form1");
			// 選択肢を動的に生成
			createSelection(element.elements['sel_sub2'], "(サブカテゴリー)",bunruiBid[obj.value], bunruiB[obj.value]);

	}
	/////////////////////////////////////////////////
	// submit前の処理
	function gettext(form){
			var a =form1.sel_main.value;   // 分類1
			var b =form1.sel_sub.value;   // 分類2
			// ANDでつなげる
			form1.elements['search'].value = a+' AND '+b;
			alert(form1.elements['search'].value );
	}
eof;

		ob_start();
		echo $script;
		$content = ob_get_contents();

		$fh=fopen($setting_arr["setting_app_path"]."/js/select.js","w+");
		fwrite($fh,$content);
		fclose($fh);
		ob_end_clean();
	}
/**
 * 画像編集用カテゴリー一覧表示関数
 *
 * 登録されているカテゴリー、サブカテゴリーを表示させ、画像編集画面へのリンクを張る。
 *
 *
 * @name catg_view_edit_html
 * @return str catg_html
 * @access public
 */
	function catg_view_edit_html(){
		$sql = " select * from catg_master where del_flag = 'N' order by catg_master_id desc";
		$res = $this->DB_ins->exec_query($sql);
		$catg_arr = $this->DB_ins->fetchall($res);
		$catg_html = null;
		foreach ($catg_arr as $key => $main_catg){
			// $main_catgがメインカテゴリーの中身
			$catg_master_id = @$main_catg[catg_master_id];
			$sql ="select * from catg_info left outer join  sub_catg_name on catg_info.sub_catg_name_id=sub_catg_name.sub_catg_name_id  where catg_master_id = {$catg_master_id} and del_flag ='N' order by catg_info.sub_catg_name_id desc";
			$res = $this->DB_ins->exec_query($sql);
			$sub_catg_arr='';
			if($res){
				$sub_catg_arr = $this->DB_ins->fetchall($res);
			}
			$sub_cnt = count($sub_catg_arr);
			if($sub_cnt){
				$catg_html.="							<li><a class=\"name0\" href=\"editfile_1.php?m_id={$main_catg["catg_master_id"]}\" title=\"editfile_1\">{$main_catg["catg_name"]}</a><ul>";
				foreach($sub_catg_arr as $sub_key => $sub_catg){
						$catg_html.="							<li><a class=\"name0\"  href=\"editfile_1.php?m_id={$main_catg["catg_master_id"]}&amp;s_id={$sub_catg["sub_catg_name_id"]}\" title=\"editfile_1\">{$sub_catg["sub_catg_name"]}</a></li>\n";
				}
				$catg_html.="							</ul></li>";
			}else{
				if($main_catg["catg_master_id"]==1){
					$catg_html.="							<li><a class=\"name0\"  href=\"editfile_1.php?m_id={$main_catg["catg_master_id"]}\" title=\"editfile_1\">{$main_catg["catg_name"]}</a></li>\n";
				}else{
					$catg_html.="							<li><a class=\"name0\" href=\"editfile_1.php?m_id={$main_catg["catg_master_id"]}\" title=\"editfile_1\">{$main_catg["catg_name"]}</a></li>\n";
				}
			}
		}
		return $catg_html;
	}
/**
 * メインカテゴリーリスト作成関数
 *
 * DBへ問い合わせ、メインカテゴリーの連想配列を作成する
 * 形式は以下の通り
 * array(catg_master_id => catg_name)
 *
 * @name catg_make_main_catg_list
 * @access public
 */
	function catg_make_main_catg_list(){
		$sql = "select catg_master_id,catg_name from catg_master where del_flag='N'";
		$res = $this->DB_ins->exec_query($sql);
		$catg_arr = $this->DB_ins->fetchall($res);
		if(is_array($catg_arr)){
		foreach ($catg_arr as $key => $catg_value) {
			$this->catg_main_list["{$catg_value["catg_master_id"]}"] = $catg_value["catg_name"];
		}
		}
	}
/**
 * サブカテゴリーリスト作成関数
 *
 * DBへ問い合わせ、サブカテゴリーの連想配列を作成する
 * 形式は以下の通り
 * array(catg_master_id => catg_name)
 *
 * @name catg_make_sub_catg_list
 * @access public
 */
	function catg_make_sub_catg_list(){
		$sql = " select * from sub_catg_name where del_flag='N'";
		$res = $this->DB_ins->exec_query($sql);
		$catg_arr = $this->DB_ins->fetchall($res);
		if($catg_arr){
			foreach ($catg_arr as $key => $catg_value) {
				$this->catg_sub_list["{$catg_value["sub_catg_name_id"]}"] = $catg_value["sub_catg_name"];
			}
		}
	}
/**
 * メインカテゴリー名取得関数
 *
 * 引数にメインカテゴリーIDをとり、クラス変数内のメインカテゴリー名を返す
 *
 * @name catg_get_main_catg
 * @param int main_id
 * @return str main_name
 * @access public
 */
	function catg_get_main_catg($main_id){
		if(!$this->catg_sub_list){
			$this->catg_make_main_catg_list();
		}
		$main_id = (int)$main_id;
		return $this->catg_main_list["$main_id"];
	}
/**
 * サブカテゴリー名取得関数
 *
 * 引数にサブカテゴリーIDをとり、クラス変数内のサブカテゴリー名を返す
 *
 * @name catg_get_sub_catg
 * @param int sub_id
 * @return str sub_name
 * @access public
 */
	function catg_get_sub_catg($sub_id){
		if(!$this->catg_sub_list){
			$this->catg_make_sub_catg_list();
		}
		$sub_id = (int)$sub_id;
		return @$this->catg_sub_list["$sub_id"];
	}
/**
 * 現在選択カテゴリー名表示関数
 *
 * メインカテゴリーIDとサブカテゴリーIDを引数に取り、
 * 現在選択されているカテゴリー名、サブカテゴリー名を表示させる。
 *
 * @name catg_display_catg
 * @param int main_id
 * @param int sub_id
 * @return str catg_pankz
 * @access public
 */
	function catg_display_catg($main_id,$sub_id=""){
		$a_open = $main_name = $a_close = $gt = $sub_name = null;
		if($main_id){
			$main_name = $this->catg_get_main_catg($main_id);
		}
		if($sub_id){
			$sub_name = $this->catg_get_sub_catg($sub_id);
		}
		if($sub_name){
			$gt="&gt;";
			$a_open = null;
			$a_close = null;
			$a_open=<<<eof
<a href="./editfile_1.php?m_id={$main_id}">
eof;
			$a_close="</a>";
		}

		$catg_pankz=<<<eof
{$a_open}{$main_name}{$a_close}{$gt}{$sub_name}
eof;

			return $catg_pankz;
	}
/**
 * フロント用現在選択カテゴリー名表示関数
 *
 * メインカテゴリーIDとサブカテゴリーIDを引数に取り、
 * 現在選択されているカテゴリー名、サブカテゴリー名を表示させる。
 *
 * @name catg_display_catg
 * @param int main_id
 * @param int sub_id
 * @return str catg_pankz
 * @access public
 */
	function catg_front_display_catg($main_id,$sub_id=""){
		$main_name = $lt = $sub_name = null;
		if($main_id){
			$main_name = $this->catg_get_main_catg($main_id);
		}
		if($sub_id){
			$sub_name = $this->catg_get_sub_catg($sub_id);
		}
		if($sub_name){
			$lt="&gt;";
		}
		$catg_pankz=<<<eof
{$main_name}{$lt}{$sub_name}
eof;

			return $catg_pankz;
	}
/**
 * フロント画面カテゴリー一覧表示関数
 *
 * 登録されているカテゴリー、サブカテゴリーを表示させ、画像表示画面へのリンクを張る。
 *
 *
 * @name catg_view_img_html
 * @return str catg_html
 * @access public
 */
	function catg_view_img_html(){
		$sql = " select * from catg_master where del_flag = 'N' order by catg_master_id desc";
		$res = $this->DB_ins->exec_query($sql);
		$catg_arr = $this->DB_ins->fetchall($res);
		if(is_array($catg_arr)){
		$main_catg=array();
		$catg_html='';
		foreach ($catg_arr as $key => $main_catg){
			// $main_catgがメインカテゴリーの中身
			$catg_master_id=@$main_catg[catg_master_id];
			$sql ="select * from catg_info left outer join  sub_catg_name on catg_info.sub_catg_name_id=sub_catg_name.sub_catg_name_id  where catg_master_id = {$catg_master_id} and del_flag ='N' order by catg_info.sub_catg_name_id desc";
			$res = $this->DB_ins->exec_query($sql);
			if($res){
				$sub_catg_arr = $this->DB_ins->fetchall($res);
			}
			$sub_cnt = count($sub_catg_arr);
			if($sub_cnt){
				$catg_html.="							<li><a class=\"name0\" href=\"view_img.php?m_id={$main_catg["catg_master_id"]}\" title=\"view_img\">{$main_catg["catg_name"]}</a><ul>\n";
				foreach($sub_catg_arr as $sub_key => $sub_catg){
						$catg_html.="								<li><a class=\"name0\" href=\"view_img.php?m_id={$main_catg["catg_master_id"]}&amp;s_id={$sub_catg["sub_catg_name_id"]}\" title=\"view_img\">{$sub_catg["sub_catg_name"]}</a></li>\n";
				}
				$catg_html.="							</ul></li>\n";
			}else{
				if($main_catg["catg_master_id"]==1){
					$catg_html.="							<li><a class=\"name0\"  href=\"view_img.php?m_id={$main_catg["catg_master_id"]}\" title=\"view_img\">{$main_catg["catg_name"]}</a></li>\n";
				}else{
					$catg_html.="							<li><a class=\"name0\"  href=\"view_img.php?m_id={$main_catg["catg_master_id"]}\" title=\"view_img\">{$main_catg["catg_name"]}</a></li>\n";
				}
			}
		}
		}else{
			$catg_html="<li>No Category</li>";
		}
		return $catg_html;
	}
}
?>
