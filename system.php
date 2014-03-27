<?

/**********
*Framework
**********/

//class定義
$sys_common = new SYS_COMMON();

//基本モジュール読み込み
$sys_common->requires();

//認証処理
session_start();
//die("cookie:".$_COOKIE['PHPSESSID']."/"."sess-id:".$_SESSION['id']);

//初期設定
$GLOBALS['system'] = $sys_common->loadConfig();
if(!isset($_REQUEST['tool'])){
    $_REQUEST['tool'] = 'system';
}

$cer_flg = 0;

//認証（ログイン）処理
if($_REQUEST['tool']=='system' && $_REQUEST['mode']=='login'){die("a");
    
    //認証成功
    if($sys_common->login($_REQUEST['id'],$_REQUEST['pw'])){
        
        //リダイレクト
        $url = new URL();
        //die($url->uri());
        header("Location: ".$url->uri());
        
    }
    //認証失敗
    else{
        $cer_flg++;
    }
    
}
//アカウント新規登録
else if($_REQUEST['tool']=='system' && $_REQUEST['mode']=='regist'){
    if($_REQUEST['action']=='add'){
        
    }
    else{
        $tpl  = new TEMPLATE();
        echo $tpl->read_tpl('tool/system/tpl/regist.html');
    }
}
//認証後
else if (isset($_SESSION['id']) && $_SESSION['id'] && $_COOKIE['PHPSESSID']) {
    
    //die("sessid:".$C_OOKIE['PHPSESSID']);
    
    //認証成功
    if($sys_common->login($_REQUEST['id'],$_REQUEST['pw'])){
        $tpl  = new TEMPLATE();
        echo $tpl->read_tpl('tool/system/tpl/index.html');
    }
    //認証失敗
    else{
        $cer_flg++;
    }
}
//ログイン前
else {
    $cer_flg++;
}

//認証失敗の場合はログインページへ
if($cer_flg){
    $tpl  = new TEMPLATE();
    echo $tpl->read_tpl('tool/system/tpl/login.html');
}

//$_SESSION[***](値保存できる）

//echo $_SESSION['count']."/".$_SESSION['id'];

exit();

class SYS_COMMON{
    //Directory require
    function requires($dir="tool/system/php/"){
        if(!is_dir($dir)){return;}
        
        if(!preg_match("@\/$@",$dir)){
            $dir.= '/';
        }
        
        $php = scandir($dir);
        for($i=0,$c=count($php);$i<$c;$i++){
            
            //システムファイルは無視
            if($php[$i]=='.' || $php[$i]=='..'){continue;}
            
            //phpファイル以外は無視
            if(!preg_match("/^(.*)\.php$/",$php[$i])){continue;}
            
            require_once $dir.$php[$i];
        }
    }
    //Login-check
    function login($id,$pw){
        if($id=='' || $pw==''){return;}
        
        //DB検索
        
        
        //セッションデータ保持
        $_SESSION['id'] = $id;
        
        //認証成功
        return true;
    }
    //Logout
    function logout(){
        
    }
    //Load-config
    function loadConfig($tool='system'){
        
        $file = "tool/".$tool."/cfg/"."database.dat";
        
        unset($data);
        
        //database
        if(file_exists($file)){
            $datas = explode("\n",file_get_contents($file));
            for($i=0,$c=count($datas);$i<$c;$i++){
                if(!$datas[$i]){continue;}
                $sp = explode(",",$datas[$i]);
                $data['cfg'][$sp[0]] = $sp[1];
            }
        }
        
        return $data;
    }
    
}


//基本クラス読み込み※メインモジュール内に格納
$ideacompo = new IDEACOMPO();

//初期設定
$ideacompo->set();

//choice only tool
$ideacompo->choice_tool();

//Library読み込み
$ideacompo->requires(SYS."php/");

//configデータ読み込み※$GLOBALSに代入
if($_REQUEST['tool']){
    $GLOBALS['config'] = $ideacompo->config(TOOL.$_REQUEST[tool].'/cfg/config.txt');
}
else{
    $GLOBALS['config'] = $ideacompo->config(SYS.'cfg/config.txt');
}

