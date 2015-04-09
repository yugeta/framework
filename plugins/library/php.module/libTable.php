<?php

class libTable extends fw_define{

	//Json-data to Table-view
	/**
	 * ex) {"a":"123","b","456"}
	 * |
	 * |
	 */

	function viewJson2Table($jsonFilePath){
		if(!is_file($jsonFilePath)){return;}

		$json = json_decode(file_get_contents($jsonFilePath),true);

		foreach($json as $key=>$val){

		}

		//return "viewJson2Table";
	}

	/**
	 * csv to talbe-view
	 * param @ $csvFilePath : root-path
	 * $flg @ [ none:record-only table:add-table-tag ]
	 */
	function viewCsv2Table($csvFilePath,$flg="none"){
		if(!is_file($csvFilePath)){return;}

		$records = explode("\n",file_get_contents($csvFilePath));

		$num = 1;
		$html = "";
		if($flg=="table"){$html.= "<table>";}
		for($i=0;$i<count($records);$i++){
			//空行はスキップする
			if($records[$i]===""){continue;}
			//セル処理
			$split = explode(",",$records[$i]);
			$html.= "<tr>";
			$html.= "<th>".$num."</th>";
			for($j=0;$j<count($split);$j++){
				$html.= "<td>".$split[$j]."</td>";
			}
			$html.= "</tr>";
			$html.= "\n";
			$num++;
		}
		if($flg=="table"){$html.= "</table>";}

		return $html;
	}


}
