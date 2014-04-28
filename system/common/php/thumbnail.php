<?
/**
Make thumbnail
**/

class THUMBNAIL{
    
    //thumb-nail
    public $thumb_ext  = '.s.jpg';
    public $thumb_exif = 'txt';
    public $thumb_size = 120;
    
    //pdf (n)page make thumb-nail
    function pdf($file,$thumb_file=null,$page=null){
        
        if(!$thumb_file){
            $thumb_file = $this->file2thumb($file);
        }
        // already exists case delete
        if(file_exists($thumb_file)){
            unlink($thumb_file);
        }
        
        $im = new Imagick();
        //画像を生成したいPDFを読み込む
        //$page=0;
        //$im->readImage($file.((!$page)?0:'['.$page.']'));
        $im->readImage($file);
        
        //PDFのページ
        $im->setImageIndex(0);
        
        //trim
        $im->trimImage(0);
        
        //サムネイルサイズ 640pxに収める
        $im->thumbnailImage($this->thumb_size, $this->thumb_size, true);
        
        //シャープ
        //$im->sharpenImage(0, 1);
        
        // get image size
        $size = $im->getImageGeometry();
        
        //生成
        $im->writeImage($thumb_file);
        
        $im->destroy();
        
        return $thumb_file;
    }
    
    function zip($file,$thumb_file=null){
        
        $str = new STRING();
        //$dir = new FOLDER();
        
        //rename
        if(!$thumb_file){
            $thumb_file = $this->file2thumb($file);
        }
        
        $temp = date(YmdHis);
        
        $tmp_folder = 'data/.tmp/'.$temp."/";
        
        if(!is_dir($tmp_folder)){
            mkdir($tmp_folder,0777,true);
        }
        
        $cmd = 'unzip -d '.$tmp_folder.' -j '.str_replace(" ","\ ",$file);
        //exec($cmd, $res);
        $res = `$cmd`;
        
        $pick_file = $this->first_img_in_dir($tmp_folder);
        
        if($pick_file && file_exists($tmp_folder.$pick_file)){
            
            $this->img2thumb($tmp_folder.$pick_file,$thumb_file,$this->thumb_size);
        }
        else{
            $thumb_file="";
        }
        
        exec('rm -rf '.$tmp_folder);
        
        return $thumb_file;
        
    }
    
    function rar($file,$thumb_file=null){
        
        $str = new STRING();
        //$dir = new FOLDER();
        
        //rename
        if(!$thumb_file){
            $thumb_file = $this->file2thumb($file);
        }
        
        $temp = date(YmdHis);
        
        $tmp_folder = 'data/.tmp/'.$temp."/";
        
        if(!is_dir($tmp_folder)){
            mkdir($tmp_folder,0777,true);
        }
        
        //$cmd = 'unrar '.str_replace(" ","\ ",$file).' '.$tmp_folder;
        $cmd = 'unrar e '.str_replace(" ","\ ",$file).' '.$tmp_folder;
        //exec($cmd, $res);
        $res = `$cmd`;
        
        $pick_file = $this->first_img_in_dir($tmp_folder);
        
        if($pick_file && file_exists($tmp_folder.$pick_file)){
            
            $this->img2thumb($tmp_folder.$pick_file,$thumb_file,$this->thumb_size);
            
        }
        else{
            $thumb_file="";
        }
        
        exec('rm -rf '.$tmp_folder);
        
        return $thumb_file;
        
    }
    
    // big image to thumb size.
    function img2thumb($in,$out,$size){
        $im = new Imagick();
        $im->readImage($in);
        
        //trim
        $im->trimImage(0);
        
        //サムネイルサイズ 640pxに収める
        $im->thumbnailImage($size, $size, true);
        
        //生成
        $im->writeImage($out);
        
        $im->destroy();
    }
    
    // in folder. first file(jpeg) is return.
    function first_img_in_dir($tmp_folder){
        $dir = new FOLDER();
        $lists = $dir->lists($tmp_folder);
        
        for($i=0,$c=count($lists);$i<$c;$i++){
            if(preg_match('/.jpg$/i',$lists[$i])){
                return $lists[$i];
                break;
            }
        }
    }
    
    
    //jpeg , gif , bmp , png
    function img($file,$thumb_file=null){
        
        //ファイルパスをファイル名と拡張子に分解
        //サイズ取得
        list($x,$y) = getimagesize($file);
        
        //サムネイルサイズ計算
        //横長（正方形）
        if($x >= $y){
            $thumb_x = $this->thumb_size;
            $thumb_y = (int)($y / ($x / $this->thumb_size));
        }
        //縦長
        else{
            $thumb_x = (int)($x / ($y / $this->thumb_size));
            $thumb_y = $this->thumb_size;
        }
        
        //サムネイルファイル名
        $sp = explode('.',$file);
        $ext = strtolower(array_pop($sp));
        
        //画像サイズ変更
        $image_p = imagecreatetruecolor($thumb_x, $thumb_y);
        
        
        if($ext == 'jpeg' || $ext == 'jpg'){
            $image = imagecreatefromjpeg($file);
        }
        else if($ext=='gif'){
            $image = imagecreatefromgif($file);
        }
        else if($ext=='png'){
            $image = imagecreatefrompng($file);
        }
        /*
        else if($ext=='bmp'){
            $image = imagecreatefromwbmp($file);
        }
        */
        
        if(!$thumb_file){
            $thumb_file = join('.',$sp).$this->thumb_ext;
        }
        
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $thumb_x, $thumb_y, $x, $y);
        imagejpeg($image_p, $thumb_file);
        
    }
    
    function avi($file,$thumb_file=null){
        
        
        
    }
    
    function mp4($file,$thumb_file=null){
        
        
        
    }
    
    function mpeg($file,$thumb_file=null){
        
        
        
    }
    
    // file name of thumbnail. *file_name -> thumb name.
    function file2thumb($file){
        
        $sp = explode('.',$file);
        $ext = array_pop($sp);
        
        return join('.',$sp).$this->thumb_ext;
        
    }
    
}