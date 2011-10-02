window.onload  = function(){
		// メインカテゴリーリストを定義
		var bunruiA = new Array();
		var bunruiAid = new Array();
		bunruiA = new Array("NEWS","未登録")

		bunruiAid = new Array("2","1")

	try{
		// 分類Aの選択リストを作成
		createSelection(form1.elements['sel_main'], "(メインカテゴリー)", bunruiAid, bunruiA);
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
		bunruiB["2"]= new Array();
bunruiB["1"]= new Array();
		bunruiBid["2"]= new Array();
bunruiBid["1"]= new Array();
			// 選択肢を動的に生成
			createSelection(form1.elements['sel_sub'], "(サブカテゴリー)",bunruiBid[obj.value], bunruiB[obj.value]);

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