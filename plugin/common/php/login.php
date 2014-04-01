<?

class LOGIN{
	
	public $user_file = "data/common/users.dat";
	
	//authorize [return : boolean]
	function auth($mode=""){
		
		//ログイン処理
		if($mode=='login'){
			$this->setLogin($_REQUEST['id'],$_REQUEST['pw']);
		}
		//ログアウト
		else if($mode=='logout'){
			$this->setLogout($_REQUEST['id'],$_REQUEST['pw']);
			
		}
		//アカウント登録
		else if($mode=='regist'){
			$account = new ACCOUNT();
			$account->new_regist($_REQUEST['action'],"",$_REQUEST['data']);
		}
		//Open-ID認証
		else if($mode=='openid'){
			
		}
		//認証済み
		else if(isset($_SESSION['id']) && $_SESSION['id'] && $_COOKIE['PHPSESSID']){
			
		}
		//未認証（ログイン前）
		else{
			$_REQUEST['m'] = "login";
		}
	}
	
	/*==========
	　ログイン処理
	==========*/
	
	//ログイン処理※errorの場合は、対象文言を保存する。
	function setLogin($id="",$pw=""){
			//未入力
		if(!$id && !$pw){
			$GLOBALS['view']['message'] = "アカウントIDとパスワードを入力してください。";
		}
		
		//認証成功※open-idは無し
		else if($this->login_check("",$id,$pw)){
			//return true;
			
			$url = new URL();
			
			if($_REQUEST['p'] && $_REQUEST['p']!='common'){
				header("Location: ".$url->getUrl()."?p=".$_REQUEST['p']);
			}
			else{
				header("Location: ".$url->getUrl());
			}
			
		}
		
		//認証失敗
		else{
			$GLOBALS['view']['message'] = "アカウントIDまたはパスワードが違います。";
		}
		
		$_REQUEST['m']='login';
		
	}
	
	//Logout
	function setLogout(){
		
		unset($_SESSION['id']);
		
		//リダイレクト
		$url = new URL();
		header("Location: ".$url->getUrl());
		
	}
	
	//Login-check
	function login_check($service,$id,$pw){
		
		if($id==''){return;}
		
		//-----
		//DB検索
		//-----
		
		//mysql
		if($GLOBALS['sys']['config']['database_type']=='mysql'){
			if(!$this->login_check_mysql($service,$id,$pw)){return;}
		}
		//mongodb
		else if($GLOBALS['sys']['config']['database_type']=='mongodb'){
			if(!$this->login_check_mongodb($service,$id,$pw)){return;}
		}
		//couched
		else if($GLOBALS['sys']['config']['database_type']=='couchdb'){
			if(!$this->login_check_couchdb($service,$id,$pw)){return;}
		}
		//file
		else{
			if(!$this->login_check_file($service,$id,$pw)){return;}
		}
		
		//セッションデータ保持
		$_SESSION['id'] = $id;
		
		//クッキー時間の書き換え
		if($_REQUEST['cookie_time']){
			$CookieInfo = session_get_cookie_params();
			setcookie( session_name(), session_id(), time() + $_REQUEST['cookie_time'] , $CookieInfo['path'] );
		}
		
		//認証成功
		return true;
	}
	
	/*----------
	 ログイン DataBase Check
	----------*/
	
	function login_check_mysql($service,$id,$pw){
		
	}
	function login_check_mongodb($service,$id,$pw){
		
	}
	function login_check_couchdb($service,$id,$pw){
		
	}
	function login_check_file($service,$id,$pw){
		
		$file = $this->user_file;
		//$file = "data/common/users.dat";
		
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
	
	
}