<?php
/**
 *　framework 表示処理
 */
date_default_timezone_set('Asia/Tokyo');

class fw_define{

    // プラグインフォルダ
    public $define_plugins = "plugins";

    // システム用プラグイン
    public $define_library = "library";

    // デザインテーマ
    public $define_design = "design";
    public $define_theme  = "default";

    // default-plugin
    public $sample = "config";

    // default-file(.php | .html)
    public $index = "index";


    public $session_name = "framework_auth";

    function getPath($plugins,$library){

        return $this->{$plugins}."/".$this->{$library};

    }

    /**
     * Module-set
     */

    function fw_module(){

        //基本システム関数ファイル
        $fw_index_file = $this->define_plugins."/".$this->define_library."/php/".$this->index.".php";

        //基本システム関数ファイル-check
        if(!is_file($fw_index_file)){$this->fw_error("||| Not-common-file : ".$fw_index_file);}

        //基本システム関数ファイル-読み込み
        require_once $fw_index_file;

        //基本ライブラリ読み込み関数-宣言
        $fw_index = new fw_index();

        //基本ライブラリ読み込み関数-実行
        $fw_index->loadModule($this->define_library);
    }

    /**
     * System-Query
     */

    function fw_query(){

        //libraryのconfig.jsonの読み込み
        $system_config_file = $this->define_plugins."/".$this->define_library."/data/config.json";
        if(is_file($system_config_file)){
            $GLOBALS['library'] = json_decode(file_get_contents($system_config_file),true);
        }

        //指定plugin(ない場合は「sample」を起動）※短縮key処理有り
        if(!isset($_REQUEST['plugins'])){
            $_REQUEST['plugins'] = "";

            if(isset($GLOBALS['library']['default-plugin'])){
                $_REQUEST['plugins'] = $GLOBALS['library']['default-plugin'];
            }
            else{
                $_REQUEST['plugins'] = $this->sample;
            }
        }

        //起動phpファイルの指定（ない場合は「$this->index」を指定）
        if(!isset($_REQUEST['php'])){$_REQUEST['php'] = $this->index;}

        //起動phpファイルの指定（ない場合は「$this->index」を指定）
        if(!isset($_REQUEST['html'])){$_REQUEST['html'] = $this->index;}

        //コンフィグデータの読み込み
        $conf_file = $this->define_plugins."/".$_REQUEST['plugins']."/data/config.json";
        $GLOBALS['config'] = array();
        if(is_file($conf_file)){
            $GLOBALS['config'] = json_decode(file_get_contents($conf_file),true);
        }

        //デザインテーマの指定
        if(!isset($GLOBALS['config']['theme'])){$GLOBALS['config']['theme'] = $this->define_theme;}
    }

    /**
     * Plugin-Action
     */

    function fw_pluginAction($plugin,$action=null,$folder="php"){

        if(is_null($action) || !$action){return;}

        //指定プラグインmoduleの読み込み
        $fw_index = new fw_index();
        $fw_index->loadModule($plugin);

        //指定pluginの起動phpファイル
        $fw_plugin_index = $this->define_plugins."/".$plugin."/".$folder."/".$action.".php";

        //指定pluginの起動phpファイル-check
        if(!is_file($fw_plugin_index)){$this->fw_error("||| Not-php-file : ".$fw_plugin_index);}

        //指定pluginの起動phpファイル-読み込み（実行）
        require_once $fw_plugin_index;
    }

    /**
     * Plugin-View
     */

    function fw_pluginView($plugin,$view=null){

        if(is_null($view) || !$view){return;}

        //指定pluginの表示htmlファイル
        //$fw_plugin_index = $this->define_plugins."/".$plugin."/html/".$view.".html";
        $fw_plugin_index = $this->define_design."/".$GLOBALS['config']['theme']."/html/".$view.".html";
        //echo $fw_plugin_index;

        //指定pluginの表示htmlファイル-check
        if(!is_file($fw_plugin_index)){$this->fw_error("||| Not-html-file : ".$fw_plugin_index);}

        //指定pluginの表示htmlファイル-表示
        $libView = new libView();
        //$libView->viewContents($fw_plugin_index);
        echo $libView->viewContents($fw_plugin_index);
    }

    /**
     * System-Error
     */

    function fw_error($val){
        die($val);
    }

    function fw_menu($plugins){

        if(!$plugins){return;}

        $plugin_menu_path = $this->define_plugins."/".$plugins."/data/menu.json";
        if(!is_file($plugin_menu_path)){return;}

        $json = json_decode(file_get_contents($plugin_menu_path),true);

        $html = "";
        $url = new libUrl();
        $path = $url->getUrl();

        for($i=0;$i<count($json);$i++){
            $menu = ($json[$i]['menu'])?"?menu=".$json[$i]['menu']:"";
            $html.= "<li><a href='".$path.$menu."'>".$json[$i]['name']."</a></li>"."\n";
        }

        return $html;
    }

    function fw_auth(){
        $libAuth = new libAuth();
        return true;

    }
}

class fw_root extends fw_define{

    // 起動時に自動的に実行
    function __construct(){

        //基本モジュールセット
        $this->fw_module();

        //システム変数（クエリ）の調整
        $this->fw_query();

        //認証
        session_name($this->session_name);
        session_start();
        $libAuth = new libAuth();
        if(!$libAuth->fw_auth()){
            $this->fw_pluginView($_REQUEST['plugins'],"login");
            return;
        }

        //指定pluginの読み込み
        $fw_index = new fw_index();
        $fw_index->loadModule($_REQUEST['plugins']);

        //表示前処理
        $start_php = $this->define_plugins."/".$_REQUEST['plugins']."/php/".$this->index.".php";
        if(is_file($start_php)){
            require_once $start_php;
        }

        //指定pluginの表示
        $this->fw_pluginView($_REQUEST['plugins'],$_REQUEST['html']);

    }
}

$viewIndex = new fw_root();
