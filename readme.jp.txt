0.必須アプリ等
・php 5.x系
　　php-gd,php-mysql,php-mbstring
・mysql 5.x系
1.ファイルの設置
・公開フォルダに移動
$ cd /web/public/
・ファイルを取得
$ wget http://www,prim.jp/project/pictscape.tar.gz
$ tar zxvf pictscape.tar.gz
$ cd pictscape/include
・設定ファイルをコピー
$ cp config.inc.org config.inc
$ vi config.inc
下記を設定
*は必須項目
  * $CONFIG['db_host'] = 'localhost';	//データベースサーバー
  * $CONFIG['db_user'] = 'pictscape';	//データベース接続ユーザー
  * $CONFIG['db_passwd'] = 'pictscape';   //データベース接続パスワード
  * $CONFIG['db_name'] = 'pictscape';   //データベース名
    $setting['setting_img_size'] = 600;	//画像長辺サイズ
    $setting['setting_thum_size'] = 150;	//サムネイル画像長辺サイズ
    $setting['setting_jpg_quality'] = 80;	//JPEG保存時画質
  * $setting['setting_app_path'] = '/home/ucn/public_html/pictscape/'; //設置パス
  * $setting['setting_upload_path'] = '/home/ucn/public_html/pictscape/upload/'; //画像一括保存パスアップロード先
  * $setting['setting_root_url']='http://est.angeltale.net/pictscape/';	//公開URL
    $setting['setting_title']='pictscape';	//タイトル
    $setting['setting_h1_title']='pictscape';	//h1タイトル
    $setting['setting_caption_title']='ピクトスケープ';	//h1下キャプション
    $setting["to_mail"]='hoge@hoge.com';	//メール送信先
    $setting["to_subject"]='hogehoge';	//送信メール件名
    $setting["from_mail"]='hoge@hoge.com';	//送信メール送信元
    $setting["from_name"]='hoge';	//送信メール送信元名
$ su -
# cd /web/public/pictscape/
# chmod -R 777 ./js ./img ./thum ./upload

2.DBの用意
mysqlをインストールしておいてください。
# vi /etc/my.cnf
[mysqld]
の下に以下を追記
＊＊＊ここから＊＊＊
default-character-set=utf8
skip-character-set-client-handshake
＊＊＊ここまで＊＊＊
#  /etc/init.d/mysqld restart
$ mysql -u root -p
Enter password: :設定したMySQLのパスワード
・DBの作成
mysql> create database pictscape;
・DBユーザーの作成
mysql> grant all privileges on {データベース名}.* to {データベース接続ユーザー}@localhost identified by '{データベース接続パスワード}';
mysql> exit
・初期テーブルの流し込み
$ mysql -u {データベース接続ユーザー} -p {データベース名} < ./pictscape.copy.sql
Enter password: 設定したDBユーザーのパスワード

3.
