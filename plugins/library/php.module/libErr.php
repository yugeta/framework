<?php

class libErr extends fw_define{

	function view($msg){

		$GLOBALS["message"] = $msg;
		$this->fw_pluginView($_REQUEST['plugins'],"error");
		exit();
	}

}
