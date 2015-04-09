<?php

class sample_update{
	function setData(){

		if($this->checkData()){return;}

		$fw_define = new fw_define();
		$dir  = $fw_define->define_plugins."/".$_REQUEST['config']."/data/";
		if(!is_dir($dir)){mkdir($dir,0777,true);}

		//config.json
		$json = json_encode($_REQUEST['data']);
		file_put_contents($dir."config.json",$json);

		//menu.json
		$menu_json = $dir."menu.json";
		if($_REQUEST['menu']){
			$json1 = json_decode($_REQUEST['menu'],true);
			$json2 = json_encode($json1,JSON_PRETTY_PRINT);
			file_put_contents($menu_json,$json2);
		}
		//delete
		elseif(!$_REQUEST['menu'] && is_file($menu_json)){
			unlink($menu_json);
		}
		

	}

	function checkData(){

	}
}
