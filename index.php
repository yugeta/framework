<?
/*====================
 * Framework
 * 
 * バージョン
 * - 0.010 : ログイン処理アルゴリズムの見直し
 * - 0.020 : OpenIDにmixiを追加
 * - 0.030 : plugin/common -> system/common
====================*/

/*----------
 * 初期設定
----------*/
$GLOBALS['sys']['version'] = '0.030';//このFWの基本バージョン
$GLOBALS['sys']['data']    = 'data';//Data-Directory
$GLOBALS['sys']['system']  = 'system';//System-Directory
$GLOBALS['sys']['plugin']  = 'plugin';//Plugin-Directory
$GLOBALS['sys']['common']  = 'common';//Default-mode
define("SYS_COMMON","common");

$GLOBALS['view']['message']  = '';//画面表示用メッセージ（システム利用分）
$GLOBALS['view']['contents'] = '';//コンテンツ箇所の内部HTML


/*----------
 * 関数
----------*/
$system_class = $GLOBALS['sys']['system']."/".$GLOBALS['sys']['common']."/php/common.php";
if(!file_exists($system_class)){
	echo "Error (system file not found.) : ".$system_class;
	exit();
}

//class定義
require_once $system_class;
$common = new SYSTEM_COMMON();

//Directory require (plugin/php)

$system_lists = scandir($GLOBALS['sys']['system']);

for($i=0,$c=count($system_lists);$i<$c;$i++){
	$common->requires($GLOBALS['sys']['system']."/".$system_lists[$i]."/php/");
}

/*
$common->requires($GLOBALS['sys']['system']."/".$GLOBALS['sys']['common']."/php/");
$common->requires($GLOBALS['sys']['system']."/openid/php/");
$common->requires($GLOBALS['sys']['system']."/bootstrap/php/");
*/



/*----------
 * 認証 -> 表示
----------*/

//初期設定----------

//Load-config
$GLOBALS['sys']['config'] = $common->loadConfig($GLOBALS['sys']['data']."/".$GLOBALS['sys']['common']."/config.dat");
$GLOBALS['sys']['openid'] = $common->loadConfig($GLOBALS['sys']['data']."/".$GLOBALS['sys']['common']."/openid.dat");

//認証処理
session_start();


//認証（ログイン）判別処理----------
$login = new LOGIN();
$login->auth($_REQUEST['m']);


//HTML表示処理(対象プラグインの実行)----------
$p = ($_REQUEST['p'])?$_REQUEST['p']:$GLOBALS['sys']['common'];
$m = ($_REQUEST['m'])?$_REQUEST['m']:"index";
$f = ($_REQUEST['f'])?$_REQUEST['f']:$GLOBALS['sys']['common'];


//pluginのモジュール一括読み込み
if($m=="login"){
	$dir = $GLOBALS['sys']['system']."/login/html/";
}
else if(!$_REQUEST['p'] || $_REQUEST['p']==$GLOBALS['sys']['common']){
	if($_REQUEST['p2'] ){
		$dir = $GLOBALS['sys']['system']."/".$_REQUEST['p2'] ."/html/";
	}
	else{
		$dir = $GLOBALS['sys']['system']."/".$p."/html/";
	}
}
//plugin処理
else{
	$dir = $GLOBALS['sys']['plugin']."/".$p."/html/";
	$common->requires($GLOBALS['sys']['plugin']."/".$_REQUEST['p']."/php/");
}



//フレーム表示
$template = new template();

//コンテンツ
if($_REQUEST['class'] && class_exists($_REQUEST['class']) && $_REQUEST['function'] && method_exists($_REQUEST['class'],$_REQUEST['function'])){
	$GLOBALS['view']['html'] = call_user_func(array($_REQUEST['class'],$_REQUEST['function']));
}
else{
	$GLOBALS['view']['html'] = $template->file2HTML($dir.$m.".html");
}

//フレーム
//echo $template->file2HTML($dir.$f.".html");
if($_REQUEST['p'] && $_REQUEST['frame'] && is_file($GLOBALS['sys']['plugin']."/".$$_REQUEST['p']."/tpl/".$_REQUEST['frame'])){
	$frame = $GLOBALS['sys']['plugin']."/".$$_REQUEST['p']."/tpl/".$_REQUEST['frame'];
}
else{
	$frame = $GLOBALS['sys']['system']."/template/tpl/".$GLOBALS['sys']['config']['tpl_frame'];
}
echo $template->file2HTML($frame);



exit();



