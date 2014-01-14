<?php

require 'config.php';

# http://api.mail.ru/docs/guides/oauth/sites/
# > В нужный момент, сделайте редирект на страницу авторизации.
# > Например, вы можете сделать кнопку "Войти с Mail.Ru" на
# > страницах регистрации или логина, по нажатию на которую будет
# > происходить перенаправление. Адрес страницы авторизации:
# >
# >	https://connect.mail.ru/oauth/authorize?
# >		client_id=идентификатор вашего сайта&
# >		response_type=token&
# >		redirect_uri=адрес принимающей страницы на вашем сайте
# >
# > Используйте необязательный параметр scope чтобы запросить у
# > пользователя требующиеся вашему сайту привилегии. Если требуется
# > запросить несколько привилегий, они передаются в scope через пробел.

$param = array();
$param['client_id'] = $site_id;
$param['response_type'] = 'code';
$param['redirect_uri'] = $redirect_url;

header('Location: https://connect.mail.ru/oauth/authorize?'.http_build_query($param));

# http://test.themefuse.com/barbarosh/mailru/redirect.php?code=d089215f2b5709a1fc85243d2f1d64e9
# http://test.themefuse.com/barbarosh/mailru/redirect.php?error=access_denied
