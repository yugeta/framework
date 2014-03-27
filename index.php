<?

/*====================
 * Framework
 * 
 * 機能
 * - 認証1:ログイン、ログアウト、アカウント登録（登録DB:File,MySQL,MongoDB,CouchDB）
 * - 認証2:SNS連携（twitter、Facebook）
 * - 表示1:UI（BootStrap）
 * - 表示2:Plug-in追加によるサービス個別表示（各種処理等）
 * - サービス:複数並列同居可（サブドメイン等による個別表示にも対応）
 * 仕様
 * - クエリ階層[ tool:サービス mode:機能 action:動作 user:アカウント別処理（基本セッション情報とする）　]※左記は予約クエリとして、別用途での使用は禁止
 * - 
 * - 
====================*/


/*----------
 * 初期設定
----------*/

//プログラムバージョン
$version = "0.001";

//tool指定が無い場合は、systemを利用する。
if(!isset($_REQUEST['tool']) || !$_REQUEST['tool']){$_REQUEST['tool'] = 'system';}
if(!isset($_REQUEST['page']) || !$_REQUEST['page']){$_REQUEST['page'] = 'index';}

//class定義
$sys_common = new SYS_COMMON();

//基本コンフィグデータの読み込み
$GLOBALS['system']['config'] = $sys_common->loadConfig();

//基本モジュール読み込み
$sys_common->requires();

//関連クラスの読み込み
$url = new URL();
$tpl = new TEMPLATE();

//セッション有効時間の設定

//session_cache_limiter('private');
//$cache_limiter = session_cache_limiter();
session_cache_expire(1);
//$cache_expire = session_cache_expire();

//認証処理
session_start();

//die(session_id());

/*----------
 * 認証 -> 表示
----------*/

//認証フラグ
$cer_flg = 0;

//認証（ログイン）処理
if($_REQUEST['tool']=='system' && $_REQUEST['mode']=='login'){
    
    //未入力
    if(!$_REQUEST['id'] && !$_REQUEST['pw']){
        $sys_common->toLoginPage("アカウントIDとパスワードを入力してください。");
    }
    
    //認証成功
    else if($sys_common->login($_REQUEST['id'],$_REQUEST['pw'])){
        
        if($_REQUEST['tool'] && $_REQUEST['tool']!="system"){
            header("Location: ".$url->uri()."?tool=".$_REQUEST['tool']);
        }
        else{
            header("Location: ".$url->uri());
        }
    }
    
    //認証失敗
    else{
        $sys_common->toLoginPage("アカウントIDまたはパスワードが違います。");
    }
    
}
//アカウント登録
else if($_REQUEST['tool']=='system' && $_REQUEST['mode']=='regist'){
    
    //新規登録
    if($_REQUEST['action']=='add'){
        //die($_REQUEST['id']."/".$_REQUEST['pw']."/".$_REQUEST['nm']."/".$_REQUEST['ml']);
        $flg = $sys_common->regist($_REQUEST['id'],$_REQUEST['pw'],$_REQUEST['nm'],$_REQUEST['ml']);
        
        //登録成功
        if($flg){
            header("Location: ".$url->url());
        }
        //入力不足
        else{
            $GLOBALS['msg']['val'] = "入力情報が不足しています。";
            $GLOBALS['contents']['html'] = 
            $sys_common->page_view($_REQUEST['tool'],$_REQUEST['page'] , "regist");
            //echo $tpl->read_tpl('tool/system/tpl/regist.html');
        }
    }
    //登録画面
    else{
        //echo $tpl->read_tpl('tool/system/tpl/regist.html');
        $sys_common->page_view($_REQUEST['tool'],$_REQUEST['page'] , "regist");
    }
}
//ログアウト
else if($_REQUEST['tool']=='system' && $_REQUEST['mode']=='logout'){
    $sys_common->logout();
}
//認証後
else if (isset($_SESSION['id']) && $_SESSION['id'] && $_COOKIE['PHPSESSID']) {
    
    //認証成功
    if($sys_common->login_continue($_SESSION['id'])){
        
        //デフォルトtool
        if($_REQUEST['tool']=='system' && $GLOBALS['system']['config']['default_tool'] && is_dir("tool/".$GLOBALS['system']['config']['default_tool'])){
            $_REQUEST['tool'] = $GLOBALS['system']['config']['default_tool'];
        }
        //die($_REQUEST['tool']."/".$GLOBALS['system']['config']['default_tool']."/".$GLOBALS['system']['config']['database_type']);
        //システム以外のtoolが指定されている場合は、モジュール読み込み等の処理を行う
        if($_REQUEST['tool'] && $_REQUEST['tool']!="system"){
            $sys_common->tool_setting($_REQUEST['tool']);
        }
        
        $sys_common->page_view($_REQUEST['tool'],$_REQUEST['page']);
        //echo $tpl->read_tpl('tool/system/page/'.$_REQUEST['page'].'.html');
    }
    //認証失敗
    else{
        $sys_common->toLoginPage("ログインしてください。");
    }
}
//ログイン前
else {
    $sys_common->toLoginPage();
}

