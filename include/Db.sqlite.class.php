<?php
/**
 * データベースクラス
 *
 * sqlite関数にてDBへの接続、切断、クエリ実行、フェッチでの取得、エスケープ
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
		include 'config.inc';
		//var_dump($CONFIG);
		/*
		$db_host=$CONFIG["db_host"];
		$db_user=$CONFIG["db_user"];
		$db_passwd=$CONFIG["db_passwd"];
		*/
		$CONFIG=array();
		$db_file=$CONFIG["db_file"];
		$this->conection($db_file);
	}

/**
 * DB接続関数
 *
 * DB名を受け取りクラス変数$this->db_connにDBリンクを格納する
 *
 * @name conection
 * @param str $db_name
 * @access public
 * @see __construct()
 */
	function conection($db_name){
		$sqliteerror="";
		if(!$this->db_conn){
			$this->db_conn  = sqlite_open($db_name, 0666, $sqliteerror) or die('Could not connect database');
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
		sqlite_close( $this->db_conn );
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
		$sqliteerror="";
		$result = sqlite_query($query, $this->db_conn,SQLITE_ASSOC,$sqliteerror);
		if(!$result){
			 $message  = '以降のクエリエラー: ' . $sqliteerror . "<br/>\n";
			 $message .= '実行されたのは以降のクエリです<br/>' . $query;
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
		$sqliteerror="";
		$result = sqlite_query($query, $this->db_conn,SQLITE_ASSOC,$sqliteerror);
		if(!$result){
			 $message  = '以降のクエリエラー: ' . $sqliteerror . "<br/>\n";
			 $message .= '実行されたのは以降のクエリです<br/> ' . $query;
			 exit($message);
		}else{
			$inset_id = sqlite_last_insert_rowid($this->db_conn);
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
		$row = sqlite_fetch_array($res);
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
		while ($row = sqlite_fetch_all($res)) {
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
		$str = sqlite_escape_string($str);
		return $str;
	}

}
?>