//認証[ login / logout ]
$uid = $ideacompo->check();
define(UID,$uid);

//共通ライブラリ読み込み
$tpl   = new TEMPLATE();
//$login = new LOGIN();
//$str   = new STRING();
$sys   = new SYS();

//----------
//初期設定
//※パスワードファイルの確認
//----------
//die('exit');
/*
if(!is_dir($sys->sys)){
    //情報登録
    $sys->first_setting();
}
*/
//----------
//認証前ページ
//各種静的ページ、システムページ
//SYS
//----------

//ユーザー新規登録
//if($_REQUEST[sys] && function_exists($sys->{$_REQUEST[sys]})){
if($_REQUEST[sys]=='regist' && method_exists("SYS",$_REQUEST[sys])){
    $sys->{$_REQUEST[sys]}();
}
else if($_REQUEST[page] && file_exists(SYS.'page/'.$_REQUEST[page].'.html')){
    
    echo $tpl->read_tpl(SYS.'page/'.$_REQUEST[page].'.html');
    exit;
}

//----------
//認証処理
//----------
/*
if(!$uid){
    echo $tpl->read_tpl(SYS."/tpl/top.html");
	exit;
}
*/
/*
if(class_exists("LOGIN") && $GLOBALS[config][login_mode]){
	
    //ログインを使用しない、認証されない場合はログイン画面を表示
    $uid = $login->check();
    
	if(!$uid){
        define(UID,'');
        
		echo $tpl->read_tpl(SYS."/tpl/top.html");
		exit;
	}
    //認証後、ユーザー情報を定数で保持
    else{
        //user-idを定数にセット（デコードした状態）
        define(UID,$str->dec_code($uid));
    }
    
}
*/
//----------
//認証後ページ
//各種TOOLS
//----------
//TOOL処理
if($_REQUEST[tool]){
    
    //toolモジュールの読み込み
    $ideacompo->requires(TOOL.$_REQUEST[tool]."/php/");
    
    //lonin-check
    if($uid){
        // login-after
        
        //初期ページ
        if(!$_REQUEST[menu] && $GLOBALS[config][menu_default]){
            $_REQUEST[menu] = $GLOBALS[config][menu_default];
        }
        
        //die($GLOBALS[config][menu_default]);
        
        //index.phpの読み込み
        $index = TOOL.$_REQUEST[tool]."/index.php";
        if(file_exists($index)){
            require_once $index;
        }
        /*
        else{
            die("file not found : ".$index);
        }
        */
        //die($index);
        //die($_REQUEST[ini]);
        //echo $tpl->read_tpl(SYS.'/tpl/frame.html');
    }
    else{
        // un-login
        //die(TOOL.$_REQUEST[tool]."/index.html");
        //表示ページ
        if(file_exists(TOOL.$_REQUEST[tool]."/index.html")){
            $GLOBALS[contents][html] = $tpl->read_tpl(TOOL.$_REQUEST[tool]."/index.html");
        }
        //common index
        else{
            $GLOBALS[contents][html] = $tpl->read_tpl(SYS."/index.html");
        }
    }
    echo $tpl->read_tpl(SYS.'/tpl/frame.html');
    
}
else{
    //config読み込み
    //$GLOBALS[config] = $ideacompo->config(SYS.'cfg/config.txt');
    
    echo $tpl->read_tpl(SYS."/tpl/index.html");
}

exit;
/*
if($_REQUEST[tool] && class_exists('TOOL')){
    
    //メインmethod読み込み
    $tool = new TOOL();
    
    if(method_exists($tool,'index')){
        $GLOBALS[contents][html] = $tool->index();
        //$GLOBALS[contents][html] = "--";
        
        echo $tpl->read_tpl(SYS.'/tpl/frame.html');
        exit;
    }
    
}
*/
/*
//システムポータル表示
echo $tpl->read_tpl(SYS."/tpl/index.html");
*/


