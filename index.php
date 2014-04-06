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
//class定義
$common = new SYSTEM_COMMON();

//Directory require (plugin/php)
$common->requires($GLOBALS['sys']['system']."/".$GLOBALS['sys']['common']."/php/");

class SYSTEM_COMMON{
	
	//Load-config
	function loadConfig($file=null){
		
		if(!$file){return;}
		
		//$file = "data/".$data_file;
		
		unset($data);
		
		//database
		if(file_exists($file)){
			$datas = explode("\n",file_get_contents($file));
			
			$lines="";
			
			for($i=0,$c=count($datas);$i<$c;$i++){
				
				if($datas[$i]==""){continue;}
				
				// #で始まる行はコメント行
				$d1 = explode("#",$datas[$i]);
				$datas[$i] = $d1[0];
				
				$lines.= $datas[$i]."\n";
			}
			
			//JSON -> HASH
			if($lines){
				$data = json_decode($lines,true);
			}
			
		}
		return $data;
	}
	
	
	//Directory require (plugin/php)
	function requires($dir=null){
		
		//フォルダが存在しない場合は処理しない
		if(!$dir || !is_dir($dir)){return;}
		
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
if(!$_REQUEST['p'] || $_REQUEST['p']==$GLOBALS['sys']['common']){
	$dir = $GLOBALS['sys']['system']."/".$p."/html/";
}
//plugin処理
else{
	$dir = $GLOBALS['sys']['plugin']."/".$p."/html/";
	$common->requires($GLOBALS['sys']['plugin']."/".$_REQUEST['p']."/php/");
}



//フレーム表示
$template = new template();

//コンテンツ
$GLOBALS['view']['html'] = $template->file2HTML($dir.$m.".html");

//フレーム
echo $template->file2HTML($dir.$f.".html");



exit();



