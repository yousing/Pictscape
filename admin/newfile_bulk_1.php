<?php
include '../include/tmp.inc';
include_once '../include/Html.class.php';
$html_ins = new Html($_SERVER['PHP_SELF']);
$header = $html_ins->html_header();
$content_header = $html_ins->html_content_header();
$menu_text = $html_ins->html_menu();
$footer_text = $html_ins->html_footer();

$str=<<<eof
{$header}
	<body onLoad="regist();">
		<div class="body0">
	{$content_header}
 		<form id="SR">
			<div class="contents0">
				<div class="content0">
				<p>ただいま処理中、少し時間がかかることがあります。</p>
				<img class="anime" src="../images/sakura_anime0.gif " alt="sakura_anime" />
				</div>
			</div>
		</form>
{$footer_text}
		</div>
	</body>
</html>

eof;

echo $str;
echo str_pad(" ",4096)."<br />\n";
ob_end_flush();
ob_start('mb_output_handler');
ob_flush();
flush();

if(@$_POST["newfile_bulk"]){
	set_time_limit(0);
	include '../include/Pict.class.php';
	if(!@$_POST["sel_main2"]){
		$_POST["sel_main2"]=1;
	}
	$_SESSION["m_id"]=@$_POST["sel_main2"];
	$_SESSION["s_id"]=@$_POST["sel_sub2"];
	$pict_ins = new Pict();
	$size_set = @$_POST["size_set"];
	$pict_ins->pict_set_seiz($size_set);
	$m_id = @$_POST["sel_main2"];
	$s_id = @$_POST["sel_sub2"];
	$pict_ins->pict_set_id($m_id,$s_id);
	$_SESSION["false_cnt"] = $pict_ins->pict_bulk_conversion();
}

?>