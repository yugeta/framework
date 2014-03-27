<?

class input_edit{
	
	function index(){
		$tpl = new TEMPLATE();
		
		return $tpl->read_tpl(TOOL.$_REQUEST[tool].'/tpl/'.$_REQUEST[menu].'.html');
	}
	
	function html_source(){
		if(!$_REQUEST['ini']){return;}
		
		$dir = "data/".$_REQUEST['tool']."/".UID."/";
		
		if(!file_exists($dir.$_REQUEST['ini'].".html")){return;}
		
		$html = file_get_contents($dir.$_REQUEST['ini'].".html");
		$html = str_replace("<","&lt;",$html);
		$html = str_replace(">","&gt;",$html);
		
		//return $dir.$_REQUEST['ini'].".html";
		return $html;
	}
	
	function write(){
		
		$dir = "data/".$_REQUEST['tool']."/".UID."/";
		if(!is_dir($dir)){
			mkdir($dir,0777,true);
		}
		if($_REQUEST['ini']){
			file_put_contents($dir.$_REQUEST['ini'].".html",$_REQUEST['view_html']);
		}
		
		$url = new URL();
		$url->move($url->url()."?tool=".$_REQUEST['tool']."&menu=".$_REQUEST['menu'].(($_REQUEST['ini'])?"&ini=".$_REQUEST['ini']:""));
	}
	
	function input_link(){
		if(!$_REQUEST['ini']){return;}
		
		$url = new URL();
		$uri = $url->url()."?tool=".$_REQUEST['tool']."&menu=input&ini=".$_REQUEST['ini'];
		
		return "<a href='".$uri."' target='_blank'>".$uri."</a>";
	}
	
	
}