//----------
//システム関数
//----------
class IDEACOMPO{
    
    public $pass = 'passwd.txt';
    public $data = 'user_data.txt';
    public $cfg  = 'config.txt';
    
    //cookieデータの区切り文字列[ymd.ip-deadtime]
    public $cookie_split = '.';
    
    //デフォルトセッション有効期間（1時間有効）※クッキー有効時間
    public $session_dead = 3600;
    
    //クッキー保持時間※1年間保持（セッションファイル有効期間はセッション有効期間より長め）
    public function cookie_time(){return (365*24*60*60);}
    
    //初期設定
    function set(){
        //toolフォルダ
        define(TOOL,'tool/');
        //デフォルト用tool-ID
        define(SYSTEM,'system');
        
        //ライブラリ（sys）フォルダの確認
        //$script_name = array_pop(explode("/",$_SERVER[SCRIPT_NAME])).".sys";
        define(SYS,TOOL.SYSTEM.'/');
        
        //データフォルダ
        define(DAT,'data/'.SYSTEM.'/');
        
        //session-folder
        define(SESSION,DAT.'session/');
        
        if(!is_dir(SYS)){
            die("Error!! : not system folder(".SYS.")");
        }
    }
    
    // if tool request is only one.
    function choice_tool(){
        if($_REQUEST[tool]){return;}
        
        unset($tool_count);
        
        $tools = scandir(TOOL);
        
        for($i=0,$c=count($tools);$i<$c;$i++){
            if($tools[$i]=='.' || $tools[$i]=='..'){continue;}
            if(file_exists(TOOL.$tools[$i].'/cfg/config.txt') && $tools[$i]!=SYSTEM){
                $tool_count[]=$tools[$i];
            }
        }
        
        $_REQUEST[tool] = (count($tool_count)==1)?$tool_count[0]:'';
    }
    
    //認証チェック
    function check(){
        //ログアウト確認
        if($_REQUEST[sys]=='logout'){
            $this->logout();
        }
        //ログイン確認
        else if($_REQUEST[sys]=='login'){
            $this->login($_REQUEST[id],$_REQUEST[pw],$_REQUEST[cookie_time]);
            //die("login");
        }
        
        $uid = $this->session_read();
        //die("uid:".$uid);
        return $uid;
    }
    
    //ユーザー、パスワード確認※パスワード指定がない場合は登録済みユーザー確認
    function user_confirm($uid,$pw=null){
        $sys = new SYS();
        
        $file = $sys->sys.$sys->pass;
        
        if(file_exists($file)){
            $data = file($file);
            //下の行から検索
            for($cnt=count($data),$i=$cnt-1;$i>=0;$i--){
                
                $sp = explode(",",$data[$i]);
                
                //IDマッチング確認
                if($sp[0]==$uid){
                    //pw確認
                    if($pw){
                        if($sp[1]==$pw){
                            return true;
                        }
                    }
                    //IDのみ確認
                    else{
                        return true;
                    }
                    break;
                }
            }
        }
    }
    
    //フォルダ内のphpを一括でincludeする※class一覧
    function requires($dir){
        if(!is_dir($dir)){return;}
        
        if(!preg_match("@\/$@",$dir)){
            $dir.= '/';
        }
        
        $php = scandir($dir);
        for($i=0,$c=count($php);$i<$c;$i++){
            
            //システムファイルは無視
            if($php[$i]=='.' || $php[$i]=='..'){continue;}
            
            //phpファイル以外は無視
            if(!preg_match("/^(.*)\.php$/",$php[$i])){continue;}
            
            require_once $dir.$php[$i];
        }
    }
    
