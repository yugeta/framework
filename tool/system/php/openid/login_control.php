<?php
require_once "common.php";

// *********************************************************
// 基本オブジェクト作成
// *********************************************************
$store = new Auth_OpenID_FileStore($store_path);
log_file(print_r($store,true));
$consumer = new Auth_OpenID_Consumer($store);
log_file(print_r($consumer,true));

// *********************************************************
// 対象
// *********************************************************
if ( $_GET['id'] != "" ) {
	$openid = $_GET['id'];
	log_file(print_r($openid,true));

	$error_message = "";
	$auth_request = $consumer->begin($openid);
	if (!$auth_request) {
		$error_message = "OpenID が正しくありません";
	}

	if ( $error_message == "" )	 {
		// nickname 等が必要無い場合はここを実行する必要はない
		$sreg_request = 
			Auth_OpenID_SRegRequest::build(
				// Required
				array('nickname'),
				// Optional
				array('fullname', 'email')
			);
		if ($sreg_request) {
			$auth_request->addExtension($sreg_request);
		}
		log_file(print_r($auth_request,true));

		// Google の email 取得 ( friendly は Yahoo で、mixi でも使えました )
		$ax = new Auth_OpenID_AX_FetchRequest;

		$attribute = array();
		$attribute[] = Auth_OpenID_AX_AttrInfo::make('http://axschema.org/contact/email', 1, true,'email');
		$attribute[] = Auth_OpenID_AX_AttrInfo::make('http://axschema.org/namePerson/last', 1, true,'lastname');
		$attribute[] = Auth_OpenID_AX_AttrInfo::make('http://axschema.org/namePerson/first', 1, true,'fastname');
		$attribute[] = Auth_OpenID_AX_AttrInfo::make('http://axschema.org/namePerson/friendly', 1, true,'friendly');


		foreach($attribute as $attr){
			$ax->add($attr);
		}
		$auth_request->addExtension($ax);

	}
	
	if ( $error_message == "" )	 {
		if ($auth_request->shouldSendRedirect()) {
			$redirect_url = 
				$auth_request->redirectURL( $trust_root, $return_to );

				if (Auth_OpenID::isFailure($redirect_url)) {
					$error_message =
						"サーバーにリダイレクトできません:"
						. $redirect_url->message;
				}
				else {
					log_file(print_r($redirect_url,true));
					header("Location: ".$redirect_url);
				}
		}
		else {
			$form_id = 'openid_message';
			$form_html = 
				$auth_request->htmlMarkup(
					$trust_root, $return_to,
					false,
					array('id' => $form_id )
				);
		
			if (Auth_OpenID::isFailure($form_html)) {
				$error_message =
					"サーバーにリダイレクトできません(HTML):"
					. $form_html->message;
			}
			else {
				log_file(print_r($form_html,true));
				print $form_html;
			}
		
		}
	}
}

// リダイレクトされなかった場合の表示
require_once "login_view_message.php";

?>
