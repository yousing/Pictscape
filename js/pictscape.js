function settingcheck(){
	var error_msg = 0;
	// 設定開始（必須にする項目を設定してください）
	if(document.form1.setting_img_size.value == ""){ // イメージサイズ
		error_msg = 1;
	}
	if(document.form1.setting_thum_size.value == ""){ //
		error_msg = 1;
	}
	if(document.form1.setting_jpg_quality.value == ""){ // 「コメント」の入力をチェック
		error_msg = 1;
	}
	if(document.form1.setting_thum_save_path.value == ""){ // 「コメント」の入力をチェック
		error_msg = 1;
	}
	if(document.form1.setting_upload_path.value == ""){ // 「コメント」の入力をチェック
		error_msg = 1;
	}
	// 設定終了
	if(flag){
		window.alert('必須項目に未入力がありました'); // 入力漏れがあれば警告ダイアログを表示
		return false; // 送信を中止
	}
	else{
		return true; // 送信を実行
	}

}
    function regist() {
		document.getElementById('SR').action ='editfile_1.php';
		document.getElementById('SR').method ='POST';
		document.getElementById('SR').submit();
	}

    function bulk_save() {
		document.getElementById('form1').action ='newfile_bulk_1.php';
		document.getElementById('form1').method ='POST';
		document.getElementById('form1').submit();
	}

	function delete_main(catg,m_id){
		message = "メインカテゴリ:["+catg+"]とそのサブカテゴリーをすべて削除してもよろしいですか？\n";
		message += "同時にこのカテゴリに属する画像も削除されますがよろしいですか？";
		if(window.confirm(message)){
			location.href='./category_menu.php?delete_main='+m_id;
		}else{
			return false;
		}
	}

	function delete_sub(catg,s_id){
		message = "サブカテゴリー:["+catg+"]とを削除してもよろしいですか？\n";
		message += "同時にこのサブカテゴリに属する画像も削除されますがよろしいですか？";
		if(window.confirm(message)){
			location.href='./category_menu.php?delete_sub='+s_id;
		}else{
			return false;
		}
	}

	function del_check(){
		window.confirm("チェックされた画像を削除しますがよろしいですか？");
	}

	var count;
function BoxChecked(){
	chked_cnt=CheckedCnt();
	if(chked_cnt!=document.getElementById('form1').c001.length){
		check=true;
	}else{
		check=false;
	}
	for(count=0; count<document.getElementById('form1').c001.length; count++) {
		document.getElementById('form1').c001[count].checked = check;
	}
}
function CheckedCnt(){
	//チェックされた数
	true_cnt=0;
	for(count=0; count<document.getElementById('form1').c001.length; count++) {
		if(document.getElementById('form1').c001[count].checked){
			true_cnt++;
		}
	}
	return true_cnt;
}
