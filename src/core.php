<?php

# http://api.mail.ru/docs/guides/restapi/#sig
# http://api.mail.ru/docs/guides/restapi/#client
function sign_client_server($uid, $param, $private_key)
{
	ksort($param);
	$a = '';
	foreach ($param as $k => $v) {
		$a .= "$k=$v";
	}
	return md5($uid.$a.$private_key);
}

# http://api.mail.ru/docs/guides/restapi/#server
function sign_server_server($param, $secret_key)
{
	ksort($param);
	$a = '';
	foreach ($param as $k => $v) {
		$a .= "$k=$v";
	}
	return md5($a.$secret_key);
}

/*
call_user_func(function () {
	# Клиент-сервер
	# http://api.mail.ru/docs/guides/restapi/#client
	# > Пусть uid=1324730981306483817 и private_key=7815696ecbf1c96e6894b779456d330e
	# >
	# > Запрос, который вы хотите выполнить:
	# >
	# >	http://www.appsmail.ru/platform/api?method=friends.get&app_id=423004&session_key=be6ef89965d58e56dec21acb9b62bdaa
	# >
	# > Тогда:
	# >
	# >	params = app_id=423004method=friends.getsession_key=be6ef89965d58e56dec21acb9b62bdaa
	# >	sig = md5(1324730981306483817app_id=423004method=friends.getsession_key=be6ef89965d58e56dec21acb9b62bdaa7815696ecbf1c96e6894b779456d330e)
	# >	    = 5073f15c6d5b6ab2fde23ac43332b002
	# >
	# > Итоговый запрос:
	# >
	# >	http://www.appsmail.ru/platform/api?method=friends.get&app_id=423004&session_key=be6ef89965d58e56dec21acb9b62bdaa&sig=5073f15c6d5b6ab2fde23ac43332b002
	# >
	$param = array();
	$param['app_id'] = '423004';
	$param['method'] = 'friends.get';
	$param['session_key'] = 'be6ef89965d58e56dec21acb9b62bdaa';
	$sig = sign_client_server('1324730981306483817', $param, '7815696ecbf1c96e6894b779456d330e');
	assert($sig === '5073f15c6d5b6ab2fde23ac43332b002');
});

call_user_func(function () {
	# Сервер-сервер
	# http://api.mail.ru/docs/guides/restapi/#server
	# > Пусть uid=1324730981306483817 и secret_key=3dad9cbf9baaa0360c0f2ba372d25716
	# >
	# > Запрос, который вы хотите выполнить:
	# >
	# >	http://www.appsmail.ru/platform/api?method=friends.get&app_id=423004&session_key=be6ef89965d58e56dec21acb9b62bdaa&secure=1
	# >
	# > Тогда:
	# >
	# >	params = app_id=423004method=friends.getsecure=1session_key=be6ef89965d58e56dec21acb9b62bdaa
	# >	sig = md5(app_id=423004method=friends.getsecure=1session_key=be6ef89965d58e56dec21acb9b62bdaa3dad9cbf9baaa0360c0f2ba372d25716)
	# >	    = 4a05af66f80da18b308fa7e536912bae
	# >
	# > Итоговый запрос:
	# >
	# >	http://www.appsmail.ru/platform/api?method=friends.get&app_id=423004&session_key=be6ef89965d58e56dec21acb9b62bdaa&secure=1&sig=4a05af66f80da18b308fa7e536912bae
	$param = array();
	$param['app_id'] = '423004';
	$param['method'] = 'friends.get';
	$param['secure'] = '1';
	$param['session_key'] = 'be6ef89965d58e56dec21acb9b62bdaa';
	$sig = sign_server_server($param, '3dad9cbf9baaa0360c0f2ba372d25716');
	assert($sig === '4a05af66f80da18b308fa7e536912bae');
});
*/
