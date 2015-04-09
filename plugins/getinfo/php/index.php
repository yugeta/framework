<?php

new jsonDB_index();

class jsonDB_index extends fw_define{

	function __construct(){
		if(!isset($_REQUEST['mode'])){$_REQUEST['mode']="";}
		
	}
}
