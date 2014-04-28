framework
=========

* 概要 : Sumally ----------
- サービスを構築する際の自分用フレームワーク。

* 機能 : Function ----------
- 

* 仕様 ----------
- [URL]
  + http://hoge.com/index.php?p=***&
- [Query]
  + p : plugin(api)[default=common]
  + h : htmlファイル（ヘッダ、フッタ、メニュー、各種モジュールの基本セット）[default=common]
  + c : contensデータ（内部表示部分）[default=common]
  + m : mode
  + a : action
  

* 基本構成 ----------
- login,logout
- 認証後->toolモジュール実行
- 階層

  / *Root Directory
  ├─ data/
  │  ├─ common/
  │  │  ├─ config.dat
  │  │  ├─ openid.dat
  │  │  └─ user.dat
  │  │
  │  └─ *plugins...
  │
  ├─ plugin/
  │  │
  │  ├─ bootstrap/
  │  └─ *plugins.../
  │
  ├─ system/
  │  ├─ common/
  │  │  ├─ css
  │  │  ├─ html
  │  │  ├─ img
  │  │  ├─ js
  │  │  └─ php
  │  │
  │  ├─ bootstrap/
  │  │  ├─ css
  │  │  ├─ fonts
  │  │  └─ js
  │  │
  │  └─ openid/
  │
  ├─ .htaccess
  └─ index.php
  
  + /data [*any]
  + /data/system/config.dat
  + /data/system/users.dat
  + /plugin [*any]
  + /plugin/system/[css,js,html,php,img]


* インストール ----------
- git clone
- 対象フォルダにブラウザでアクセスして、初回設定を行う。

* 各種設定 ----------
- データベース選択
  + file
  + mysql(-)
  + mongodb(-)
  + couchdb(-)

- OAUTH認証
  + Gmail
  + Facebook(-)
  + twitter(-)
  + mixi(-)
  
- Pluginの登録



* フロー ----------
- 1) index.php
  + login認証(Oauth認証)
  + Account-regist(新規追加)
  + Top-page(*any)
  
- 2) login認証後
  + plugin処理
  + system-Account(アップデート)

* 注意事項 ----------
- plugin名で指定できない名前
  + システムでフォルダ作成できない文字列（基本は英数字のみが望ましい）
  + common （※システムでデフォルト使用しているため -> 設定変更できます）
  
* 使用方法概要 ----------
- クエリ情報
  + TOP
    http://hoge.com/
    
  + 静的ページ
    http://hoge.com/?html=***.html
  - 縮小型
    http://hoge.com/?h=***.html
    
  + ログイン後※認証後（プラグインページ）
    http://hoge.com/?plugin=test
  - 縮小型
    http://hoge.com/?p=test
    
  + プラグインのサブメニュー
    http://hoge.com/?plugin=test&mode=set
  - 縮小型
    http://hoge.com/?p=test&m=set
  
  + プラグインのアクション処理
    http://hoge.com/?plugin=test&mode=**&action=write
  - 縮小型
    http://hoge.com/?p=test&**&a=write
    
  + 強制関数起動（ログインしている事が条件※ログインしていない場合は静的ページのみ）
    http://hoge.com/?plugin=test&class=%setClass%&function=%setFunction%&data[0]=aaa&data[1]=bbb
  - 縮小型
    http://hoge.com/?p=test&c=set1&f=set2&d[0]=aaa&d[1]=bbb
  
  + システム管理
  - login,logout,account-settiong,openid,
  
  * p: plugin
  * h: html
  * m: mode
  * c: class
  * f: function
  * a: action
  * d: data
  * s: system
  * 

* oauth

- facebook
https://www.facebook.com/dialog/oauth?client_id=579105722099598&redirect_uri=https://app.streem.com/_oauth/facebook?close&display=popup&scope=email&state=BGXvHSYthYg5fS6nX