    //configファイルの読み込み
    function config($file){
        if(!file_exists($file)){return;}
        
        unset($global);
        
        //$cfg = file($file);
        $cfg = explode("\n",file_get_contents($file));
        
        for($i=0,$cnt=count($cfg);$i<$cnt;$i++){
            
            //directoryの場合は、処理無し
            if(preg_match("@^/$@",$cfg[$i])){continue;}
            
            //不要文字列削除
            $cfg[$i] = str_replace(array("\r","\n"),'',$cfg[$i]);
            //key,valueに分解
            $sp = explode('=',$cfg[$i]);
            //分解が無い場合は処理無し
            if(count($sp)<=1){continue;}
            
            //global変数に保存
            $global[$sp[0]] = $sp[1];
        }
        
        // default value setting
        $global[menu_default] = (isset($global[menu_default]))?$global[menu_default]:'index';
        $global[login_mode]   = (isset($global[login_mode]))?$global[login_mode]:1;
        
        
        return $global;
    }
    
    //ログアウト処理
    function logout($session_id=null){
        
        //初期設定
        
        $url    = new URL();
        $tpl    = new TEMPLATE();
        $query  = new QUERY();
        
        /*
        //セッションデータ削除
        if($session_id && file_exists(SESSION.$session_id)){
            unlink(SESSION.$session_id);
        }
        
        //cookie削除
        $cookie->clear($cookie->id);
        */
        
        $this->logout_proc();
        
        //queryチェック※sysクエリを除外する。
        $query_string = $query->key_del($_SERVER[QUERY_STRING],'sys');
        
        //リダイレクト※クエリを全て除外する
        $url->move($url->url().(($query_string)?'?'.$query_string:''));
        //システムポータル表示
        //echo $tpl->read_tpl(SYS."/tpl/index.html");
        exit;
    }
    
    //cookie-del session-file-del
    function logout_proc(){
        $cookie = new COOKIE();
        
        $session_id = $this->get_cookie();
        
        //セッションデータ削除
        if($session_id && file_exists(SESSION.$session_id)){
            unlink(SESSION.$session_id);
        }
        //die(SESSION.$session_id);
        
        //cookie削除
        $cookie->clear($cookie->id);
        
    }
    
    //loginファイル確認→認証処理
    function login($id,$pw,$cookie_time=null){//die($id."/".$pw);
		if(!$id || !$pw){return;}
        
        $str    = new STRING();
        $cookie = new COOKIE();
        $url    = new URL();
        $query  = new QUERY();
        
        $id_code = $str->enc_code($id);
        $pw_code = $str->enc_code($pw);
		
        //データ確認（ファイル確認）
        if(!$this->uid_confirm($id_code,$pw_code)){return;}
        
        //cookie get
        $session_id = $this->get_cookie();
        
        //session-id作成
        if(!$session_id){
            $session_id = $this->make_session_id();
        }
        //die("a".$session_id);
        
        $this->session_write($session_id,$id,$cookie_time);
        
        //リダイレクト処理※postクエリのキャッシュ排除処理
        $query_string = $query->key_del($_SERVER[QUERY_STRING],'sys');
        
        $url->move($url->url().(($query_string)?'?'.$query_string:''));
        
        exit;
	}
    
    //ユーザー、パスワード確認※パスワード指定がない場合は登録済みユーザー確認
    function uid_confirm($uid,$pw=null){
        $sys = new SYS();
        
        $file = DAT.'passwd.txt';
        
        if(file_exists($file)){
            $data = explode("\n",file_get_contents($file));
            
            //下の行から検索
            for($cnt=count($data),$i=$cnt-1;$i>=0;$i--){
                
                $sp = explode(',',$data[$i]);
                
                //IDマッチング確認
                if($sp[0]==$uid){
                    //pw確認
                    if($pw){
                        if($sp[1]==$pw){
                            return true;
                        }
                    }
                    //IDのみ確認
                    else{
                        return true;
                    }
                    break;
                }
            }
        }
    }
    
    //session-name[ YMDHIS+IP : **************.************ ]
    function make_session_id(){
        //ipアドレス文字列処理
        $ip = $_SERVER[REMOTE_ADDR];
        
        //ipアドレスを3桁フォーマットに変換
        $ips = explode('.',$ip);
        for($i=0,$c=count($ips);$i<$c;$i++){
            $ips[$i] = sprintf("%03d",$ips[$i]);
        }
        $ip = join('',$ips);
        
        
        //ユニーク値(yyyymmdd.ip)
		return date(YmdHis).$this->cookie_split.$ip;
    }
    
