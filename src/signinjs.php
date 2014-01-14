<?php require 'config.php' ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
	"http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
	<title>Title of the Page</title>
	<script type="text/javascript" src="http://cdn.connect.mail.ru/js/loader.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
</head>
<body>

<h2><a href="http://api.mail.ru/docs/guides/jsapi/">Пример кода сайта</a></h2>
<pre>
&lt;html&gt;
  &lt;head&gt;
    &lt;script type="text/javascript" src="http://cdn.connect.mail.ru/js/loader.js"&gt;
    &lt;/script&gt;
  &lt;/head&gt;
  &lt;body&gt;
    &lt;script type="text/javascript"&gt;
       mailru.loader.require('api', function() {
           mailru.connect.init('app_id из настроек сайта',
                               'ваш приватный ключ из настроек сайта');
           // все готово, здесь можно работать с функциями API
       });
    &lt;/script&gt;
  &lt;/body&gt;
&lt;/html&gt;
</pre>

<?php

# http://api.mail.ru/docs/guides/sites/
#
# > После того, как пользователь залогинился, на вашем домене
# > проставляется кука mrc, в которой есть все данные для
# > использования API с вашего сервера. Пример разбора куки на PHP:
# >
# >	parse_str(urldecode($_COOKIE['mrc']));
# >
# > Пример результата:
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

echo '<h2>$_COOKIE[\'mrc\']</h2>';
echo '<pre>';
if (isset($_COOKIE['mrc'])) {
	parse_str($_COOKIE['mrc'], $a);
	var_dump($a);
	echo htmlspecialchars(ob_get_clean());
}
echo '</pre>';

?>

<!-- http://api.mail.ru/docs/guides/sites/ -->
<!-- http://api.mail.ru/docs/guides/jsapi/ -->
<script type="text/javascript">
// этот вызов обязателен, он осуществляет непосредственную загрузку
// кода библиотеки; рекомендуем всю работу с API вести внутри callback'а
mailru.loader.require('api', function() {

	// инициализируем внутренние переменные
	// не забудьте поменять на ваши значения app_id и private_key
	mailru.connect.init(<?php echo json_encode($site_id) ?>, <?php echo json_encode($site_key) ?>);

	// регистрируем обработчики событий,
	// которые будут вызываться при логине и логауте
	mailru.events.listen(mailru.connect.events.login, function(session) {
		window.location.reload();
	});
	mailru.events.listen(mailru.connect.events.logout, function() {
		window.location.reload();
	});

	// проверка статуса логина, в result callback'a приходит
	// вся информация о сессии (см. следующий раздел)
	mailru.connect.getLoginStatus(function(result) {

		if (result.is_app_user != 1) {

			// пользователь не залогинен, надо показать ему
			// кнопку логина вешаем кнопку логина (пример
			// для jquery)
			$('<a class="mrc__connectButton">вход@mail.ru</a>').appendTo('body');

			// эта функция превращает только что вставленный
			// элемент в стандартную кнопку Mail.Ru
			mailru.connect.initButton();
		}
		else {
			// все ок, можно работать
			// получаем полную информацию о текущем пользователе
			mailru.common.users.getInfo(function(result) {
				console.log(result)
			});
		}
	});
});
</script>

</body>
</html>
