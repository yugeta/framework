<?
/*====================
 Framework
 
* バージョン
- 0.010 : ログイン処理アルゴリズムの見直し
- 0.020 : OpenIDにmixiを追加
- 0.030 : plugin/common -> system/common
- 0.100 : 全体構成の変更※system関数のセグメント化、クエリ構成の変更

====================*/


//classライブラリの読み込み*common.phpの読み込み
require_once "common.php";
$common = new SYSTEM_COMMON();

//session処理開始
session_start();

//定数設定（各種初期値）
$common->setDefine();

//コンフィグデータ読み込み
$common->setConfig();

//argv->request（cliのみ）
$common->argc2request();

//query-set
$common->setQuery();

//"system"内のphpは全てincludeする
$common->getSystemPhpModule();

//認証処理
$login_flg = $common->checkLogin($_REQUEST['m']);


/*==========
 表示
==========*/

//未認証の場合->ログイン画面
$common->viewHTML($login_flg);

/*
$template = new TEMPLATE();

if($login_flg['message']){
	$GLOBALS['view']['message'] = $login_flg['message'];
}
//認証済み
if($login_flg['flg']){
	//$GLOBALS['view']['html'] = "OK";
	//認証直後->リダイレクト処理
	if($login_flg['action']=="redirect"){
		$url = new URL();
		
		header("Location: ".$url->getUrl());
		
		if(!$_REQUEST['p'] && $_REQUEST['p']=='common'){
			header("Location: ".$url->getUrl());
		}
		else{
			header("Location: ".$url->getUrl()."?p=".$_REQUEST['p']);
		}
	}
	//認証後の通王画面
	else{
		
		//$GLOBALS['view']['html'] = $template->file2HTML($common->getPluginData($_REQUEST['p'],$_REQUEST['c'],$_REQUEST['f'],$_REQUEST['data'],$_REQUEST['h']));
		$GLOBALS['view']['html'] = $common->getPluginData($_REQUEST['p'],$_REQUEST['c'],$_REQUEST['f'],$_REQUEST['data'],$_REQUEST['h']);
	}
}
//未認証
else{
	$GLOBALS['view']['html'] = $template->file2HTML(_SYSTEM."/login/html/login.html");
}



echo $template->file2HTML($common->getFramePath($_REQUEST['page'],$_REQUEST['frame']));
*/
//$login_flg = $login->auth($_REQUEST['m']);

/*
//pluginのモジュール一括読み込み
if($m=="login"){
	$dir = _SYSTEM."/login/html/";
}
else if(!$_REQUEST['p'] || $_REQUEST['p']==_COMMON){
	if($_REQUEST['p2'] ){
		$dir = _SYSTEM."/".$_REQUEST['p2'] ."/html/";
	}
	else{
		$dir = _SYSTEM."/".$p."/html/";
	}
}
//plugin処理
else{
	$dir = _PLUGIN."/".$p."/html/";
	$common->requires(_PLUGIN."/".$_REQUEST['p']."/php/");
}
*/

/*
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
if($_REQUEST['p'] && $_REQUEST['frame'] && is_file(_PLUGIN."/".$$_REQUEST['p']."/tpl/".$_REQUEST['frame'])){
	$frame = _PLUGIN."/".$$_REQUEST['p']."/tpl/".$_REQUEST['frame'];
}
else{
	$frame = _SYSTEM."/template/tpl/".$GLOBALS['sys']['config']['tpl_frame'];
}
echo $template->file2HTML($frame);
*/

exit();





