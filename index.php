<?
/*====================
 * Framework
 * 
 * バージョン
 * - 0.010 : ログイン処理アルゴリズムの見直し
 * - 0.020 : OpenIDにmixiを追加
 * - 0.030 : 
====================*/

/*----------
 * 初期設定
----------*/
//$GLOBALS['data']['common']['version']='0.003';
//$GLOBALS['data']['common']['plugin_dir']='plugin';

$GLOBALS['sys']['version'] = '0.020';//このFWの基本バージョン
$GLOBALS['sys']['plugin']  = 'plugin';//Plugin-Directory
$GLOBALS['sys']['common']  = 'common';//Default-mode

$GLOBALS['view']['message']  = '';//画面表示用メッセージ（システム利用分）
$GLOBALS['view']['contents'] = '';//コンテンツ箇所の内部HTML


//$_REQUEST['p'] = ($_REQUEST['p'])?$_REQUEST['p']:"common";

/*----------
 * 関数
----------*/
//class定義
$common = new system_common();

//Directory require (plugin/php)
$common->requires();

class system_common{
	
	//Load-config
	function loadConfig($data_file='common/config.dat'){
		
		$file = "data/".$data_file;
		
		unset($data);
		
		//database
		if(file_exists($file)){
			$datas = explode("\n",file_get_contents($file));
			
			$lines="";
			
			for($i=0,$c=count($datas);$i<$c;$i++){
				
				if($datas[$i]==""){continue;}
				
				// #で始まる行はコメント行
				//if(preg_match("/^".'#'."/",$datas[$i])){continue;}
				$d1 = explode("#",$datas[$i]);
				$datas[$i] = $d1[0];
				
				//$d2 = explode("//",$datas[$i]);
				//$datas[$i] = $d2[0];
				
				
				$lines.= $datas[$i]."\n";
			}
			//die($lines);
			if($lines){
				$data = json_decode($lines,true);
			}
			
		}
		return $data;
	}
	/*
	function loadConfig($p='common'){
		
		$file = "data/".$p."/config.bak";
		
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
				$data[$sp[0]] = $sp[1];
				//$data[$sp[0]] = str_replace("&#44;",",",$sp[1]);
			}
		}
		return $data;
	}
	*/
	
	//Directory require (plugin/php)
	function requires($plugin="common"){
		
		//対象plugin内のphpフォルダを取得
		$dir = $GLOBALS['sys']['plugin']."/".$plugin."/php/";
		
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
$GLOBALS['sys']['config'] = $common->loadConfig();
//print_r($GLOBALS['sys']['config']);exit();
$GLOBALS['sys']['openid'] = $common->loadConfig("common/openid.dat");
//echo "test:";print_r($GLOBALS['sys']['openid']);exit();

//認証処理
session_start();


//認証（ログイン）判別処理----------
$login = new LOGIN();
$login->auth($_REQUEST['m']);


//HTML表示処理(対象プラグインの実行)----------


$p = ($_REQUEST['p'])?$_REQUEST['p']:"common";
$m = ($_REQUEST['m'])?$_REQUEST['m']:"index";
$f = ($_REQUEST['f'])?$_REQUEST['f']:"common";
$dir = $GLOBALS['sys']['plugin']."/".$p."/html/";
//die($dir." : ".$m." : ".$f." : ");


//pluginのモジュール一括読み込み
if($_REQUEST['p']!='common'){
	$common->requires($_REQUEST['p']);
}


//フレーム表示
$template = new template();

//コンテンツ
$GLOBALS['view']['html'] = $template->file2HTML($dir.$m.".html");

//フレーム
echo $template->file2HTML($dir.$f.".html");



exit();



