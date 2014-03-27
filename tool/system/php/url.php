<?

//URL関連処理

class URL{
    
    //port + domain [http://hoge.com:8800/]
    //現在のポートの取得（80 , 443 , その他）
    function site(){
        //通常のhttp処理
        if($_SERVER['SERVER_PORT']==80){
        	$site = 'http://'.$_SERVER['SERVER_NAME'];
    	}
        //httpsページ処理
    	else if($_SERVER['SERVER_PORT']==443){
    		$site = 'https://'.$_SERVER['SERVER_NAME'];
    	}
        //その他ペート処理
    	else{
    		$site = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'];
    	}
        
        return $site;
    }
    
    //現在ページのサービスroot階層のパスを返す
    function dir(){
        $uri = $this->site();
        $dir = explode('/',$_SERVER['REQUEST_URI']);
        if(count($dir)>1){
            $uri.= join('/',array_pop($dir));
        }
        return $uri;
    }
    
    //現在のクエリ無しパスを返す
    function url(){
    	$uri = $this->site();
    	$req = explode('?',$_SERVER['REQUEST_URI']);
    	$uri.= $req[0];
    	return $uri;
    }
    
    //フルパスを返す
    function uri(){
        $uri = $this->site();
    	$uri.= $_SERVER['REQUEST_URI'];
    	return $uri;
    }
    
    //基本ドメインを返す
    function domain(){
		return $_SERVER['SERVER_NAME'];
    }
    
    //リダイレクト処理
    function move($url){
    	if(!$url){return;}
    	header('Location: '.$url);
    }
    
    
    
    
    /*
    //------------------------------
    //ユーザー設定用scriptタグ発行
    //------------------------------
    function url_tag($mode,$user,$api){
    	
    	if($mode=='url' && file_exists("user/".$user."/url.js")){
    		return "<script type='text/javascript' charset='UTF-8' src='".thisDir()."user/".$user."/url.js?tmp=".date(YmdHis)."'></script>";
    	}
    	else if($mode=='product' && file_exists("user/".$user."/".$api.".js")){
    //		return "<script type='text/javascript' charset='UTF-8' src='".thisDir()."api/".$api."/js/list.js?tmp=".date(YmdHis)."'></script>";
    		return "<script type='text/javascript' charset='UTF-8' src='".thisDir()."user/".$user."/".$api.".js?tmp=".date(YmdHis)."'></script>";
    	}
    }
    */
}