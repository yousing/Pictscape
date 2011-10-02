<?php
require_once 'Db.class.php';
/**
 * 設定関連クラス
 *
 * DB上の環境変数を設定する。
 *
 * @package Setting
 * @access public
 * @author KOUNO Hiroomi <hk@angeltale.net>
 * @version
 *
 */
class Setting{
/**
 * DBのインスタンスを格納
 *
 * @name DB_ins
 * @access public
 * @var instance
 */
	public $DB_ins;
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
 * @param str file
 * @access public
 */
	function __construct(){
		$this->DB_ins = new Db();
	}
/**
 * 設定読み込み
 *
 * 現在の設定を読み込み、フィールド名での連想配列に格納する。
 *
 * @name set_read_Db
 * @access public
 */
	function set_read_Db(){
		if(!$this->setting){
			$sql = "select * from setting_master";
			$res = $this->DB_ins->exec_query($sql);
			$this->setting = $this->DB_ins->fetch($res);
			return $this->setting;
		}else{
			return $this->setting;
		}
	}
/**
 * 設定更新
 *
 * ポストされた設定でDBを更新する。
 *
 * @name set_read_Db
 * @param arr data
 * @access public
 */
	function set_updata($data){
		foreach ($data as $key => $value){
			if(preg_match('/^setting_.*/',$key)){
				$value = $this->DB_ins->escape($value);
				$sql = " update setting_master set {$key}='{$value}'";
				$res = $this->DB_ins->exec_query($sql);
				if(!$res){
					echo $sql."this Query error!!";
				}
			}
		}
	}

/**
 * パスワード更新
 *
 * ポストされた設定でDBを更新する。
 *
 * @name set_update_pass
 * @param arr data
 * @access public
 */
	function set_update_pass($data,$id){
		$errmsg="";
		$newpass = $this->DB_ins->escape($data["newpass"]);
		$renewpass = $this->DB_ins->escape($data["renewpass"]);
		if($newpass==""){
			$errmsg = "パスワードが空白です。入力してください。";
			return $errmsg;
		}else if($renewpass==""){
			$errmsg = "確認用パスワードが空白です。入力してください。";
			return $errmsg;
		}else if($newpass != $renewpass){
			$errmsg = "パスワードが確認用パスワードと異なります、同じパスワードを入力してください。";
			return $errmsg;
		}else if(preg_match("/^[a-zA-Z0-9]+$/", $newpass)==false){
			$errmsg = "パスワードは、半角英数で入力してください。";
			return $errmsg;
		}else{
			$sql = " update user_auth set passwd ='{$newpass}' where user_name = '{$id}'";
			$res = $this->DB_ins->exec_query($sql);
			if(!$res){
				$errmsg = $sql."this Query error!!";
				return $errmsg;
			}else{
				return "true";
			}
		}
	}
}
?>