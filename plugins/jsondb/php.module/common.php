<?php

class jsonDB{
	public $dir_data = "data/";
	public $data_config = ".config";

	function getDataTablelines(){
		$datas = $this->getListData();
		$html="";
		$no=1;
		for($i=0;$i<count($datas);$i++){

			$memo="";
			$memoFile = $this->dir_data.$this->data_config."/".$datas[$i].".dat";
			if(is_file($memoFile)){
				$json = json_decode(file_get_contents($memoFile),true);
				if(isset($json['memo']) && $json['memo']){$memo = $json['memo'];}
			}

			$html.= "<tr data-name='".$datas[$i]."'>"."\n";
			$html.= "<th>".$no."</th>"."\n";
			$html.= "<td>".$datas[$i]."</td>"."\n";
			$html.= "<td>".$memo."</td>"."\n";
			$html.= "<td class='option' data-name='".$datas[$i]."'>";
				$html.= "<i class='icon-pencil option-icon' data-type='edit'></i>";
				$html.= "<i class='icon-trash option-icon' data-type='delete'></i>";
				$html.= "<i class='icon-arrow-right option-icon' data-type='field'></i>";
			$html.= "</td>"."\n";
			$html.= "</tr>"."\n";
			$no++;
		}
		return $html;
	}
	function getDataOptions(){
		$datas = $this->getListData();
		$html="";
		for($i=0;$i<count($datas);$i++){
			$memo="";
			$memoFile = $this->dir_data.$this->data_config."/".$datas[$i].".dat";
			if(is_file($memoFile)){
				$json = json_decode(file_get_contents($memoFile),true);
				if(isset($json['memo']) && $json['memo']){$memo = $json['memo'];}
			}
			$selected = "";
			if($datas[$i]==$_REQUEST['data']){$selected="selected";}
			$html.= "<option value='".$datas[$i]."' ".$selected.">".$datas[$i]." (".$memo.")</option>"."\n";
		}
		return $html;
	}

	function getListData(){
		$lists = scandir($this->dir_data.$this->data_config);
		unset($new);
		for($i=0;$i<count($lists);$i++){
			if($lists[$i]=="." || $lists[$i]==".."){continue;}
			//if(!is_dir($this->dir_data.$lists[$i])){continue;}
			if(preg_match("/^\./",$lists[$i])){continue;}

			$table_name = preg_replace("/\.dat$/","",$lists[$i]);

			$new[] = $table_name;
		}
		return $new;
	}

	function setDataAdd(){

		$name = $_REQUEST['d']['name'];
		$memo = $_REQUEST['d']['memo'];

		//data-dir作成
		if($name && !is_dir($this->dir_data.$name)){
			mkdir($this->dir_data.$name , 0777 , true);
		}
		//configフォルダ作成
		if(!is_dir($this->dir_data.$this->data_config)){
			mkdir($this->dir_data.$this->data_config , 0777 , true);
		}
		//jsonデータ作成
		//$json = '{"entry":"'.date("YmdHis").'","name":"'.$name.'","memo":"'.$memo.'"}'."\n";
		$qdata = array(
			"entry"=>date("YmdHis"),
			"name"=>$name,
			"memo"=>$memo
		);
		$json = json_encode($qdata,JSON_PRETTY_PRINT);

		//configファイル作成
		file_put_contents($this->dir_data.$this->data_config."/".$name.".dat" , $json);
		//file_put_contents($this->dir_data.$this->data_config."/".$name.".dat" , $json , FILE_APPEND);

	}
	function setDataDel(){

		$name = $_REQUEST['d']['name'];
		if(!$name){return;}

		$dir  = $this->dir_data.$this->data_config;
		$file = $dir."/".$name.".dat";
		$dirBak = $this->dir_data."bak";
		$bak  = $dirBak."/".$name.".".date("YmdHis").".dat";

		if(!is_dir($dirBak)){mkdir($dirBak,0777,true);}
		if(!is_file($file)){return;}

		rename($file,$bak);
	}

	function setTableAdd($cfgName){

		if(!$cfgName){return;}

		$name = $_REQUEST['d']['name'];
		$memo = $_REQUEST['d']['memo'];

		//data-dir作成
		/*
		if($name && !is_dir($this->dir_data.$name)){
			mkdir($this->dir_data.$name , 0777 , true);
		}
		*/
		//configフォルダ作成
		$dirPath = $this->dir_data.$this->data_config;
		if(!is_dir($dirPath)){
			mkdir($dirPath , 0777 , true);
		}

		//保存ファイル
		$savePath = $dirPath."/".$cfgName.".dat";

		//元データ読み込み
		unset($json);
		if(is_file($savePath)){
			$json = json_decode(file_get_contents($savePath),true);
		}
		else{
			$json = array();
		}

		//jsonデータ追加
		if(!isset($json['fields'])){$json['fields']=array();}
		//$json = '{"entry":"'.date("YmdHis").'","name":"'.$name.'","memo":"'.$memo.'"}'."\n";
		$qdata = array(
			"entry"=>date("YmdHis"),
			"memo"=>$memo
		);
		//array_push($json['fields'],$qdata);
		$json['fields'][$name] = $qdata;

		//configファイル作成
		file_put_contents($savePath , json_encode($json,JSON_PRETTY_PRINT));

		echo $savePath."\n".json_encode($json,JSON_PRETTY_PRINT);
	}

	function getHtmlField(){
		if(!isset($_REQUEST['data']) || !$_REQUEST['data']){return;}
		$dirPath  = $this->dir_data.$this->data_config;
		$filePath = $dirPath."/".$_REQUEST['data'].".dat";
		if(!is_file($filePath)){return;}

		$json = json_decode(file_get_contents($filePath),true);

		if(!isset($json['fields'])){return;}

		$no=1;
		$html="";
		foreach($json['fields'] as $key=>$val){
			$html.= "<tr>";
			$html.= "<th>".$no."</th>";
			$html.= "<td>".$key."</td>";
			$html.= "<td>".$val['memo']."</td>";
			$html.= "</tr>";
			$no++;
		}
		return $html;
	}
}
