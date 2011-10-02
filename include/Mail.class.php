<?php
require_once("Db.class.php");
/**
 * メールフォームクラス
 *
 * メールフォームの処理全般
 *
 *
 * @package Mail
 * @access  public
 * @author  KOUNO Hiroomi <hk@angeltale.net>
 * @version
 *
 */
class Mail {
/**
 * DBのインスタンスを格納
 *
 * @name DB_ins
 * @access public
 * @var instance
 */
	public $DB_ins;
/**
 * DBのインスタンスを格納
 *
 * @name random_str
 * @access public
 * @var string
 */
	public $random_str;
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
    function __construct(){
    	$this->DB_ins = new Db();
		$setting = array();
		include 'config.inc';
		$this->setting = $setting;
    }
/**
 * ランダムテキスト生成
 *
 *
 * @name mail_random_str
 * @access public
 */
	function mail_make_random_str(){
		//md5を使用して、ランダムテキストを生成
		$md5 = md5(microtime() * mktime());
		/*
		32文字の文字列を5文字の文字列に短縮
		*/
		$this->random_str = substr($md5,0,5);
	}
/**
 * ランダムテキスト画像生成
 *
 *
 * @name mail_random_img
 * @access public
 */
	function mail_make_random_img(){
		/*
		 * ランダムテキスト生成
		 */
		$this->mail_make_random_str();
		$string = $this->random_str;
		$image_path = $this->setting['setting_app_path']."images/captcha.png";
		$width  = 70;
		$height = 30;
		$captcha = ImageCreate($width,$height); // 下地画像
		$white = ImageColorallocate($captcha,255,255,255);
		$black = ImageColorallocate($captcha, 0, 0, 0);
		$line = ImageColorallocate($captcha,233,239,239);
		ImageFilledRectangle($captcha,0,0,$width,$height,$black); // 下地画像を白で塗りつぶす
		//$captcha = imagecreatefrompng($image_path);

		$black = ImageColorallocate($captcha, 0, 0, 0);
		$line = ImageColorallocate($captcha,233,239,239);

		imageline($captcha,0,0,39,29,$line);
		imageline($captcha,40,0,64,29,$line);

		 imagestring($captcha, 5, 13, 10, $string, $white);
		 /*
		 キーを暗号化し、セッション内に保存
		 */
		 $hash_string = md5($string);
		 $_SESSION["$hash_string"] = $hash_string;
		 /*
		 画像を出力
		 */
		 header("Content-type: image/png");
		 imagepng($captcha);
		 imagedestroy($captcha);
	}
/**
 * メール送信
 *
 *
 *
 * @name mail_send
 * @access public
 */
	function mail_send($data){

		$name = "名前 ：".$data['name_kanji']."\n";
		$mailaddress = $mail = "E-Mail：".$data['mail_address']."\n";
		$message = "本文：\n".$data['message']."\n";

		 //送信アドレス
		$to=$this->setting["to_mail"];
		//件名
		$subject = $this->setting["to_subject"];
		//本文
		$body=<<<eof
{$name}{$mailaddress}
{$message}
eof;

		//送信元メールアドレス
		$from_email = $this->setting["from_mail"];

		//送信元名前
		$from_name = $this->setting["from_name"];

		$headers = "MIME-Version: 1.0 \n" ;
		$headers .= "From: " ."".mb_encode_mimeheader(mb_convert_encoding($from_name,"ISO-2022-JP","AUTO")) .""."<".$from_email."> \n";
		$headers .= "Reply-To: " ."".mb_encode_mimeheader(mb_convert_encoding($from_name,"ISO-2022-JP","AUTO")) .""."<".$from_email."> \n";
		$headers .= "Content-Type: text/plain;charset=ISO-2022-JP \n";

		$body = mb_convert_encoding($body, "ISO-2022-JP","AUTO");

		mb_language("Ja");
		mb_internal_encoding("UTF-8");
		$result = mb_send_mail($to,$subject,$body,$headers);
		return $result;
	}
}
?>