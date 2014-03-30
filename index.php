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
 * - クエリ階層[ tool:サービス page:テンプレートのベースファイル(index) mode:機能(テンプレートmodeフォルダ内) action:動作 user:アカウント別処理（基本セッション情報とする）　]※左記は予約クエリとして、別用途での使用は禁止
 * - 
 * バージョン
 * - 0.001 : 書記構築（簡易ログイン機能）
 * - 0.002 : 外部システムのAuth認証機能（Googleのみ）
 * - 0.003 : admin権限(管理者専用画面)
 * 
 * 
 * 
====================*/


/*----------
 * 初期設定
----------*/

//プログラムバージョン
$version = "0.003";

//class定義
$sys_common = new SYS_COMMON();

//基本コンフィグデータの読み込み
$GLOBALS['system']['config'] = $sys_common->loadConfig();

//基本モジュール読み込み
$sys_common->requires();

//関連クラスの読み込み
$url = new URL();
$tpl = new TEMPLATE();
$account = new ACCOUNT();

//認証処理
session_start();

/*----------
 * 認証 -> 表示
----------*/

//認証フラグ
$cer_flg = 0;

//toolがsystem対応の場合のフラグ
$system_flg = (!$_REQUEST['tool'] || $_REQUEST['tool']=='system')?1:0;

