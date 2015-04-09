<?php
/**
 * Folder within load PHP.
 */
class fw_index extends fw_define{

    // loadLibrary
    function loadModule($plugin){

        if(!$plugin){return;}

        //基本フォルダの指定
        $path = $this->define_plugins."/".$plugin."/php.module/";

        //exist-check
        if(!is_dir($path)){return;}

        //基本モジュールの読み込み
        $libs = scandir($path);

        //phpモジュールの読み込み
        for($i=0,$c=count($libs);$i<$c;$i++){

            //拡張子が.php以外は対象外
            if(!preg_match("/\.php$/",$libs[$i])){continue;}

            //include処理
            require_once $path.$libs[$i];
        }
    }

}
