<?php

require 'config.php';

# http://api.mail.ru/docs/guides/oauth/sites/
# > Если вы указали response_type=code
# > В этом случае результат авторизации выозвращается в виде
# > GET-параметров, чтобы вы сразу имели к ним доступ с сервера,
# > например, так:
# >
# >	http://example.com/oauth/receiver?code=cfb65617ee147446cb17fba30b2fdc5e
# >
# > Обменяйте полученный авторизационный код на идентификатор сессии,
# > который вы сможете использовать для доступа к REST API. Для этого
# > с сервера сделайте следующий POST-вызов на адрес
# >
# >	https://connect.mail.ru/oauth/token
# >
# >		POST /oauth/token HTTP/1.1
# >		Host: connect.mail.ru
# >		Accept: */*
# >		Content-Length: 186
# >		Content-Type: application/x-www-form-urlencoded
# >
# >		client_id=464119&
# >		client_secret=ac7fd2cc742c70a707cad3f6b2ca1c89&
# >		grant_type=authorization_code&
# >		code=000ff8627d2d79b60ebdaf004f9a68aa&
# >		redirect_uri=http://example.com/oauth/receiver

$param = array();
$param['client_id'] = $site_id;
$param['client_secret'] = $site_secret;
$param['grant_type'] = 'authorization_code';
$param['code'] = $_GET['code'];
$param['redirect_uri'] = $redirect_url;

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://connect.mail.ru/oauth/token');
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
$error = curl_error($curl);
$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

header('Content-Type: text/plain; charset=utf-8');

echo '$error', PHP_EOL;
var_dump($error);
echo PHP_EOL;

echo '$code', PHP_EOL;
var_dump($code);
echo PHP_EOL;

echo '$response', PHP_EOL;
var_dump($response);
echo PHP_EOL;

# $error
# string(0) ""
#
# $code
# int(400)
#
# $response
# string(25) "{"error":"invalid_grant"}"

# $error
# string(0) ""
#
# $code
# int(200)
#
# $response
# string(179) "{"refresh_token":"553b784556c0fc904adb10135481370e","expires_in":86400,"access_token":"558b965801b55b24788850aeafc33515","token_type":"bearer","x_mailru_vid":"101259747216435918"}"
#
#	{
#	"refresh_token": "553b784556c0fc904adb10135481370e",
#	"expires_in": 86400,
#	"access_token": "558b965801b55b24788850aeafc33515",
#	"token_type": "bearer",
#	"x_mailru_vid": "101259747216435918"
#	}
