<?php

require 'config.php';
require 'core.php';

# http://api.mail.ru/docs/guides/sites/
# > Технически, API для сайтов представляет механизм для
# > получения необходимых данных (сессии) для осуществления
# > вызовов API приложений моего мира. То есть сначала надо
# > получить сессию (это делается ***только*** через JavaScript
# > библиотеку), а потом можно обращаться к API приложений
# > любыми способами, как это делают приложения (используя
# > JS, Perl, PHP или что-то еще).
#
# У mail.ru ***есть*** OAuth авторизация:
# http://api.mail.ru/docs/guides/oauth/sites/

# http://api.mail.ru/docs/guides/sites/
# > После того, как пользователь залогинился, на вашем домене
# > проставляется кука mrc, в которой есть все данные для
# > использования API с вашего сервера. Пример разбора куки на PHP:
# >
# >	parse_str(urldecode($_COOKIE['mrc']));
# >
# > Пример результата:
# >
# >	Array(
# >		[app_id] => 459441
# >		[exp] => 1270806364
# >		[ext_perm] =>
# >		[is_app_user] => 1
# >		[oid] => 1324730981306483817
# >		[session_key] => 0b693783ca5ad2023d9aac4fa535a9a4
# >		[ss] => d41d8cd98f00b204e9800998ecf8427e
# >		[state] =>
# >		[vid] => 1324730981306483817
# >		[sig] => 5069d4cc484d229b5a56ce39ed92037a
# >	)
# >
# > Содержимое mrc идентично содержанию объекта mailru.session.

header('Content-Type: text/plain; charset=utf-8');

echo '$_COOKIE[\'mrc\']', PHP_EOL;
parse_str($_COOKIE['mrc'], $mrc);
var_dump($mrc);
echo PHP_EOL;

# mailru.session
# http://api.mail.ru/docs/guides/jsapi/#mailru.session
# > подпись запроса, настоятельно рекомендуем ее
# > проверять на сервере при использовании данных
# > для авторизации

$param = $mrc;
unset($param['sig']);
assert(sign_server_server($param, $site_secret) === $mrc['sig']);

# $_COOKIE['mrc']
# array(10) {
#   ["app_id"]=>
#   string(6) "697633"
#   ["exp"]=>
#   string(10) "1359252068"
#   ["ext_perm"]=>
#   string(13) "notifications"
#   ["is_app_user"]=>
#   string(1) "1"
#   ["oid"]=>
#   string(18) "101259747216435918"
#   ["session_key"]=>
#   string(32) "a44e61e9cc6570195fb10f21f555a589"
#   ["ss"]=>
#   string(32) "d02f3b16ada591b91ee90b8aaa13df7a"
#   ["state"]=>
#   string(0) ""
#   ["vid"]=>
#   string(18) "101259747216435918"
#   ["sig"]=>
#   string(32) "0031c3b5adcc214090c4d01ee4a45502"
# }

# Как использовать API
# http://api.mail.ru/docs/guides/restapi/
# > Все вызовы методов API -- это GET или POST HTTP-запросы к URL
# > http://www.appsmail.ru/platform/api с некоторым набором параметров. 

# Параметры запроса
# http://api.mail.ru/docs/guides/restapi/#params
# > В каждом запросе должен присутствовать набор обязательных
# > параметров. Также для каждой функции в ее документации определены
# > дополнительные параметры, нужные только для этой функции. Текстовые
# > значения параметров должны быть преданы в кодировке UTF-8.
# > Одинаковые для всех функций параметры перечислены ниже.
# >
# > Имя          Тип     Описание
# > method       string  название вызываемого метода, например, users.getInfo; обязательный параметр
# > app_id       int     идентификатор приложения; обязательный параметр
# > sig          string  подпись запроса; обязательный параметр
# > session_key  string  сессия текущего пользователя
# > uid          uint64  идентификатор пользователя, для которого вызывается метод; данный аргумент должен быть указан, если не указан session_key
# > secure       bool    флаг, обозначающий, что запрос идет по защищенной схеме «сервер-сервер»; возможные значения: 1 или 0; по-умолчанию 0
# > format       string  формат выдачи ответа API; возможные значения: xml или json; по-умолчанию json
# >
# > Порядок следования параметров в запросе значения не имеет, порядок
# > параметров важен только при расчете подписи.

