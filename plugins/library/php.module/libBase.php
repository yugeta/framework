<?php

/**
* 文字数縮小の為に62進数に変更
* 62bit
**/

class libBase{

	/**
	* hex-64
	*/

	// 62bit
	function chars($bit=62){
		if($bit==62){
			return array_merge(
				range(0,9),//10
				range('a','z'),//26
				range('A','Z')//26
			);
		}
		else if($bit==64){
			return array_merge(
				range(0,9),
				range('a','z'),
				range('A','Z'),
				array("-","+")
			);
		}
		else if($bit==80){
			return array_merge(
				range(0,9),
				range('a','z'),
				range('A','Z'),
				array("-","+","!","$","%","^","~","|",
				"(",")","[","]","{","}","<",">","*","@"
				)
			);
		}
		else if($bit==100){
			return array_merge(
				range(0,9),
				range('a','z'),
				range('A','Z'),
				array("-","+","!","$","%","^","~","|",
				"(",")","[","]","{","}","<",">","*","@",
				"","","","","","","","","","",
				"","","","","","","","","",""
				)
			);
		}
	}

	// num->id
	function encode($n,$bit=62){

		$char = $this->chars($bit);

		$cn = count($char);

		$str = '';
		while ($n != 0) {
			$a1 = (int) ($n / $cn);
			$a2 = $n - ($a1*$cn);

			$str = $char[$a2].$str;
			$n = $a1;
		}

		return $str;
	}

	// id->num
	function decode($n,$bit=62){

		$char = $this->chars($bit);

		$cn = count($char);
		for ($i=0; $i<$cn; $i++) {
			$chars[$char[$i]] = $i;
		}

		$str = 0;
		for ($i=0; $i<strlen($n); $i++) {
			$str += $chars[substr($n, ($i+1)*-1, 1)] * pow($cn, $i);
		}

		return $str;
	}

	/**
	 * hex-64
	 */


}
