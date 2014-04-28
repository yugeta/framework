<?
//----------
//Cookie処理
//----------

class COOKIE{
	
	//Cookie（保持時間）
	public $id = 'fw';
    /*
	public $d = 30;
	public $h = 0;
	public $m = 0;
	public $s = 0;
	*/
    
	//書き込み※[time:保持時間は秒で指定]
	function write($id='',$data ,$sec){
        if(!$id){$id = $this->id;}
		$time = (time() + ($sec*1) );
        setcookie($id , $data , $time , "/");
	}
	
    //読み込み
    function read($id=''){
        if(!$id){$id = $this->id;}
        return $_COOKIE[$id];
    }
    
	//削除
	function clear($id=''){
        if(!$id){$id = $this->id;}
		setcookie($id,'',-1,'/');
	}
	
}