**************************************************
 1) 
**************************************************

http://www.janrain.com/openid-enabled

��L�� URL ���烉�C�u�������_�E�����[�h���āAopenid2/Auth/OpenID.php ��
�Ȃ�悤�ɔz�u���܂��B


[openid_sample]
    common.php
    ���̑��̃t�@�C��

[openid2]
    [Auth]
        [OpenID]
        [Yadis]
        OpenID.php


**************************************************
 2)
**************************************************

Auth/Yadis/ParanoidHTTPFetcher.php ���ȉ��̂悤�ɕύX���܂�
( 2�ӏ�����Acurl_exec($c); �̒��O�Ɉȉ����L�q )

if ($this->isHTTPS($url)) {
    curl_setopt($c, CURLOPT_CAINFO, $GLOBALS['openid_pem']);
}

**************************************************
 3) 
**************************************************

OpenID.php �̃��O�������ȉ��̂悤�ɏ��������܂�

    static function log($format_string)
    {
        $args = func_get_args();
        $message = call_user_func_array('sprintf', $args);
//      error_log($message);
        if ( $GLOBALS['logfile'] != "" ) {
            error_log("$message\n", 3, $GLOBALS['logfile']);
        }
    }

**************************************************
 4)
**************************************************

http://curl.haxx.se/docs/caextract.html ����Acacert.pem ���_�E�����[�h
���āAopenid_sample �t�H���_���ɒu���܂��B


**************************************************
 5) windows �̂�
**************************************************

windows �ł́Aphp.ini �� extension=php_curl.dll ��
extension=php_openssl.dll ��L���ɂ��܂�



�����쌠

���̃v���O�����̓t���[�ł��B�ǂ������R�Ɍ�g�p���������B
���쌠�͍�҂ł��鎄(lightbox)���ۗL���Ă��܂��B
�܂��A�{�\�t�g���^�p�������ʂɂ��ẮA��҂͈�ؐӔC��
��������̂ł��������������B
