<?php

class sample_common extends fw_define{

	function getListDesign(){

		//echo $this->define_design;

		$lists = scandir($this->define_design);

		$arr = array();

		for($i=0;$i<count($lists);$i++){
			if($lists[$i]=="." || $lists[$i]==".."){continue;}
			$arr[] = $lists[$i];
		}

		return $arr;
	}

	function viewSelectDesign($config,$key="theme"){

		if(!isset($GLOBALS['data'])
		|| !isset($GLOBALS['data'][$config])
		|| !isset($GLOBALS['data'][$config][$key])){
			$GLOBALS['data'][$config][$key] = "";
		}

		$html = "";

		$lists = $this->getListDesign();

		for($i=0;$i<count($lists);$i++){
			$sel="";
			if($lists[$i]==$GLOBALS['data'][$config][$key]){$sel = "selected";}

			$html.= "<option value='".$lists[$i]."' ".$sel.">".$lists[$i]."</option>\n";
		}

		return $html;
	}

	function viewSelectPlugins(){
		$html = "";
		$html.= "<option value='".$this->sample."'>".$this->sample."</option>"."\n";
		//$html.= "<option value='".$this->define_library."'>".$this->define_library."</option>"."\n";

		$lists = scandir($this->define_plugins);

		for($i=0;$i<count($lists);$i++){
			if($lists[$i]=="." || $lists[$i]==".."){continue;}
			//if($lists[$i]==$this->define_library){continue;}
			if($lists[$i]==$this->sample){continue;}

			$sel="";
			if(isset($_REQUEST['config']) && $lists[$i]==$_REQUEST['config']){$sel = "selected";}

			$html.= "<option value='".$lists[$i]."' ".$sel.">".$lists[$i]."</option>"."\n";
		}

		return $html;
	}

	function viewSelectDefaultPlugin(){
		$html = "";
		//$html.= "<option value='".$this->sample."'>".$this->sample."</option>"."\n";
		//$html.= "<option value='".$this->define_library."'>".$this->define_library."</option>"."\n";

		$lists = scandir($this->define_plugins);

		for($i=0;$i<count($lists);$i++){
			if($lists[$i]=="." || $lists[$i]==".."){continue;}
			if($lists[$i]==$this->define_library){continue;}
			if($lists[$i]==$this->sample){continue;}

			$sel="";
			if($lists[$i]==$GLOBALS['data']['config']['default_plugin']){$sel = "selected";}

			$html.= "<option value='".$lists[$i]."' ".$sel.">".$lists[$i]."</option>"."\n";
		}

		return $html;
	}

	function getGlobals($config,$key){
		if(!isset($GLOBALS['data'])
		|| !isset($GLOBALS['data'][$config])
		|| !isset($GLOBALS['data'][$config][$key])){return;}
		return $GLOBALS['data'][$config][$key];
	}

	function getJson($fileName){
		if(!$fileName){return;}
		$path = $this->define_plugins."/".$_REQUEST['config']."/data/".$fileName.".json";
		if(!is_file($path)){return;}

		return file_get_contents($path);
	}
}
