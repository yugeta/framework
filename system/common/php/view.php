<?

class VIEW{
	//html-view
	function html($plugin="common",$file="common"){
		
		//$common = new system_common();
		$template = new template();
		//$file_mode = 'plugin/'.$plugin.'/html/common.html';
		
		if($file){
			if(!$plugin || $plugin==$GLOBALS['sys']['common']){
				$file = $GLOBALS['sys']['system']."/".$plugin."/html/".$file;
			}
			else{
				$file = $GLOBALS['sys']['plugin']."/".$plugin."/html/".$file;
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
	
	function html_logout(){
		if($_SESSION['id']){
			$html = '';
			//$html.= '<a href="?mode=logout" class="navbar-brand">';
			$html.= '<a href="?m=logout">';
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
	
}