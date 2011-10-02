<?php
require_once 'phpExifWriter.php';
require_once 'Db.class.php';
/**
 * 画像処理クラス
 *
 * EXIFライブラリのphpexifRWとPHPの通常関数で、画像処理を行う。
 *
 * @package Pict
 * @access public
 * @author KOUNO Hiroomi <hk@angeltale.net>
 * @version
 *
 */

class Pict{
/**
 * 元画像でのphpeExifRWのインスタンスを格納
 *
 * @name Exifrw_soruce_ins
 * @access	public
 * @var str
 */
	public $Exifrw_soruce_ins;
/**
 * ファイルパスを格納
 *
 * @name pict_path
 * @access	public
 * @var str
 */
	public $pict_path;

/**
 * DBインスタンスを格納
 *
 * @name db_ins
 * @access	public
 * @var instance
 */
	public $db_ins;
/**
 * EXIF情報を格納
 *
 * @name exif
 * @access	public
 * @var str
 */
	public $exif;
/**
 * 画像形式を格納
 *
 * @name img_mime
 * @access public
 * @var str
 */
	public $img_mime;
/**
 * 設定情報を格納
 *
 * @name setting
 * @access public
 * @var instance
 */
	public $setting;
/**
 * 画像の元の名前を格納
 *
 * @name org_name
 * @access public
 * @var string
 */
	public $org_name;
/**
 * メインカテゴリーIDを格納
 *
 * @name main_id
 * @access public
 * @var int
 */
	public $main_id;
/**
 * サブカテゴリーIDを格納
 *
 * @name sub_id
 * @access public
 * @var int
 */
	public $sub_id;
/**
 * タイトルを格納
 *
 * @name title
 * @access public
 * @var int
 */
	public $title;
/**
 * イメージIDの配列を格納
 *
 * @name id_arr
 * @access public
 * @var int
 */
	public $id_arr;
/**
 * 任意の画像サイズを格納
 *
 * @name set_size
 * @access public
 * @var int
 */
	public $set_size;
/**
 * 画像ソート種別番号を格納
 *
 * @name sort_num
 * @access public
 * @var int
 */
	public $sort_num;
/**
 * コンストラクト
 *
 * DBのインスタンスを作成する。
 * パスが渡されている場合は、クラス変数に格納する。
 *
 * @name __construct
 * @param str file
 * @access public
 */
	function __construct($file="",$org_name=""){
		if($file){
			$this->pict_path = $file;
		}
		if($org_name){
			$this->org_name = $org_name;
		}
		$this->db_ins = new Db();
		$setting = array();
		include 'config.inc';
		$this->setting = $setting;
	}
/**
 * カテゴリーセット関数
 *
 * 引数で与えられた、メインカテゴリーIDとサブカテゴリーIDをクラス変数にセットする。
 *
 * @name pict_set_id
 * @param main_id
 * @param sub_id
 * @access public
 */
	function pict_set_id($main_id=0,$sub_id=0){
		if($main_id==0){
			$main_id=1;
		}
		$this->main_id = (int)$main_id;
		$this->sub_id = (int)$sub_id;
	}
/**
 * タイトルセット関数
 *
 * 引数で与えられた、タイトルをクラス変数へセットする
 *
 * @name pict_set_id
 * @param str $title
 * @access public
 */
	function pict_set_title($title){
		$this->title = $title;
	}
/**
 * 一括変更リストセット関数
 *
 * 引数で与えられたイメージIDの配列をクラス変数へセットする
 *
 * @name pict_set_id_list
 * @param array $id_arr
 * @access public
 */
	function pict_set_id_list($id_arr){
		$this->id_arr = $id_arr;
	}
/**
 * 長辺サイズセット関数
 *
 * ポストされた任意のサイズを設定
 *
 * @name pict_set_seiz
 * @param int set_size
 * @access public
 */
 	function pict_set_seiz($size){
		$this->set_size = $size;
	}
/**
 * sort種別セット関数
 *
 * @name pict_set_sort
 * @param int sort_num
 * @access public
 */
	function pict_set_sort($sort_num){
		$this->sort_num = (int)$sort_num;
	}


/**
 * 画像縮小
 *
 * サムネイルフラッグを引数として取り、
 * サイズ、JPEG保存品質、保存パスをDBより取得し縮小画像を生成行う。
 * JPEGについてはpict_imgrotateにて、生成を行う。
 *
 * pict_exifRWによりJPEGのExif情報引継ぎも行う。
 *
 * @name pict_reduced
 * @param str $thum_flag
 * @access public
 * @see pict_imgrotate
 * @see pict_exifRW
 */
	function pict_reduced($thum_flag=""){
		$log="";
		$image="";
		$new_image="";
		$log .= "関数呼び出し時".memory_get_usage(). "\n";
		$img_info = getimagesize ($this->pict_path);
		// 設定取得
		/* 設定クラス関数に置き換え
		$sql = "select * from setting_master";
		$res = $this->db_ins->exec_query($sql);
		$setting	= $this->db_ins->fetch($res);
		*/
		//var_dump($setting);
		if($thum_flag){
			$size = $this->setting["setting_thum_size"];
			$img_size = "thum_";
		}else{
			//echo $this->set_size;
			if($this->set_size=='0'){
				$org_width = $img_info[0];
				$org_heigth = $img_info[1];
				//縦　横
				if($org_width<$org_heigth){
					$size=$org_heigth;
				}else{
					$size=$org_width;
				}
			}else if(isset($this->set_size) and $this->set_size!=0){
				$size = $this->set_size;	//ポスト時に設定された値
			}else{
				$size = $this->setting["setting_img_size"];		//設定ファイルに設定された値
			}
			//echo $size;
			$img_size = "view_";
		}
		//echo "$this->pict_path";
		$img_type = $img_info[2];
		$this->img_mime = $img_type;
		//echo $img_type;
		if($thum_flag){
			$file_path = $this->setting["setting_app_path"]."/thum/";
		}else{
			$file_path = $this->setting["setting_app_path"]."/img/";
		}

		$img_name=uniqid($img_size);
		$file_name = $file_path.$img_name;
		/**
		 * 画像形式
		 * 1=gif
		 * 2=jpg
		 * 3=png
		 */
		// 元画像のリソースを作成
		switch ($img_type){
			case 1:
				 $image = @ImageCreateFromGIF($this->pict_path);
			break;
			case 2:
				$exif_arr=exif_read_data($this->pict_path);
				//var_dump($exif_arr);
				$model = @$exif_arr["Model"];
				if($model){
					$log .= "宣言前".memory_get_usage() . "\n";
					$this->Exifrw_soruce_ins = new phpExifWriter($this->pict_path);
					$this->Exifrw_soruce_ins->caching = false;
					$log .= "宣言後".memory_get_usage() . "\n";
				}
				$log .= "生成前".memory_get_usage() . "\n";
				$image = ImageCreateFromJPEG($this->pict_path);
				$log .= "生成後".memory_get_usage() . "\n";
			break;
			case 3:
				$image = @ImageCreateFromPNG($this->pict_path);
			break;
		}
		//var_dump($size);
		//var_dump($img_info);
		$org_width = $img_info[0];
		$org_heigth = $img_info[1];
		/*
		 * 元画像の縦横を比較し、長辺に$sizeを設定する。
		 * あわせて、短編の縮小率も設定する
		 *
		 * 長辺が設定値より短い場合は元画像の値を使う。
		 *
		 */
		if($org_width < $org_heigth ){
			//echo "height long";
			if($org_heigth < $size){
				$size=$org_heigth;
			}
			$new_height = $size;
			$rate = (int)$size / (int)$org_heigth;
			$new_width = $rate * $org_width;
		}else {
			//echo "width long";
			if($org_width < $size){
				$size=$org_width;
			}
			$new_width = $size;
			//echo $org_width;
			$rate = (int)$size / (int)$org_width;
			$new_height = $rate * $org_heigth;
		}

		//echo "rate:".$rate."height:".$new_height."width:".$new_width;
		$new_image = ImageCreateTrueColor($new_width, $new_height) or die("Cannot Initialize new GD image stream");
		imagecopyresampled($new_image,$image,0,0,0,0,$new_width,$new_height,$org_width,$org_heigth);



		//echo $file_path;
		switch ($img_type){
			case 1:
				$file_name = $file_name.".gif";
				$save_name = $img_name.".gif";
				ImageGIF($new_image, $file_name);
			break;
			case 2:
				$file_name = $file_name.".jpg";
				$save_name = $img_name.".jpg";
				$jpg_quality=$this->setting["setting_jpg_quality"];
				$this->pict_imgrotate($new_image, $file_name, $jpg_quality);
				if(@$this->Exifrw_soruce_ins){
					$this->exif = $this->Exifrw_soruce_ins->getExif();
					$this->pict_exifRW($file_name);
				}
			break;
			case 3:
				$file_name = $file_name.".png";
				$save_name = $img_name.".png";
				ImagePNG($new_image, $file_name);
			break;
		}

		$img_dest_flag = imagedestroy($image);
		unset($image);
		unset($new_image);
		$this->exif = null;
		$this->Exifrw_soruce_ins = null;
		$log .= "終了時".memory_get_usage() . "\n";
		error_log($log, 3, "/tmp/php-memory.log");
		if($thum_flag){
			//$save_nameはサムネイル保存名　$thum_flagは画像保存名
			$this->pict_thum_db_insert($save_name,$thum_flag);
			unlink($this->pict_path);
		}else{
			$this->pict_db_insert($save_name);
			return $save_name;
		}
	}
/**
 * 表示画像セーブ処理
 *
 * 画像の登録処理を行う、
 * 回転の必要があるときのみ、画像の再構築を行い、それ以外の場合は、下のデータを残す。
 * JPEGについてはpict_imgrotateにて、生成を行う。
 *
 * pict_exifRWによりJPEGのExif情報引継ぎも行う。
 *
 * @name pict_reduced
 * @param str $thum_flag
 * @access public
 * @see pict_imgrotate
 * @see pict_exifRW
 */
 /*
	function pict_reduced_2(){

		//echo "$this->pict_path";
		$img_info = getimagesize ($this->pict_path);
		$img_type = $img_info[2];
		$this->img_mime = $img_type;
		//echo $img_type;

		$img_size = "view_";
		$img_name=uniqid($img_size);
		$file_path = $this->setting["setting_app_path"]."/img/";

		$file_full_name = $file_path.$img_name;
		//gif pngについては移動のみ、画像処理は行わない。
		switch ($img_type){
			case 1:
				copy($this->pict_path,$file_full_name);
			break;
			case 2:
				$exif_arr=exif_read_data($this->pict_path);
				if($exif_arr["Model"]){
					$size = $this->setting["setting_img_size"];
					$this->pel_ins = new PelJpeg($this->pict_path);
					$this->exif = $this->pel_ins->getExif();
					$jpg_quality=$this->setting["setting_jpg_quality"];
					$image = @ImageCreateFromJPEG($this->pict_path);
					$org_width = $img_info[0];
					$org_heigth = $img_info[1];
/
					if($org_width < $org_heigth ){
						//echo "height long";
						if($org_heigth < $size){
							$size=$org_heigth;
						}
						$new_height = $size;
						$rate = (int)$size / (int)$org_heigth;
						$new_width = $rate * $org_width;
					}else {
						//echo "width long";
						if($org_width < $size){
							$size=$org_heigth;
						}
						$new_width = $size;
						//echo $org_width;
						$rate = (int)$size / (int)$org_width;
						$new_height = $rate * $org_heigth;
					}
					//echo "rate:".$rate."height:".$new_height."width:".$new_width;
					$new_image = ImageCreateTrueColor($new_width, $new_height) or die("Cannot Initialize new GD image stream");
					imagecopyresampled($new_image,$image,0,0,0,0,$new_width,$new_height,$org_width,$org_heigth);
					$this->pict_imgrotate($new_image, $file_full_name, $jpg_quality);
					$this->pict_exifRW($file_full_name);
					if(is_resource($image)){
						$img_dest_flag = imagedestroy($image);
					}
					if(is_resource($new_image)){
						imagedestroy($new_image);
					}
					unset($new_image);
				}else{
					copy($this->pict_path,$file_full_name);
				}
			break;
			case 3:
				copy($this->pict_path,$file_full_name);
			break;
		}
		if(is_resource($image)){
			$img_dest_flag = imagedestroy($image);
		}
		if(is_resource($new_image)){
			imagedestroy($new_image);
		}
		unset($image);
		unset($new_image);
		$this->pict_db_insert($img_name);
		return $img_name;
	}
*/
/**
 * Exif書き込み
 *
 * 書き込み先ァイルパスを引数として取り、
 * 指定ファイルにクラス変数のexifの情報を書き込む。
 *
 * @name pict_exifRW
 * @param str $file_name
 * @access public
 */
	function pict_exifRW($file_name){
		if($this->exif){
			$to_ins = new phpExifWriter($file_name);
			$to_ins->caching = false;
			$to_ins->addExif($this->exif);
			$to_ins->writeImage($file_name);
			//file_put_contents($file_name, $to_ins->getBytes());
		}
	}
/**
 * 画像回転
 *
 * 引数として、縮小画像リソース、縮小画像ファイルパス、JPGqualityをとり、
 * 元画像のExif情報を読み取り必要であれば回転させ、JPGを縮小画像を作成する。
 *
 * @name pict_exifRW
 * @param resource $new_image
 * @param str $file_name
 * @param str $jpg_quality
 * @access public
 * @see pict_exifRW
 */
	function pict_imgrotate($new_image, $file_name, $jpg_quality){
		$exif_arr = exif_read_data($this->pict_path);
		$ort_num = @$exif_arr["Orientation"];
		//print_r($exif_arr);
		switch($ort_num){
			case 1: // nothing
			break;
			case 2: // horizontal flip
			break;
			case 3: // 180 rotate left
				$rotate=180;
			break;
			case 4: // vertical flip
			break;
			case 5: // vertical flip + 90 rotate right
			break;
			case 6: // 90 rotate right
				$rotate=-90;
			break;
			case 7: // horizontal flip + 90 rotate right
			break;
			case 8:	// 90 rotate left
				$rotate=90;
			break;
		}
		$rotate_id = imagerotate($new_image, $rotate, 0);
		$return=imagejpeg($rotate_id, $file_name,$jpg_quality);
		imagedestroy($rotate_id);
		imagedestroy($new_image);
		return $return;
	}

/**
 * 画像情報インサート関数
 *
 * @name pict_db_insert
 * @access public
 * @see pict_reduced
 *
 */
	function pict_db_insert($img_name){
		$DateTimeOriginal= null;
		/*
		 * インスタンス作成時にファイル名を与えられていれば
		 * そちらを使う。
		 */
		if($this->org_name){
			$org_name = $this->org_name;
		}else{
			$org_name = basename($this->pict_path);
		}
		if($this->img_mime==2){
			$exif = exif_read_data($this->pict_path);
			$DateTimeOriginal=@$exif["DateTimeOriginal"];
		}
		if($DateTimeOriginal){
			$datetime_org = "'".$DateTimeOriginal."'";
		}else{
			$datetime_org="now()";
		}
		if($this->title){
			$alt_name=$this->title;
		}else{
			$alt_name=$org_name;
		}
		$alt_name=$this->db_ins->escape($alt_name);
		$sql = "insert into img_master
					(img_org_name,img_alt_name,img_save_name,insert_date,del_flag,app_flag,img_DateTimeOriginal)
					values ('$org_name','$alt_name','$img_name',now(),'N','N',$datetime_org)";
		$res = $this->db_ins->exec_query($sql);
		//echo $sql;
		$new_img_id = mysql_insert_id();
		if($res){
			if($this->main_id){
				$maind_id = $this->main_id;
			}else{
				$maind_id=1;
			}
			if($this->sub_id){
				$sub_id = $this->sub_id;
			}else{
				$sub_id=0;
			}
			$sql = "insert into catg_img_info
						(catg_master_id,sub_catg_name_id,img_mng_id)
						values ('$maind_id','$sub_id','$new_img_id')";
			$res = $this->db_ins->exec_query($sql);
		}
	}
/**
 * サムネイル画像情報インサート関数
 *
 * 保存画像と保存サムネイルを紐付けるDBへ画像保存名と
 * サムネイル保存名を挿入する。
 *
 * @name pict_thum_db_insert
 * @param thum_name
 * @param img_name
 * @access public
 * @see pict_reduced
 *
 */
	function pict_thum_db_insert($thum_name,$img_name){
			$sql = "insert into thum_info
						(thum_name,img_name)
						values ('$thum_name','$img_name')";
			$res = $this->db_ins->exec_query($sql);
	}
/**
 * 一括変換
 *
 * 設定フォルダのファイルを一括で表示画像とサムネイルに変換する。
 *
 * @name pict_bulk_conversion()
 * @access public
 * @see pict_reduced
 */
	function pict_bulk_conversion(){
		$log_dir=$this->setting["setting_upload_path"];
		$o_dir=opendir($log_dir);
		$false_cnt=0;
		while (false !== ($file = readdir($o_dir))) {
			if (!preg_match('/^\./',$file)) {
				if(preg_match('/(\.jpg$|\.jpeg$|\.gif$|\.png$)/i',$file)){
					$file_path = $log_dir."/".$file;
					$this->__construct($file_path);
					$save_name = $this->pict_reduced();
					$this->pict_reduced($save_name);
					unset($save_name);
					unset($file_path);
				}else{
					$file_path = $log_dir."/".$file;
					unlink($file_path);
					$false_cnt++;
				}
			}
		}
		return $false_cnt;
	}




/**
 * 一括設定変更関数
 *
 * 各セット関数でセットした情報で、画像の情報を一括で変更する。
 *
 * @name pict_bulk_setting_edit()
 * @access public
 * @see pict_reduced
 */
	function pict_bulk_setting_edit(){
		//タイトル一括変更
		foreach ($this->title as $key=>$var) {
			$var = $this->db_ins->escape($var);
			$sql="update img_master set img_alt_name = '{$var}' where img_mng_id = '{$key}'";
			//echo $sql."\n";
			$res = $this->db_ins->exec_query($sql);
		}
		//カテゴリー一括変更
		if(is_array($this->id_arr )){
			foreach ($this->id_arr as $key) {
				$sql = "update catg_img_info set catg_master_id = '{$this->main_id}' ,sub_catg_name_id = '{$this->sub_id}' where img_mng_id = '{$key}'";
				//echo $sql."\n";
				$res = $this->db_ins->exec_query($sql);
			}
		}
	}
/**
 * 画像回転
 *
 * 画像名、サムネイル名、回転角度を引数に取り、読み込んだ画像を上書きする。
 *
 * @name pict_after_imgrotate($img_name,$rand)
 * @access public
 * @param string img_name
 * @param string thum_name
 * @param rand
 * @see pict_reduced
 */
/*
	function pict_after_imgrotate($img_id,$rand){
		$img_name[0] = $this->setting["setting_save_path"].$main_name;
		$img_name[1] = $this->setting["setting_thum_save_path"].$thum_name;

		$img_info = getimagesize($img_name[0]);
		$img_type = $img_info[2];
		for($i=0;$i<2;$i++){
			switch ($img_type){
				case 1:
					 $image = ImageCreateFromGIF($img_name["$i"]);
					 $rotated_img = imagerotate ($image,$rand,0);
					 imagegif($rotated_img,$img_name["$i"]);
				break;
				case 2:
					 $exif_arr=exif_read_data($img_name["$i"]);
					if($exif_arr["Orientation"]){
						$this->pel_ins = new PelJpeg($img_name["$i"]);
					}
					$image = ImageCreateFromJPEG($img_name["$i"]);
					$rotated_img = imagerotate ($image,$rand,0);
					imagejpeg($rotated_img,$img_name["$i"],$this->setting["setting_jpg_quality"]);
					if($this->pel_ins){
						$this->exif = $this->pel_ins->getExif();
					}
					$this->pict_exifRW($img_name["$i"]);
				break;
				case 3:
					$image = ImageCreateFromPNG($img_name["$i"]);
					$rotated_img = imagerotate ($image,$rand,0);
					imagepng($rotated_img,$img_name["$i"]);
				break;
			}
			imagedestroy($image);
			imagedestroy($rotated_img);
		}
	}
*/

}
?>