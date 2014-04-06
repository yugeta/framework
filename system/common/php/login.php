<?

class LOGIN{
	
	public $user_file = "data/common/users.dat";
	
	//authorize [return : boolean]
	function auth($mode=""){
		
		$openid = new OPENID();
		$account = new ACCOUNT();
		$url = new URL();
		
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
			
			$account->new_regist($_REQUEST['action'],"",$_REQUEST['data']);
		}
		//Open-ID認証
		else if($mode=='openid'){
			
			//認証サイトからの返信
			if($_REQUEST['action']){
				
				//セッションID無し（期限切れ等）
				if(!$_REQUEST['session_id'] || $_REQUEST['session_id'] != session_id()){
					//リダイレクト処理
					header("Location: ".$url->getUrl());
				}
				
				//認証成功（管理専用※返答値確認）
				if($_REQUEST['check']){
					$keys = array_keys($_REQUEST);
					$a="";
					for($i=0;$i<count($keys);$i++){
						$a.= $keys[$i]." = ".$_REQUEST[$keys[$i]]."<br>\n";
					}
					$GLOBALS['view']['html'] = "test:".$a;
					//echo "OK<br>\n";
					$template = new template();
					echo $template->file2HTML($GLOBALS['sys']['system']."/".$GLOBALS['sys']['common']."/html/common.html");
					exit();
				}
				//認証成功->ログイン
				else{
					
					//openidのIDを取得
					$id   = $openid->getReturnData($_REQUEST['service'],"id");
					$mail = $openid->getReturnData($_REQUEST['service'],"mail");
					//die($_REQUEST['service']." / ".$id." / ".$mail);
					
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
					header("Location: ".$url->getUrl());
				}
				
			}
			//認証サイトへ遷移
			else{
				$openid->services($_REQUEST['service'],session_id());
				/*
				//Google(Gmail)
				if($_REQUEST['service']=="gmail"){
					$openid->{$_REQUEST['service']}(session_id());
					//call_user_func(array($openid,$_REQUEST['service']),session_id());
				}
				//Mixi
				else if($_REQUEST['service']=="mixi"){
					$openid->mixi(session_id());
				}
				//Yahoo
				else if($_REQUEST['service']=="yahoo"){
					$openid->yahoo(session_id());
				}
				*/
			}
		}
		//認証済み
		else if(isset($_SESSION['id']) && $_SESSION['id'] && $_COOKIE['PHPSESSID']){
			
			if(!$_REQUEST['p'] && $GLOBALS['sys']['config']['default_plugin']){
				$_REQUEST['p'] =  $GLOBALS['sys']['config']['default_plugin'];
			}
			
			
			
			
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
			
			if(!$_REQUEST['p'] && $_REQUEST['p']=='common'){
				header("Location: ".$url->getUrl());
			}
			else{
				header("Location: ".$url->getUrl()."?p=".$_REQUEST['p']);
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