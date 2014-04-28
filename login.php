<?
/*====================
 Framework - login
 
 * 機能
 - ログイン認証処理
 - ログアウト処理
 - openidによる認証処理
 - 認証後、index.phpへの受け渡し処理

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
$common->viewLogin($login_flg);



exit();





