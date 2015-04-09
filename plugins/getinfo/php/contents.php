<?php

new jsonDB_contents();

class jsonDB_contents extends fw_define{

	function __construct(){
		if(!isset($_REQUEST['menu'])){$_REQUEST['menu']="";}
		//$this->setGlobals();

		$fw_define = new fw_define();
		$libView   = new libView();

		$file = $this->define_plugins."/".$_REQUEST['plugins']."/html/contents.html";
		if($_REQUEST['menu'] && is_file($this->define_plugins."/".$_REQUEST['plugins']."/html/".$_REQUEST['menu'].".html")){
			$file = $this->define_plugins."/".$_REQUEST['plugins']."/html/".$_REQUEST['menu'].".html";
		}

		echo $libView->file2HTML($file);
	}
}
