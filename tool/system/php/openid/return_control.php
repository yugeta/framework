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
// 結果チェック
// *********************************************************
$error_message = "";
$response = $consumer->complete($return_to);
log_file(print_r($response,true));

if ($response->status == Auth_OpenID_CANCEL) {
	$error_message = 'キャンセルされました';
}
if ($response->status == Auth_OpenID_FAILURE) {
	$error_message = $response->message;
}
if ($response->status == Auth_OpenID_SUCCESS) {
	$start_message = "ログインされました";
	$_SESSION['id'] = $response->getDisplayIdentifier();

	$sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($response);
	log_file(print_r($sreg_resp,true));

	$sreg = $sreg_resp->contents();
	log_file(print_r($sreg,true));

	if (@$sreg['nickname']) {
		$_SESSION['nickname'] = 
			mb_convert_encoding( $sreg['nickname'], "SHIFT_JIS", "UTF-8" );
	}

	$ax_resp = Auth_OpenID_AX_FetchResponse::fromSuccessResponse($response);
	if ( $ax_resp ) {
		$ax_data = $ax_resp->getExtensionArgs();
		log_file(print_r($ax_data,true));

		foreach($ax_data as $ax_key => $ax_value){
			// Google 用
			if ( $ax_value == 'http://axschema.org/contact/email' ) {
				$target_key = str_replace( "type.ext", "", $ax_key );
				$target_key = "value.ext" . $target_key . ".1";
				$_SESSION['email'] = $ax_data[$target_key];
			}
			// Yahoo 用
			if ( $ax_value == 'http://axschema.org/namePerson/friendly' ) {
				$target_key = str_replace( "type.ext", "", $ax_key );
				$target_key = "value.ext" . $target_key . ".1";
				$_SESSION['dispname'] = mb_convert_encoding( $ax_data[$target_key], "SHIFT_JIS", "UTF-8" );
			}

		}
	}

	log_file(print_r($_SESSION,true));

}

require_once "return_view.php";

?>
