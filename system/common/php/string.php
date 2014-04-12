<?

class STRING{
	
	function fileNameEncode($val){
		$val = str_replace(",","&#44;",$val);
		$val = str_replace(":","&#58;",$val);
		$val = str_replace("/","&#47;",$val);
		return $val;
	}
	function fileNameDecode($val){
		$val = str_replace("&#44;",",",$val);
		$val = str_replace("&#58;",":",$val);
		$val = str_replace("&#47;","/",$val);
		return $val;
	}
	
}