//認証（ログイン）処理
if($system_flg && $_REQUEST['mode']=='login'){
    
    //未入力
    if(!$_REQUEST['id'] && !$_REQUEST['pw']){
        $sys_common->toLoginPage("アカウントIDとパスワードを入力してください。");
    }
    
    //認証成功
    else if($sys_common->login("",$_REQUEST['id'],$_REQUEST['pw'])){
        
        if(!$system_flg){
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
else if($system_flg && $_REQUEST['mode']=='regist'){
    
    //新規登録
    if($_REQUEST['action']=='add'){
        
        //登録済み確認（登録されていればtrueがかえる）
        if($account->checkAccountID("",$_REQUEST['id'])){
            $GLOBALS['msg']['val'] = "「".$_REQUEST['id']."」は登録済みのアカウントです。";
        }
        //情報不足（登録が完了した場合にtrueがかえる）
        else if(!$account->setAccount(array("0","","",$_REQUEST['id'],$_REQUEST['pw'],$_REQUEST['nm'],$_REQUEST['mail']))){
            $GLOBALS['msg']['val'] = "入力情報が不足しています。";
        }
        //登録成功
        else{
            header("Location: ".$url->url());
        }
        
        //アラートメッセージ表示
        $sys_common->page_view($_REQUEST['tool'],$_REQUEST['page'] , "regist");
    }
    //update
    else if($_REQUEST['action']=='update'){
        //$sys_common->regist($_REQUEST['service'],$_REQUEST['id'],$_REQUEST['pw'],$_REQUEST['nm'],$_REQUEST['mail']);
        $account->setAccount(array("0",$_REQUEST['service'],"",$_REQUEST['id'],$_REQUEST['pw'],$_REQUEST['nm'],$_REQUEST['mail']));
        header("Location: ".$url->url()."?tool=system&mode=account_setting_complete");
    }
    //登録画面
    else{
        //echo $tpl->read_tpl('tool/system/tpl/regist.html');
        $sys_common->page_view($_REQUEST['tool'],$_REQUEST['page'] , "regist");
    }
}
//ログアウト
else if($system_flg && $_REQUEST['mode']=='logout'){
    $sys_common->logout();
}
//OAUTH認証(open-id)
else if($system_flg && $_REQUEST['mode']=='openid' && $_REQUEST['service']){
    
    $openid = new OPENID();
    
    //認証サイトからの返信
    if($_REQUEST['action']){
        //認証成功（管理専用※返答値確認）
        if($_REQUEST['session_id'] == session_id() && $_REQUEST['check']){
            $keys = array_keys($_REQUEST);
            $a="";
            for($i=0;$i<count($keys);$i++){
                $a.= $keys[$i]." = ".$_REQUEST[$keys[$i]]."<br>\n";
            }
            $GLOBALS['contents']['html'] = $a;
            //echo "OK<br>\n";
            echo $tpl->read_tpl("tool/system/page/index.html");
            exit();
        }
        //認証成功->ログイン
        else if($_REQUEST['session_id'] == session_id()){
            
            //openidのIDを取得
            $id   = $openid->getReturnData($_REQUEST['service'],"id");
            $mail = $openid->getReturnData($_REQUEST['service'],"mail");
            
            //登録済みチェック
            if(!$account->checkAccountID($_REQUEST['service'],$id,"")){
                //未登録の場合（新規登録 id:アカウント pw:null openid:[google,facebook,twitter]）
                //$sys_common->regist($_REQUEST['service'],$id,'','',$mail);
                $account->setAccount(array("0",$_REQUEST['service'],"",$id,"","",$mail));
            }
            //セッション情報の登録
            $_SESSION['id'] = $id;
            
            //cookie-time処理
            if($_REQUEST['cookie_time']){
                $CookieInfo = session_get_cookie_params();
                setcookie( session_name(), session_id(), time() + $_REQUEST['cookie_time'] , $CookieInfo['path'] );
            }
            
            //リダイレクト処理
            header("Location: ".$url->url());
        }
        //セッション未認証（期限切れ等）
        else{
            //リダイレクト処理
            header("Location: ".$url->url());
        }
        //die("OK:".session_id());
    }
    //認証サイトへ遷移
    else{
        //Google(Gmail)
        if($_REQUEST['service']=="gmail"){
            $openid->gmail(session_id());
        }
        else if($_REQUEST['service']=="facebook"){
            $openid->facebook(session_id());
        }
    }
}
//認証後
else if (isset($_SESSION['id']) && $_SESSION['id'] && $_COOKIE['PHPSESSID']) {
    
    //認証成功
    if($sys_common->login_continue($_SESSION['id'])){
        
        //アカウント情報取得
        $GLOBALS['account_info'] = $account->getAccount($_SESSION['id']);
        //die(session_id()."/".$GLOBALS['account']['id']);
        
        //デフォルトtool
        if(!$_REQUEST['tool']
        && $GLOBALS['system']['config']['default_tool']
        && is_dir("tool/".$GLOBALS['system']['config']['default_tool'])){
            $_REQUEST['tool'] = $GLOBALS['system']['config']['default_tool'];
        }
        
        //システム以外のtoolが指定されている場合は、モジュール読み込み等の処理を行う
        if(!$system_flg){
            $sys_common->tool_setting($_REQUEST['tool']);
        }
        
        $sys_common->page_view($_REQUEST['tool'],$_REQUEST['page'],$_REQUEST['mode']);
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
    function login($service,$id,$pw){
        if($id==''){return;}
        
        //-----
        //DB検索
        //-----
        
        //mysql
        if($GLOBALS['system']['config']['database_type']=='mysql'){
            if(!$this->login_check_mysql($service,$id,$pw)){return;}
        }
        //mongodb
        else if($GLOBALS['system']['config']['database_type']=='mongodb'){
            if(!$this->login_check_mongodb($service,$id,$pw)){return;}
        }
        //couched
        else if($GLOBALS['system']['config']['database_type']=='couchdb'){
            if(!$this->login_check_couchdb($service,$id,$pw)){return;}
        }
        //file
        else{
            if(!$this->login_check_file($service,$id,$pw)){return;}
        }
        
        //セッションデータ保持
        $_SESSION['id'] = $id;
        
        //クッキー時間の書き換え
        //die($_COOKIE['PHPSESSID']);
        //setcookie( session_name(), session_id(), time() + 600 );
        //die(session_name()." : ".session_id()." : ".$CookieInfo['path']);
        if($_REQUEST['cookie_time']){
            $CookieInfo = session_get_cookie_params();
            setcookie( session_name(), session_id(), time() + $_REQUEST['cookie_time'] , $CookieInfo['path'] );
        }
        
        //認証成功
        return true;
    }
    
    /*----------
     * DataBase Check
    ----------*/
    
    function login_check_mysql($service,$id,$pw){
        
    }
    function login_check_mongodb($service,$id,$pw){
        
    }
    function login_check_couchdb($service,$id,$pw){
        
    }
    function login_check_file($service,$id,$pw){
        $file = "data/system/users.dat";
        
        if(!file_exists($file)){return;}
        
        //ユーザーデータ読み込み
        $data_users = explode("\n",file_get_contents($file));
        
        unset($pw_data);
        
        //データ内のライン処理
        for($i=0,$c=count($data_users);$i<$c;$i++){
            //ラインの文字列を分解
            $sp = explode(",",$data_users[$i]);
            
            //データカラムチェック
            if(count($sp)<4){continue;}
            
            //service(open-id)チェック
            if($sp[1]!=$service){
                continue;
            }
            
            //論理削除フラグフラグ
            if($sp[0]!="0"){
                $pw_data="";
                continue;
            }
            
            //アカウント判別
            if($sp[3]!=$id){continue;}
            
            //パスワード保持
            $pw_data = $sp[4];
            
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
            $html = '';
            //$html.= '<a href="?mode=logout" class="navbar-brand">';
            $html.= '<a href="?mode=logout">';
            //$html.= '<img src="tool/system/img/cc_black/padlock_open_icon&16.png">';
            $html.= 'Logout</a>';
            return $html;
        }
        else{
            $html = '';
            //$html.= '<a href="./" class="navbar-brand">';
            $html.= '<a href="./">';
            //$html.= '<img src="tool/system/img/cc_black/padlock_closed_icon&16.png">';
            $html.= 'Login</a>';
            return $html;
        }
    }
    
    // ->login-page
    function toLoginPage($msg="",$tool="",$page=""){
        
        if(!$tool){$tool="system";}
        if(!$page){$page="index";}
        
        $tpl  = new TEMPLATE();
        $GLOBALS['msg']['val'] = $msg;
        
        $this->page_view($tool,$page , "login");
        
        //echo $tpl->read_tpl('tool/system/tpl/login.html');
    }
    
    //Load-config
    function loadConfig($tool=''){
        
        if(!$tool){$tool="system";}
        
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
    
    //page表示
    function page_view($tool="",$page="",$mode=""){
        
        if(!$tool){$tool="system";}
        if(!$page){$page="index";}
        if(!$mode){$mode="index";}
        
        $tpl = new TEMPLATE();
        
        $file_mode = 'tool/'.$tool.'/page/mode/'.$mode.".html";
        
        if($mode && file_exists($file_mode)){
            $GLOBALS['contents']['html'] = $tpl->read_tpl($file_mode);
        }
        
        echo $tpl->read_tpl('tool/'.$tool.'/page/'.$page.".html");
    }
    
    //system以外のtool初期設定
    function tool_setting($tool){
        
        if($tool=="system" || !$tool){return;}
        
        $tool_dir = "tool/".$tool."/";
        
        if(!is_dir($tool_dir)){return;}
        
        //PHPライブラリの読み込み
        $php_dir = $tool_dir."/php/";
        
        if(is_dir($php_dir)){
            //基本モジュール読み込み
            $sys_common->requires($php_dir);
        }
    }
    
    function checkSession(){
        if(session_id() && $_SESSION['id']){
            return true;
        }
        else{
            return false;
        }
        //return session_id();
    }
}

