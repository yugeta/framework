<?
/*====================
 Framework
 
* バージョン
- 0.010 : ログイン処理アルゴリズムの見直し
- 0.020 : OpenIDにmixiを追加
- 0.030 : plugin/common -> system/common
- 0.100 : 全体構成の変更※system関数のセグメント化、クエリ構成の変更

====================*/

/*----------
 * 初期設定
----------*/

//session処理開始
session_start();

//classライブラリの読み込み
$common = new SYSTEM_COMMON();

//定数設定（各種初期値）
$common->setDefine();

//query-set
$common->query_setting();

/*
$GLOBALS['sys']['version'] = '0.030';//このFWの基本バージョン
$GLOBALS['sys']['data']    = 'data';//Data-Directory
$GLOBALS['sys']['system']  = 'system';//System-Directory
$GLOBALS['sys']['plugin']  = 'plugin';//Plugin-Directory
$GLOBALS['sys']['common']  = 'common';//Default-mode
define("SYS_COMMON","common");
*/
/*
define("_VERSION" , "0.040");
define("_COMMON"  , "common");
define("_SYSTEM"  , "system");
define("_PLUGIN"  , "plugin");
define("_DATA"    , "data");

$GLOBALS['view']['message']  = '';//画面表示用メッセージ（システム利用分）
$GLOBALS['view']['contents'] = '';//コンテンツ箇所の内部HTML
*/

/*----------
 * 関数読み込み
----------*/

$system_lists = scandir(_SYSTEM);
for($i=0,$c=count($system_lists);$i<$c;$i++){
	$common->requires(_SYSTEM."/".$system_lists[$i]."/php/");
}

/*----------
 * 認証 -> 表示
----------*/

//認証（ログイン）判別処理----------
$login = new LOGIN();
if($_REQUEST['m']=="login"){
	$login_flg = $login->setLogin($_REQUEST['id'],$_REQUEST['pw']);
}
else if($_REQUEST['m']=="logout"){
	$login_flg = $login->logout($_REQUEST['m']);
}
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




/*----------
 System Method
----------*/

class SYSTEM_COMMON{
	
	//定数設定
	function setDefine(){
		define("_VERSION" , "0.040");
		
		define("_SYSTEM"  , "system");
		define("_PLUGIN"  , "plugin");
		define("_COMMON"  , "common");
		define("_DATA"    , "data");
		
		$GLOBALS['view']['message']  = '';//画面表示用メッセージ（システム利用分）
		$GLOBALS['view']['contents'] = '';//コンテンツ箇所の内部HTML
		
		//Load-config
		$GLOBALS['sys']['config'] = $this->loadConfig(_DATA."/"._SYSTEM."/config.dat");
		$GLOBALS['sys']['openid'] = $this->loadConfig(_DATA."/"._SYSTEM."/openid.dat");
	}
	
	function query_setting(){
		
		if(isset($_REQUEST['plugin']))  {$_REQUEST['p'] = $_REQUEST['plugin'];}
		if(isset($_REQUEST['html']))    {$_REQUEST['h'] = $_REQUEST['html'];}
		if(isset($_REQUEST['mode']))    {$_REQUEST['m'] = $_REQUEST['mode'];}
		if(isset($_REQUEST['class']))   {$_REQUEST['c'] = $_REQUEST['class'];}
		if(isset($_REQUEST['function'])){$_REQUEST['f'] = $_REQUEST['function'];}
		if(isset($_REQUEST['action']))  {$_REQUEST['a'] = $_REQUEST['action'];}
		if(isset($_REQUEST['data']))    {$_REQUEST['d'] = $_REQUEST['data'];}
		if(isset($_REQUEST['system']))  {$_REQUEST['s'] = $_REQUEST['system'];}
		
	}
	
	/*
	//define
	function setDefine(){
		
		define("VERSION",'0.030');//このFWの基本バージョン
		
		define("DIR_SYSTEM",'system');//systemフォルダ
		define("DIR_PLUGIN",'plugin');//pluginフォルダ
		
		define("DEF_MODE",'index');//デフォルトmode
		define("DEF_HTML",'index.html');//デフォルトindexファイル
		
		
		
		$GLOBALS['sys']['data']    = 'data';//Data-Directory
		$GLOBALS['sys']['system']  = 'system';//System-Directory
		$GLOBALS['sys']['plugin']  = 'plugin';//Plugin-Directory
		$GLOBALS['sys']['common']  = 'common';//Default-mode
		
		define("SYS_COMMON","common");

		$GLOBALS['view']['message']  = '';//画面表示用メッセージ（システム利用分）
		$GLOBALS['view']['contents'] = '';//コンテンツ箇所の内部HTML
	}
	*/
	
	//Load-config
	function loadConfig($file=null){
		
		//ファイル指定がない場合はnllを返す
		if(!$file){return;}
		
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
	
	//html-view
	function html($plugin="common",$file="common"){
		
		//$common = new system_common();
		$template = new template();
		//$file_mode = 'plugin/'.$plugin.'/html/common.html';
		
		if($file){
			if(!$plugin || $plugin==_COMMON){
				$file = _SYSTEM."/".$plugin."/html/".$file;
			}
			else{
				$file = _PLUGIN."/".$plugin."/html/".$file;
			}
			
			if(file_exists($file)){
				$GLOBALS['view']['html'] = $template->file2HTML($file);
			}
		}
		else{
			$file = "common";
		}
		
		echo $template->key2HTML($plugin,$file);
		
	}
	
	// login / logout html-value
	function html_logout(){
		if($_SESSION['id']){
			return '<a href="?m=logout">Logout</a>';
		}
		else{
			return '<a href="./">Login</a>';
		}
	}
}
