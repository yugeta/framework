<?php

class info extends fw_define{

	function data(){
		return "aaa";
	}
	function url(){
		$libUrl = new libUrl();
		return $libUrl->getUri();
	}
	function server($key){
		return $_SERVER[$key];
	}
	function getDate(){
		return date("Y")."/".date("m")."/".date("d")." ".date("H").":".date("i").":".date("s");
	}

	function cookie(){
		$data="";
		foreach($COOKIE as $key=>$val){
			$data .= $key."=".$val."<br>"."\n";
		}
		if(!$data){$data="--Non-data";}
		return $data;
	}
	function getSession(){
		$data="";
		foreach($SESSION as $key=>$val){
			$data .= $key."=".$val."<br>"."\n";
		}
		if(!$data){$data="--Non-data";}
		return $data;
	}
}
