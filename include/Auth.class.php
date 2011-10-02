<?php
require_once("Db.class.php");
/**
 * 認証クラス
 *
 * ユーザー認証クラス
 *
 *
 * @package Auth
 * @access  public
 * @author  KOUNO Hiroomi <hk@angeltale.net>
 * @version
 *
 */
class Auth{
/**
 * DBのインスタンスを格納
 *
 * @name DB_ins
 * @access public
 * @var instance
 */
	public $DB_ins;
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
		$this->setting_arr = $setting;
		$this->DB_ins = new Db();
	}
/**
 * ログイン関数
 *
 * ユーザ名とパスワードを引数として取り、DBへ問い合わせ、
 * 一致するユーザーがあれば、ユーザ名をセッション変数へ格納し返す。
 * 一致しない場合はセッション変数を初期化し、状態に合わせた文字列を返す。
 *
 * @name auth_user
 * @param str name
 * @param str password
 * @return str
 * @access public
 */
	function auth_user($name,$password){
		if($name && $password){
			 $name = $this->DB_ins->escape($name);
			 $password = $this->DB_ins->escape($password);
			 $query = <<<EOF
select * from user_auth
where
user_name = '{$name}'
and passwd = '{$password}'
and del_flag = 'N'
EOF;

			$result = $this->DB_ins->exec_query($query);
			$array = $this->DB_ins->fetch($result);
			if($array){
				//print_r($array);
				$_SESSION["userid"] = $array["user_name"];
				//$_SESSION["user_type"] = $array["user_type"];
				$_SESSION["user_name"] = $array["user_name"];
				return $_SESSION["userid"];
			}else {
				$_SESSION["userid"]="";
				$_SESSION["user_type"] = "";
				$_SESSION["user_name"] = "";
		 		return "no_entry";
			}
		}else{
		 	if($_SESSION["userid"]){
		 		return $_SESSION["userid"];
			 }else{
			 	return "not_login";
			 }
		 }
	}
/**
 * 認証関数
 *
 * ユーザ名とパスワード、ログアウトフラグを引数として取り、ログイン関数にて
 * ユーザの認証をかけ、認証、未ログイン、ログイン失敗などに切り分ける。
 * 認証が通った場合は、
 *
 * @name auth_login
 * @param str name
 * @param str password
 * @return str
 * @access public
 */
	function auth($name,$password,$log_off){
		$setting=$this->setting_arr;
		if($log_off){
			$message = "please login";
			$_SESSION["userid"]="";
			$_SESSION["user_type"] = "";
			$_SESSION["user_name"] = "";
			$_SESSION["new_product"] = "";
			$_SESSION["file"] = "";
			$_SESSION["img_path"] = "";
			header('location:'.$setting['setting_root_url']);
			exit;
		}
		$result = $this->auth_user($name,$password);
		if($result== "no_entry"){
			header('location:'.$setting['setting_root_url'].'admin/login.php');
			exit;
		}elseif($result== "not_login"){
			header('location:'.$setting['setting_root_url'].'admin/login.php');
			exit;
		}else{
			return $result;
		}
	}
}
?>