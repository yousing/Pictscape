<?php
/**
 * データベースクラス
 *
 *MySql関数にてDBへの接続、切断、クエリ実行、フェッチでの取得、エスケープ
 * を行う
 *
 * @package Db
 * @access  public
 * @author  KOUNO Hiroomi <hk@angeltale.net>
 * @version
 *
 */
class Db{
/**
 *DBリンクを格納
 *
 * @name db_conn
 * @access  public
 * @var resource
 * @see conection()
 */
	public $db_conn;
/**
 *DB接続時のホスト名を格納
 *
 * @name db_host
 * @access  private
 * @var str
 * @see conection()
 */
	private $db_host;
/**
 *DB接続時のユーザー名を格納
 *
 * @name db_user
 * @access  private
 * @var str
 * @see conection()
 */
	private $db_user;
/**
 *DB接続時のパスワードを格納
 *
 * @name db_passwd
 * @access  private
 * @var str
 * @see conection()
 */
	private $db_passwd;
/**
 *DB接続時のデータベース名を格納
 *
 * @name db_name
 * @access  private
 * @var str
 * @see conection()
 */
	private $db_name;
/**
 *DB接続時のエラーを格納
 *
 * @name db_debug
 * @access  private
 * @var boolean
 * @see conection()
 */
	private $db_debug;
/**
 * コンストラクト
 *
 * DB接続関数connection()をよびDB接続を行う
 *
 * @name __construct
 * @access public
 * @see conection()
 */
	function __construct(){
		$CONFIG=array();
		include 'config.inc';
		//var_dump($CONFIG);
		$db_host=$CONFIG["db_host"];
		$db_user=$CONFIG["db_user"];
		$db_passwd=$CONFIG["db_passwd"];
		$db_name=$CONFIG["db_name"];
		$this->conection($db_host,$db_user,$db_passwd,$db_name);
	}

/**
 * DB接続関数
 *
 * ホスト名、ユーザ名、パスワード、DB名を受け取り
 * クラス変数$this->db_connにDBリンクを格納する
 *
 * @name conection
 * @param str $db_host
 * @param str $db_user
 * @param str $db_passwd
 * @param str $db_name
 * @access public
 * @see __construct()
 */
	function conection($db_host,$db_user,$db_passwd,$db_name){
		if(!$this->db_conn){
			$this->db_conn = mysql_connect($db_host,$db_user,$db_passwd)or die('Could not connect: ' . mysql_error());
			mysql_select_db($db_name,$this->db_conn) or die('Could not select database');
			$this->db_debug="false";
		}
	}

/**
 *
 * DB切断関数
 *
 * 接続されているDBリンクを切断する。
 *
 * @name close
 * @access public
 */
	function close( ) {
		mysql_close( $this->db_conn );
	}
/**
 *
 * クエリ実行関数
 *
 * クエリを引数として取り、クエリを実行し成功したときはリソースまたはtrue、失敗した場合はfalseを返す。
 *
 * @name exec_query
 * @param str $query
 * @return resource $result
 * @access public
 */
	function exec_query($query){
		$result = mysql_query($query, $this->db_conn);
		if(!$result){
			 $message  = '以降のクエリエラー: ' . mysql_error() . "\n";
			 $message .= '実行されたのは以降のクエリです: ' . $query;
			 exit($message);
		}else{
			return $result;
		}
	}
/**
 *
 * クエリ実行関数2
 *
 * クエリを引数として取り、クエリを実行し成功したときは実行したクエリのIDを
 * 失敗した場合はfalseを返す。
 * insert後にIDが必要な場合に使用
 *
 * @name exec_query_id
 * @param str $query
 * @return int $inset_id;
 * @access public
 */
	function exec_query_id($query){
		$result = mysql_query($query, $this->db_conn);
		if(!$result){
			 $message  = '以降のクエリエラー: ' . mysql_error() . "\n";
			 $message .= '実行されたのは以降のクエリです: ' . $query;
			 exit($message);
		}else{
			$inset_id = mysql_insert_id($this->db_conn);
			return $inset_id;
		}
	}
/**
 * fetch関数
 *
 * 取得クエリのリソースを引数として取り、1行を連想配列で返す
 *
 * @name fetch
 * @param resource $res
 * @return array $row
 * @access public
 */
	function fetch($res){
		$row = mysql_fetch_assoc($res);
		return $row;
	}
/**
 * fetchall関数
 *
 * 取得クエリのリソースを引数として取り、全行を連想配列で返す
 *
 * @name fetchall
 * @param resource $res
 * @return array $rows
 * @access public
 */
	function fetchall($res){
		$rows = array();
		while ($row = mysql_fetch_assoc($res)) {
			$rows[] = $row;
		}
		return $rows;
	}

/**
 * escape関数
 *
 * SQLインジェクション対策とし引数で渡された文字列をエスケープして返す。
 *
 * @name fetchall
 * @param resource $res
 * @return array $rows
 * @access public
 */
	function escape($str){
		$str = mysql_real_escape_string($str,$this->db_conn);
		return $str;
	}

}
?>