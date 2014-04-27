<?
/**
 * 画像関連操作
**/

class IMAGE{
    
    //サムネイル作成[対象画像ファイルパス , 長辺のサイズ]
    function thumb($path , $file_name , $size){
        
        if(!file_exists($path)){return;}
        
        //ファイルパスをファイル名と拡張子に分解
        $paths = explode('/',$path);
        $name = array_pop($paths);
        $file_names = explode('.',$name);
        $kaku = array_pop($file_names);
        $new_file = join('.',$file_names).'.thumb';
        
        //サイズ取得
        $file_size = getimagesize($path);
        
        $x = $file_size[0];
        $y = $file_size[1];
        
        //サムネイルサイズ計算
        //横長（正方形）
        if($x >= $y){
            $thumb_x = $size;
            $thumb_y = (int)($y / ($size / $x));
        }
        //縦長
        else{
            $thumb_x = (int)($x / ($size / $t));
            $thumb_y = $size;
        }
        
        //
        //画像サイズ変更
        $image_p = imagecreatetruecolor($thumb_x, $thumb_y);
        $image = imagecreatefromjpeg($path);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $thumb_x, $thumb_y, $x, $y);
        imagejpeg($image_p, join('/',$paths).'/'.$new_file.'.'.$kaku);
        
        file_put_contents(join('/',$paths).'/'.$new_file.'.txt', $x.'/'.$y);
        
        /*
        $image_in = imagecreatefrompng($path);
        $image_out = imagecreate($thumb_x,$thumb_y);
        imagecopyresized( $image_out, $image_in, 0, 0, 0, 0, $thumb_x, $thumb_y, $x, $y );
        
        imagepng( $image_out, $new_file.'.'.$kaku );
        imagedestroy( $image_in );
        imagedestroy( $image_out );
        */
        
    }
    
    
    /**
     * thumbnail proc
    **/
    public $thumb_ext  = '.s.jpg';
    public $thumb_exif = 'txt';
    public $thumb_size = 120;
    
    // image-file -> thumbnail (***.png -> ***.s.jpg)
    function thumbnail($file,$thumb_file=null){
        
    }
    
    function pdf2thumb($file,$thumb_file=null){
        
        $thumb_file = ($thumb_file)?$thumb_file:$this->file_name2thumb_name($file);
        
        // already exists case delete
        if(file_exists($thumb_file)){
            unlink($thumb_file);
        }
        
        $im = new imagick();
        //画像を生成したいPDFを読み込む
        //$page=0;
        //$im->readImage($file.((!$page)?0:'['.$page.']'));
        $im->readImage($file.'[0]');
        
        //PDFのページ
        //$im->setImageIndex(0);
        
        //trim
        $im->trimImage(0);
        
        //サムネイルサイズ 640pxに収める
        $im->thumbnailImage($this->thumb_size, $this->thumb_size, true);
        
        //シャープ
        //$im->sharpenImage(0, 1);
        
        // get image size
        $size = $im->getImageGeometry();
        
        //生成
        $this->file2make_dir($thumb_file);
        $im->writeImage($thumb_file);
        
        $im->destroy();
        
        return $thumb_file;
    }
    function zip2thumb($file,$thumb_file=null){
        $str = new STRING();
        
        //rename
        $thumb_file = ($thumb_file)?$thumb_file:$this->file_name2thumb_name($file);
        
        $temp = date(YmdHis);
        
        $tmp_folder = 'data/.tmp/'.$temp."/";
        
        if(!is_dir($tmp_folder)){
            mkdir($tmp_folder,0777,true);
        }
        
        $conv1 = array(" ","(",")","[","]");
        $conv2 = array("\ ","\(","\)","\[","\]");
        
        $cmd = 'unzip -d '.$tmp_folder.' -jo '.str_replace($conv1,$conv2,$file);
        //exec($cmd, $res);
        $res = `$cmd`;
        
        $pick_file = $this->first_img_in_dir($tmp_folder);
        
        if($pick_file && file_exists($tmp_folder.$pick_file)){
            $this->file2make_dir($thumb_file);
            $this->img2thumb($tmp_folder.$pick_file,$thumb_file,$this->thumb_size);
        }
        else{
            $thumb_file="";
        }
        
        exec('rm -rf '.$tmp_folder);
        
        return $thumb_file;
    }
    function rar2thumb($file,$thumb_file=null){
        $str = new STRING();
        //$dir = new FOLDER();
        //echo "thumb:".$thumb_file."\n";
        
        //rename
        $thumb_file = ($thumb_file)?$thumb_file:$this->file_name2thumb_name($file);
        
        //echo "file:".$file."\n";
        //echo "thumb:".$thumb_file."\n";
        //exit;
        
        $temp = date(YmdHis);
        
        $tmp_folder = 'data/.tmp/'.$temp."/";
        
        if(!is_dir($tmp_folder)){
            mkdir($tmp_folder,0777,true);
        }
        
        $conv1 = array(" ","(",")");
        $conv2 = array("\ ","\(","\)");
        
        $cmd = 'unrar e -o- '.str_replace($conv1,$conv2,$file).' '.$tmp_folder;
        $res = `$cmd`;
        
        $pick_file = $this->first_img_in_dir($tmp_folder);
        
        if($pick_file && file_exists($tmp_folder.$pick_file)){
            $this->file2make_dir($thumb_file);
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
        
        //portrate
        $img_size = $im->getImageGeometry();
        if($img_size[width]/$img_size[height] >= 1.4){
            //resize
            $im->cropImage(($img_size[width]/2),$img_size[height],0,0);
            
        }
        
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
    // file name of thumbnail. *file_name -> thumb name.
    function file_name2thumb_name($file){
        
        $sp = explode('.',$file);
        $ext = array_pop($sp);
        
        return join('.',$sp).$this->thumb_ext;
        
    }
    // book front page is portrait. landscape -> portrait
    // This is book cover.
    function img_size2portrate($img){
        
        //size check ( one and half )
        
        
        
        
        
    }
    
    //file -> make dir
    function file2make_dir($file){
        
        $file2 = explode('?',$file);
        $file = $file2[0];
        
        if(!$file){return;}
        $sp = explode('/',$file);
        
        $file_name = array_pop($sp);
        $dir = join('/',$sp);
        
        if(is_dir($dir)){return;}
        
        mkdir($dir,0777,true);
        
    }
    
}