# http://api.mail.ru/docs/reference/rest/users.getInfo/

$param = array();
$param['app_id'] = $site_id;
$param['secure'] = '1';
$param['session_key'] = $mrc['session_key'];
$param['method'] = 'users.getInfo';
$param['sig'] = sign_server_server($param, $site_secret);

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'http://www.appsmail.ru/platform/api?'.http_build_query($param));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
$error = curl_error($curl);
$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

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
# string(71) "{"error":{"error_msg":"parameter 'sig' is necessary","error_code":100}}"

# $error
# string(0) ""
#
# $code
# int(200)
#
# $response
# string(1109) "[{"pic_50":"http://avt.appsmail.ru/mail/vladimir.barbarosh/_avatar50","friends_count":0,"pic_22":"http://avt.appsmail.ru/mail/vladimir.barbarosh/_avatar22","nick":"Владимир Барбэрош","is_verified":1,"is_online":0,"pic_big":"http://avt.appsmail.ru/mail/vladimir.barbarosh/_avatarbig","last_name":"Барбэрош","has_pic":1,"email":"vladimir.barbarosh@mail.ru","pic_190":"http://avt.appsmail.ru/mail/vladimir.barbarosh/_avatar190","referer_id":"","vip":0,"pic_32":"http://avt.appsmail.ru/mail/vladimir.barbarosh/_avatar32","birthday":"07.04.1985","referer_type":"","link":"http://my.mail.ru/mail/vladimir.barbarosh/","last_visit":"1359247250","uid":"101259747216435918","app_installed":1,"status_text":"","pic_128":"http://avt.appsmail.ru/mail/vladimir.barbarosh/_avatar128","sex":0,"pic":"http://avt.appsmail.ru/mail/vladimir.barbarosh/_avatar","pic_small":"http://avt.appsmail.ru/mail/vladimir.barbarosh/_avatarsmall","pic_180":"http://avt.appsmail.ru/mail/vladimir.barbarosh/_avatar180","pic_40":"http://avt.appsmail.ru/mail/vladimir.barbarosh/_avatar40","first_name":"Владимир"}]"
#
#	[{
#	"uid": "101259747216435918",
#	"email": "vladimir.barbarosh@mail.ru",
#	"first_name": "Владимир"
#	"last_name": "Барбэрош",
#	"nick": "Владимир Барбэрош",
#	"birthday": "07.04.1985",
#	"app_installed": 1,
#	"friends_count": 0,
#	"has_pic": 1,
#	"link": "http://my.mail.ru/mail/vladimir.barbarosh/",
#	"pic": "http://avt.appsmail.ru/mail/vladimir.barbarosh/_avatar",
#	"pic_22": "http://avt.appsmail.ru/mail/vladimir.barbarosh/_avatar22",
#	"pic_32": "http://avt.appsmail.ru/mail/vladimir.barbarosh/_avatar32",
#	"pic_40": "http://avt.appsmail.ru/mail/vladimir.barbarosh/_avatar40",
#	"pic_50": "http://avt.appsmail.ru/mail/vladimir.barbarosh/_avatar50",
#	"pic_128": "http://avt.appsmail.ru/mail/vladimir.barbarosh/_avatar128",
#	"pic_180": "http://avt.appsmail.ru/mail/vladimir.barbarosh/_avatar180",
#	"pic_190": "http://avt.appsmail.ru/mail/vladimir.barbarosh/_avatar190",
#	"pic_big": "http://avt.appsmail.ru/mail/vladimir.barbarosh/_avatarbig",
#	"pic_small": "http://avt.appsmail.ru/mail/vladimir.barbarosh/_avatarsmall",
#	"is_online": 0,
#	"is_verified": 1,
#	"last_visit": "1359247250",
#	"referer_id": "",
#	"referer_type": "",
#	"sex": 0,
#	"status_text": "",
#	"vip": 0,
#	}]
