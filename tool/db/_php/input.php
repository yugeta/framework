<?

class input{
	
	function index(){
		$tpl = new TEMPLATE();
		
		echo $tpl->read_tpl(TOOL.$_REQUEST[tool].'/tpl/'.$_REQUEST[menu].'.html');
		exit();
	}
	
	
	
	function data_html(){
		if(!$_REQUEST['ini']){return;}
		
		$db_common = new DB_COMMON();
		$db_common->ini_set($_REQUEST['ini']);
		
		$tpl = file_get_contents("data/".$_REQUEST['tool']."/".UID."/".$_REQUEST['ini'].".html");
		
		//----------
		//置き換え文字
		//----------
		
		//name
		$tpl = $this->replace_name($tpl);
		
		//value
		$tpl = $this->replace_value($tpl,$_REQUEST['ini']);
		
		//option
		$tpl = $this->replace_option($tpl,$_REQUEST['ini']);
		
		return $tpl;
	}
	
	//-----
	// tpl-replace
	//-----
	function replace_name($tpl){
		if(preg_match_all("|<!--%name:(.*?)%-->|" , $tpl , $match)){
			for($i=0,$c=count($match[1]);$i<$c;$i++){
				$id  = $match[1][$i];
				$val = "<!--%name:".$id."%-->";
				$rep = "data[".$id."]";
				$tpl = str_replace($val , $rep , $tpl);
			}
		}
		return $tpl;
	}
	function replace_value($tpl,$ini){
		if(preg_match_all("|<!--%value:(.*?)%-->|" , $tpl , $match)){
			for($i=0,$c=count($match[1]);$i<$c;$i++){
				$ini_data = $GLOBALS['ini_data'][$ini]['data'];
				$id  = $match[1][$i];
				$val = "<!--%value:".$id."%-->";
				$rep = (($ini_data[$id][5]!="")?$ini_data[$id][5]:"");
				$tpl = str_replace($val , $rep , $tpl);
			}
		}
		return $tpl;
	}
	function replace_option($tpl,$ini){
		if(preg_match_all("|<!--%option:(.*?)%-->|" , $tpl , $match)){
			for($i=0,$c=count($match[1]);$i<$c;$i++){
				$dat_data = $GLOBALS['dat_data'][$ini];
				$id  = $match[1][$i];
				$val = "<!--%option:".$id."%-->";
				$rep = array();
				if(is_array($dat_data[$id])){//echo var_dump($dat_data[$id])."/";
					foreach($dat_data[$id] as $key=>$d){
						$rep[] = "<option value='".$key."'>".$d."</option>";
					}
				}
				$tpl = str_replace($val , join("\n",$rep) , $tpl);
			}
		}
		return $tpl;
	}
	
	//-----
	//data-write
	//-----
	function write(){
		/*
		for($i=0,$c=count($_REQUEST['data']);$i<$c;$i++){
			echo $i." : ".$_REQUEST['data'][$i]."<br>";
		}
		*/
		foreach($_REQUEST['data'] as $key=>$val){
			echo $key." : ".$val."<br>";
		}
		
		exit();
	}
	
}