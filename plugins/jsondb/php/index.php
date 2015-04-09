<?php

new jsonDB_index();

class jsonDB_index extends fw_define{

	function __construct(){
		if(!isset($_REQUEST['mode'])){$_REQUEST['mode']="";}

		$jsonDB = new jsonDB();

		if($_REQUEST['mode']=="upload"){
			die("upload");
			//redirect
			$url = new libUrl();
			$url->setUrl($url->getUrl());
		}
		else if($_REQUEST['mode']=="ajax"){
			$dotflow->setFlow($_REQUEST['loadType'],$_REQUEST['format']);
			exit();
		}
		else if($_REQUEST['mode']=="download"){
			$dotflow->getDownload($_REQUEST['format'],$_REQUEST['uuid']);
			exit();
		}
		else if($_REQUEST['menu']=="list_table" && $_REQUEST['mode']=="addData"){
			$jsonDB->setDataAdd();
			exit();
		}
		else if($_REQUEST['menu']=="list_table" && $_REQUEST['mode']=="delete"){
			$jsonDB->setDataDel();
			exit();
		}

		else if($_REQUEST['menu']=="list_field" && $_REQUEST['mode']=="addData"){
			$jsonDB->setTableAdd($_REQUEST['data']);
			exit();
		}






	}
}
