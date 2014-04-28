<?

/*----------
 System Method
----------*/

class SYSTEM_COMMON{
	
	//default-set
	function setDefault(){
		//IEのiframe cookie対応 ※3rd party cookie用
		header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
		
		
	}
	
	//定数設定
	function setDefine(){
		define("_VERSION" , "0.100");
		
		define("_SYSTEM"  , "system");
		define("_PLUGIN"  , "plugin");
		define("_COMMON"  , "common");
		define("_DATA"    , "data");
		
		$GLOBALS['view']['message']  = '';//画面表示用メッセージ（システム利用分）
		$GLOBALS['view']['contents'] = '';//コンテンツ箇所の内部HTML
		
	}
	
	//configデータ読み込み
	function setConfig(){
		//Load-config
		$GLOBALS['sys']['config'] = $this->loadConfig(_DATA."/"._SYSTEM."/config.dat");
		$GLOBALS['sys']['openid'] = $this->loadConfig(_DATA."/"._SYSTEM."/openid.dat");
	}
	
	//transport query define-querys
	function setQuery(){
		
		if(isset($_REQUEST['plugin']))  {$_REQUEST['p'] = $_REQUEST['plugin'];}
		if(isset($_REQUEST['html']))    {$_REQUEST['h'] = $_REQUEST['html'];}
		if(isset($_REQUEST['mode']))    {$_REQUEST['m'] = $_REQUEST['mode'];}
		if(isset($_REQUEST['class']))   {$_REQUEST['c'] = $_REQUEST['class'];}
		if(isset($_REQUEST['function'])){$_REQUEST['f'] = $_REQUEST['function'];}
		if(isset($_REQUEST['action']))  {$_REQUEST['a'] = $_REQUEST['action'];}
		if(isset($_REQUEST['data']))    {$_REQUEST['d'] = $_REQUEST['data'];}
		if(isset($_REQUEST['system']))  {$_REQUEST['s'] = $_REQUEST['system'];}
		
	}
	
	//CLIの場合argvをREQUESTに変換する。
	function argc2request(){
		
		//cliの判断はuriが存在するかどうかで行う
		if(isset($_SERVER['SCRIPT_URI']) || !count($argv)){return;}
		
		for($i=0,$c=count($argv);$i<$c;$i++){
			if(!$argv[$i]){continue;}
			//各クエリの分解
			$q = explode("=",$argv[$i]);
			if(count($q)<2){continue;}
			if($q[0]!=''){
				//requestに格納
				$key = $q[0];
				$val = join("=",array_slice($q,1));
				
				//値をセット
				$_REQUEST[$key]=$val;
			}
		}
	}
	
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
	
	/*----------
	 * 関数読み込み
	 * "system"内のphpは全てincludeする
	 // at time of start.php of system directory is include.
	----------*/
	
