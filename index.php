<?
/*====================
 * Framework
 * 
 * バージョン
 * - 0.010 : ログイン処理アルゴリズムの見直し
 * 
====================*/

/*----------
 * 初期設定
----------*/
$GLOBALS['data']['common']['version']='0.003';
$GLOBALS['data']['common']['plugin_dir']='plugin';

$_REQUEST['p'] = ($_REQUEST['p'])?$_REQUEST['p']:"common";

/*----------
 * 関数
----------*/
//class定義
$common = new system_common();

//Directory require (plugin/php)
$common->requires();

class system_common{
	
	//Load-config
	function loadConfig($plugin='common'){
		
		$file = $GLOBALS['data']['common']['plugin_dir']."/".$plugin."/config.dat";
		
		unset($data);
		
		//database
		if(file_exists($file)){
			$datas = explode("\n",file_get_contents($file));
			for($i=0,$c=count($datas);$i<$c;$i++){
				
				// #で始まる行はコメント行
				if(preg_match("/^#/",$datas[$i])){continue;}
				
				//不要文字削除
				$datas[$i] = str_replace("\r","",$datas[$i]);
				$datas[$i] = str_replace("\n","",$datas[$i]);
				
				//空行は処理無し
				if(!$datas[$i]){continue;}
				
				//分解
				$sp = explode(",",$datas[$i]);
				$data[$sp[0]] = str_replace("&#44;",",",$sp[1]);
			}
		}
		return $data;
	}
	
	//Directory require (plugin/php)
	function requires($plugin="common"){
		
		//対象plugin内のphpフォルダを取得
		$dir = $GLOBALS['data']['common']['plugin_dir']."/".$plugin."/php/";
		
		//フォルダが存在しない場合は処理しない
		if(!is_dir($dir)){return;}
		
		//フォルダ指定で「/」で終わっていない場合は、付与する
		if(!preg_match("@\/$@",$dir)){$dir.= '/';}
		
		//対象フォルダ内のファイル一覧取得
		$php = scandir($dir);
		
		//フィアル別処理
		for($i=0,$c=count($php);$i<$c;$i++){
			
			//システムファイルは無視 || phpファイル以外は無視
			if($php[$i]=='.' || $php[$i]=='..' || !preg_match('/^(.*)\.php$/',$php[$i])){continue;}
			
			//include処理
			require_once $dir.$php[$i];
		}
	}
}

/*----------
 * 認証 -> 表示
----------*/

//初期設定----------

//Load-config
$GLOBALS['data']['common']['config'] = $common->loadConfig();

//認証処理
session_start();


//認証（ログイン）判別処理----------
$login = new LOGIN();
$login->auth($_REQUEST['m']);

//アカウント登録

//ログアウト

//OAUTH認証(open-id)

//認証後

//HTML表示処理(対象プラグインの実行)----------


$p = ($_REQUEST['p'])?$_REQUEST['p']:"common";
$m = ($_REQUEST['m'])?$_REQUEST['m']:"index";
$f = ($_REQUEST['f'])?$_REQUEST['f']:"common";
$dir = $GLOBALS['data']['common']['plugin_dir']."/".$p."/html/";

//$view = new view();
//$view->html($p,$m);
//フレーム表示
$template = new template($dir.$m.".html");

//die($dir.$f.".html:".$dir.$m.".html:".$GLOBAL['data']['common']['message']);
//コンテンツ
$GLOBALS['data']['html'] = $template->file2HTML($dir.$m.".html");
//die($GLOBALS['data']['html']);
//die($dir.$f.".html"."/".$GLOBALS['data']['html']);
//die($GLOBAL['data']['common']['message']);
//フレーム
echo $template->file2HTML($dir.$f.".html");



exit();