    //session-cookie-get
    function get_cookie(){
        
        $cookie = new COOKIE();
        
        $value = $cookie->read($cookie->id);
        
        if(!$value){return '';}
        
        $sp = explode($this->cookie_split,$value);
        
        if(!$sp[0] || !$sp[1]){return '';}
        
        return $sp[0].$this->cookie_split.$sp[1];
        
    }
    
    //session-read [ cookie -> session-file -> uid ]
    function session_read(){
        $cookie = new COOKIE();
        $str    = new STRING();
        $date   = new DATE();
        
        //$cookie->write($cookie->id, '20130526141136.192168001106' ,3600);
        //cookiem確認
        $session_id  = $cookie->read($cookie->id);
        if(!$session_id){return;}
        
        //$cookie_data = explode($this->cookie_split,$session_id);
        list($write_time , $ip , $limit) = explode($this->cookie_split,$session_id);
        if(!$write_time || !$ip){return;}
        if(!$limit){$limit = $this->cookie_dead;}
        
        //file-confirm
        $session_file = SESSION.$write_time.$this->cookie_split.$ip;
        if(!file_get_contents($session_file)){return;}
        
        //session-data確認
        $session_data = explode("\n",file_get_contents($session_file));
        if(!count($session_data)){return;}
        
        //session-id search
        unset($uid,$dt);
        for($i=count($session_data)-1;$i>=0;$i--){
            $session_data[$i] = str_replace(array("\n","\r"),'',$session_data[$i]);
            if(!$session_data[$i]){continue;}
            $sp = explode(',',$session_data[$i]);
            if(count($sp)< 2){continue;}
            
            //return $str->dec_code($sp[0]);
            
            $uid = $str->dec_code($sp[0]);
            $dt  = $sp[1];
            break;
        }
        if(!$uid || !$dt){return;}
        
        //limit-check
        if(date(YmdHis) > $date->advance_secounds($dt,$limit)){
            $this->logout_proc($session_id);
            return;
        }
        
        return $uid;
    }
    //session-write
    function session_write($session_id,$id,$cookie_time=null){
        
        if(!$session_id || !$id){return;}
        
        $str    = new STRING();
        $cookie = new COOKIE();
        
        //保存ディレクトリ作成
        if(!is_dir(SESSION)){
            mkdir(SESSION, 0777, true);
        }
		
		//一時ファイル保存
		file_put_contents(SESSION.$session_id, $str->enc_code($id).','.date(YmdHis).",\n",FILE_APPEND);
		
		//クッキー保存※365日期限
		
        //約一ヶ月分
        //$time = (30 * 24 * 60 * 60);
        //$cookietime = ($_REQUESR[cookir_time])?$_REQUESR[cookir_time]:$this->cookie_time();
        $session_dead = ($cookie_time)?$cookie_time:$this->session_dead;
		$cookie->write($cookie->id , $session_id.$this->cookie_split.$session_dead , $this->cookie_time());
		
    }
    
}




class BATCH{
    
    /**
     * Books - batch
     * [pdf,zip,rar] -> .book/--/%file%.s.jpg
    **/
    
    function books(){
        
        $path = '';
        define(TOOL_DIR,'data/books/');
        define(TOOL_THUMB,'data/.books/');
        
        //check
        if(!is_dir($path)){return;}
        
        //folder-check
        $this->books_folder($path);
        
        
    }
    function books_folder($path){
        
        $lists = scandir(TOOL_DIR.$path);
        
        for($i=0,$c=count($lists);$i<$c;$i++){
            if($lists[$i]=='.' || $lists[$i]=='..'){continue;}
            
            //dir
            if(is_dir(TOOL_DIR.$path.$lists[$i])){
                
            }
            
            //file
            else if(file_exists(TOOL_DIR.$path.$lists[$i])){
                
            }
            
        }
        
    }
    
    
}