exit();

/*----------
 * 関数
----------*/

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
        
        //-----
        //DB検索
        //-----
        
        //mysql
        if($GLOBALS['system']['config']['database_type']=='mysql'){
            if(!$this->login_check_mysql($id,$pw)){return;}
        }
        //mongodb
        else if($GLOBALS['system']['config']['database_type']=='mongodb'){
            if(!$this->login_check_mongodb($id,$pw)){return;}
        }
        //couched
        else if($GLOBALS['system']['config']['database_type']=='couchdb'){
            if(!$this->login_check_couchdb($id,$pw)){return;}
        }
        //file
        else{
            if(!$this->login_check_file($id,$pw)){return;}
        }
        
        //セッションデータ保持
        $_SESSION['id'] = $id;
        
        //認証成功
        return true;
    }
    
    /*----------
     * DataBase Check
    ----------*/
    
    function login_check_mysql($id,$pw){
        
    }
    function login_check_mongodb($id,$pw){
        
    }
    function login_check_couchdb($id,$pw){
        
    }
    function login_check_file($id,$pw){
        $file = "data/system/users.dat";
        
        if(!file_exists($file)){return;}
        
        //ユーザーデータ読み込み
        $data_users = explode("\n",file_get_contents($file));
        
        unset($pw_data);
        
        //データ内のライン処理
        for($i=0,$c=count($data_users);$i<$c;$i++){
            //ラインの文字列を分解
            $sp = explode(",",$data_users[$i]);
            
            //論理フラグチェック
            if(count($sp)<4){continue;}
            
            //削除フラグ
            if($sp[0]!="0"){
                $pw_data="";
                continue;
            }
            
            //アカウント判別
            if($sp[1]!=$id){continue;}
            
            //パスワード保持
            $pw_data = $sp[2];
            
        }
        
        //パスワード確認
        if($pw_data == $pw){return true;}
        
    }
    
    function login_continue($id){
        if(!$id){return;}
        
        //セッションデータ保持
        $_SESSION['id'] = $id;
        
        //認証成功
        return true;
    }
    
    //Logout
    function logout(){
        
        unset($_SESSION['id']);
        
        //リダイレクト
        $url = new URL();
        header("Location: ".$url->url());
        
    }
    
    //
    function html_logout(){
        if($_SESSION['id']){
            return '<a href="?mode=logout" class="navbar-brand">Logout</a>';
        }
        else{
            return '<a href="./" class="navbar-brand">Login</a>';
        }
    }
    
    // ->login-page
    function toLoginPage($msg=""){
        $tpl  = new TEMPLATE();
        $GLOBALS['msg']['val'] = $msg;
        
        $this->page_view($_REQUEST['tool'],$_REQUEST['page'] , "login");
        
        //echo $tpl->read_tpl('tool/system/tpl/login.html');
    }
    
    //Load-config
    function loadConfig($tool='system'){
        
        $file = "data/".$tool."/config.dat";
        
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
    
    //アカウント新規登録
    function regist($id,$pw,$name,$mail){
        
        //IDの登録は必須
        if(!$id){return;}
        
        $file = "data/system/users.dat";
        
        //[ 1:flg 2:ID 3:PassWord 4:Name 5:Mail ]
        $line = "0,".$id.",".$pw.",".$name.",".$mail.","."\n";
        
        file_put_contents($file,$line,FILE_APPEND);
        
        return true;
        
    }
    
    //page表示
    function page_view($tool="system",$page="index",$mode="index"){
        $tpl = new TEMPLATE();
        
        $file_mode = 'tool/'.$tool.'/page/mode/'.$mode.".html";
        
        if($mode && file_exists($file_mode)){
            $GLOBALS['contents']['html'] = $tpl->read_tpl($file_mode);
        }
        
        echo $tpl->read_tpl('tool/'.$tool.'/page/'.$page.".html");
    }
    
    //system以外のtool初期設定
    function tool_setting($tool){
        
        if($tool=="system"){return;}
        
        $tool_dir = "tool/".$tool."/";
        
        if(!is_dir($tool_dir)){return;}
        
        //PHPライブラリの読み込み
        $php_dir = $tool_dir."/php/";
        
        if(is_dir($php_dir)){
            //基本モジュール読み込み
            $sys_common->requires($php_dir);
        }
        
        
        
        //$GLOBALS['contents']['html'] = 
        
        
        
    }
    
}