	function getSystemPhpModule($dir=null){
		
		if(!$dir){$dir=_SYSTEM;}
		if(!is_dir($dir)){return;}
		
		$system_lists = scandir($dir);
		for($i=0,$c=count($system_lists);$i<$c;$i++){
			$this->requires($dir."/".$system_lists[$i]."/php/");
		}
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
	function viewHTML($login_flg=array()){
		$url = new URL();
		$template = new TEMPLATE();
		
		if($login_flg['message']){
			$GLOBALS['view']['message'] = $login_flg['message'];
		}
		//認証済み
		if($login_flg['flg']){
			//認証直後->リダイレクト処理
			if($login_flg['action']=="redirect"){
				
				$index="";
				if(is_set($GLOBALS['sys']['config']['root_index']) && $GLOBALS['sys']['config']['root_index']){
					$index = $GLOBALS['sys']['config']['root_index'];
				}
				$path = $url->getDir()."/".$index;
				
				die($path);
				
				header("Location: ".$path);
				/*
				if(!$_REQUEST['p'] && $_REQUEST['p']=='common'){
					header("Location: ".$url->getUrl());
				}
				else{
					header("Location: ".$url->getUrl()."?p=".$_REQUEST['p']);
				}
				*/
			}
			//認証後の通王画面
			else{
				//$GLOBALS['view']['html'] = $template->file2HTML($common->getPluginData($_REQUEST['p'],$_REQUEST['c'],$_REQUEST['f'],$_REQUEST['data'],$_REQUEST['h']));
				//$GLOBALS['view']['html'] = $this->getPluginData($_REQUEST['p'],$_REQUEST['c'],$_REQUEST['f'],$_REQUEST['data'],$_REQUEST['h']);
				
				$plugin   = $_REQUEST['p'];
				$class    = $_REQUEST['c'];
				$function = $_REQUEST['f'];
				$data     = $_REQUEST['data'];
				$html     = $_REQUEST['h'];
				$mode     = $_REQUEST['mode'];
				
				//----------
				//target-path
				//----------
				$path="";
				if($plugin){
					$path = _PLUGIN."/".$plugin."/";
				}
				else if($GLOBALS['sys']['config']['default_plugin']){
					$path = _PLUGIN."/".$GLOBALS['sys']['config']['default_plugin']."/";
				}
				
				if(!$path || !is_dir($path)){
					$path = _SYSTEM."/"._COMMON."/";
				}
				
				//----------
				// view
				//----------
				//class & function
				if($class && class_exists($class) && $function && method_exists($class,$functon)){
					$GLOBALS['view']['html'] = call_user_func_array(array($class,$functin) , $datas);
				}
				//html
				else if($html && file_exists($path."html/".$html)){
					$GLOBALS['view']['html'] = $template->file2HTML($path."html/".$html);
				}
				
				//other:default-page
				else{
					$GLOBALS['view']['html'] = $template->file2HTML($path."html/index.html");
				}
			}
		}
		//未認証
		else{
			//configにroot_loginの指定があれば、リダイレクト処理
			if(isset($GLOBALS['sys']['config']['root_login']) && $GLOBALS['sys']['config']['root_login']){
				header("Location: ".$url->getDir()."/".$GLOBALS['sys']['config']['root_login']);
			}
			//configにroot_loginの指定がなければ、その場処理
			else{
				$GLOBALS['view']['html'] = $template->file2HTML(_SYSTEM."/login/html/login.html");
			}
		}
		
		echo $template->file2HTML($this->getFramePath($_REQUEST['page'],$_REQUEST['frame']));
	}
	
	// log-in画面
	function viewLogin($login_flg=array()){
		$url = new URL();
		$template = new TEMPLATE();
		
		if($login_flg['message']){
			$GLOBALS['view']['message'] = $login_flg['message'];
		}
		
		//認証済み*リダイレクト処理
		if($login_flg['flg']){
			$index="";
			if(isset($GLOBALS['sys']['config']['root_index']) && $GLOBALS['sys']['config']['root_index']){
				$index = $GLOBALS['sys']['config']['root_index'];
			}
			$path = $url->getDir()."/".$index;
			
			header("Location: ".$path);
		}
		//未認証
		else{
			$GLOBALS['view']['html'] = $template->file2HTML(_SYSTEM."/login/html/login.html");
		}
		
		
		echo $template->file2HTML($this->getFramePath($_REQUEST['page'],$_REQUEST['frame']));
	}
	
	/*
	function html(){
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
	*/
	// login / logout html-value
	function html_login_logout(){
		if($_SESSION['id']){
			$url = new URL();
			return '<a href="'.$url->getUrl().'?m=logout">Logout</a>';
		}
		else{
			return '<a href="./">Login</a>';
		}
	}
	
	// frame-path
	function getFramePath($page="" , $frame=""){
		//echo $template->file2HTML($dir.$f.".html");
		if($page && $frame && is_file(_PLUGIN."/".$page."/tpl/".$frame)){
			return _PLUGIN."/".$page."/tpl/".$frame;
		}
		else{
			return _SYSTEM."/template/tpl/common.html";
		}
	}
	/*
	//
	function getPluginData($plugin,$class,$function,$datas,$html){
		
		$template = new TEMPLATE();
		
		//plugin
		$path="";
		if($plugin){
			$path = _PLUGIN."/".$plugin."/";
		}
		else if($GLOBALS['sys']['config']['default_plugin']){
			$path = _PLUGIN."/".$GLOBALS['sys']['config']['default_plugin']."/";
		}
		else{
			$path = _SYSTEM."/"._COMMON."/";
		}
		
		//class & function
		if($class && class_exists($class) && $function && method_exists($class,$functon)){
			return call_user_func_array(array($class,$functin) , $datas);
		}
		//html
		else if($html && file_exists($path."html/".$html)){
			return $template->file2HTML($path."html/".$html);
		}
		//other
		else{
			return $template->file2HTML(_SYSTEM."/"._COMMON."/html/index.html");
		}
		
	}
	*/
	//認証処理
	function checkLogin($mode=""){
		//認証（ログイン）判別処理----------
		$login = new LOGIN();
		
		//ログアウト処理-----
		if($mode=="logout"){
			$login->setLogout();
		}
		
		//認証済み
		else if($_SESSION['id']){
			
			//ログインフラグを強制、真にする
			return array("flg"=>true);
		}
		
		//認証前
		else if($mode=="login"){
			
			//初期表示※action指定が有る場合
			if(!$_REQUEST['a']){
				return array("flg"=>false);
			}
			
			//認証処理
			else{
				return $login->setLogin($_REQUEST['id'],$_REQUEST['pw']);
			}
		}
		
		return array("flg"=>false);
	}
	
}
