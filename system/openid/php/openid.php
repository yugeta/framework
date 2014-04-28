<?

class OPENID{
	
	/*==========
	サイト別認証処理
	==========*/
	function services($service="",$check=""){
		if(!$service){return;}
		$this->{$GLOBALS['sys']['openid'][$service]['type']}($service,$check);
	}
	
	//open-idタイプ別リクエスト処理
	function openid_1_0($service,$check=""){
		
		$session_id = session_id();
		if(!$session_id){return;}
		
		$openid_url = $GLOBALS['sys']['openid'][$service]['url'];
		
		$url = new URL();
		
		$mysite = $url->getUrl();
		
		$data=array(
			'openid.ns'				=> 'http://specs.openid.net/auth/1.0',
			'openid.ns.pape'		=> 'http://specs.openid.net/extensions/pape/1.0',
			'openid.ns.max_auth_age'=> '300',
			'openid.claimed_id'		=> 'http://specs.openid.net/auth/1.0/identifier_select',
			'openid.identity'		=> 'http://specs.openid.net/auth/1.0/identifier_select',
			'openid.return_to'		=> $mysite.'?m=openid&service='.$service.'&action=return&session_id='.$session_id."&check=".$check."&cookie_time=".$_REQUEST['cookie_time'],
			'openid.realm'			=> $mysite,
			'openid.mode'			=> 'checkid_setup',
			'openid.ui.ns'			=> 'http://specs.openid.net/extensions/ui/1.0',
			'openid.ui.mode'		=> '=popup',
			'openid.ui.icon'		=> 'true',
			'openid.ns.ax'			=> 'http://openid.net/srv/ax/1.0',
			'openid.ax.mode'		=> 'fetch_request',
			'openid.ax.type.email'	=> 'http://axschema.org/contact/email',
			'openid.ax.type.guid'	=> 'http://schemas.openid.net/ax/api/user_id',
			'openid.ax.type.language'=>'http://axschema.org/pref/language',
			'openid.ax.required'	=> 'email,guid,language'
		);
		
		foreach($data as $key=>$val){
			$q[] = $key."=".urlencode($val);
		}
		
		//separate
		$separate_value = "?";
		if(preg_match("/".$separate_value."/",$openid_url)){
			$separate_value = "&";
		}
		
		//サイトへ移動
		header("Location: ".$openid_url.$separate_value.join("&",$q));
	}
	function openid_2_0($service,$check=""){
		
		$session_id = session_id();
		if(!$session_id){return;}
		
		$openid_url = $GLOBALS['sys']['openid'][$service]['url'];
		
		$url = new URL();
		
		$mysite = $url->getUrl();
		
		$data=array(
			'openid.ns'				=> 'http://specs.openid.net/auth/2.0',
			'openid.ns.pape'		=> 'http://specs.openid.net/extensions/pape/1.0',
			'openid.ns.max_auth_age'=> '300',
			'openid.claimed_id'		=> 'http://specs.openid.net/auth/2.0/identifier_select',
			'openid.identity'		=> 'http://specs.openid.net/auth/2.0/identifier_select',
			'openid.return_to'		=> $mysite.'?m=openid&service='.$service.'&action=return&session_id='.$session_id."&check=".$check."&cookie_time=".$_REQUEST['cookie_time'],
			'openid.realm'			=> $mysite,
			'openid.mode'			=> 'checkid_setup',
			'openid.ui.ns'			=> 'http://specs.openid.net/extensions/ui/1.0',
			'openid.ui.mode'		=> '=popup',
			'openid.ui.icon'		=> 'true',
			'openid.ns.ax'			=> 'http://openid.net/srv/ax/1.0',
			'openid.ax.mode'		=> 'fetch_request',
			'openid.ax.type.email'	=> 'http://axschema.org/contact/email',
			'openid.ax.type.guid'	=> 'http://schemas.openid.net/ax/api/user_id',
			'openid.ax.type.language'=>'http://axschema.org/pref/language',
			'openid.ax.required'	=> 'email,guid,language'
		);
		
		foreach($data as $key=>$val){
			$q[] = $key."=".urlencode($val);
		}
		
		//separate
		$separate_value = "?";
		if(preg_match("/".$separate_value."/",$openid_url)){
			$separate_value = "&";
		}
		
		//サイトへ移動
		header("Location: ".$openid_url.$separate_value.join("&",$q));
	}
	
	
	//認証後に対象OPENID別に返り値を取得する。
	function getReturnData($service,$mode){
		
		$val = $_REQUEST[$GLOBALS['sys']['openid'][$service]['request'][$mode]];
		/*
		//ID用入れ替え文字処理
		if($GLOBALS['sys']['openid'][$service]['id_replace']['str_replace']){
			$val = str_replace($GLOBALS['sys']['openid'][$service]['id_replace']['str_replace'],"",$val);
		}
		*/
		//echo "<pre>\ntest-\n".urlencode($val)."\n</pre>";exit;
		//特殊文字変換
		$val = urlencode($val);
		//$string = new STRING();
		//$val = $string->fileNameEncode($val);
		/*
		$val = str_replace(",","&#44;",$val);
		$val = str_replace(":","&#58;",$val);
		$val = str_replace("/","&#47;",$val);
		*/
		
		//die($GLOBALS['sys']['openid'][$service]['id_replace']['str_replace']." : ".$val);
		//die($val);
		
		return $val;
	}
	
	//ボタン表示
	function view_button($service,$check=""){
		if(!$service){return;}
		
		//管理モードチェック
		if($check){
			$GLOBALS['sys']['openid_check'] = $check;
		}
		
		$template = new template();
		
		if($GLOBALS['sys']['openid'][$service]['flg']=="true"){
			return $template->file2HTML(_SYSTEM."/openid/html/".$service.".html");
		}
	}
}



