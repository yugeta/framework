framework
=========

* 概要 : Sumally
- サービスを構築する際の自分用フレームワーク。

* 機能 : Function
- 

* 仕様
- [URL]
  + http://hoge.com/index.php?p=***&
- [Query]
  + p : plugin(api)[default=common]
  + h : htmlファイル（ヘッダ、フッタ、メニュー、各種モジュールの基本セット）[default=common]
  + c : contensデータ（内部表示部分）[default=common]
  + m : mode
  + a : action
  

* 基本構成
- login,logout
- 認証後->toolモジュール実行
- 階層
  + /.htaccess
  + /index.php
  + /data [*any]
  + /data/system/config.dat
  + /data/system/users.dat
  + /plugin [*any]
  + /plugin/system/[css,js,html,php,img]


* インストール
- git clone
- 対象フォルダにブラウザでアクセスして、初回設定を行う。

* 各種設定
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



* フロー
- 1) index.php
  + login認証(Oauth認証)
  + Account-regist(新規追加)
  + Top-page(*any)
  
- 2) login認証後
  + plugin処理
  + system-Account(アップデート)






