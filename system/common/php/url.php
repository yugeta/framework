<?

//URL関連処理

class URL{
	
	//port + domain [http://hoge.com:8800/]
	//現在のポートの取得（80 , 443 , その他）
	function getSite(){
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
	function getDir(){
		$uri = $this->getSite();
		$dir = explode('/',$_SERVER['REQUEST_URI']);
		if(count($dir)>1){
			$uri.= join('/',array_pop($dir));
		}
		return $uri;
	}
	
	//現在のクエリ無しパスを返す
	function getUrl(){
		$uri = $this->getSite();
		$req = explode('?',$_SERVER['REQUEST_URI']);
		$uri.= $req[0];
		return $uri;
	}
	
	//フルパスを返す
	function getUri(){
		$uri = $this->getSite();
		$uri.= $_SERVER['REQUEST_URI'];
		return $uri;
	}
	
	//基本ドメインを返す
	function getDomain(){
		return $_SERVER['SERVER_NAME'];
	}
	
	//リダイレクト処理
	function setUrl($url){
		if(!$url){return;}
		header('Location: '.$url);
	}
}