<?php

header('Content-Type: text/plain; charset=utf-8');

echo '$_GET', PHP_EOL;
var_dump($_GET);
echo PHP_EOL;

echo '$_POST', PHP_EOL;
var_dump($_POST);
echo PHP_EOL;

echo '$_COOKIE', PHP_EOL;
var_dump($_COOKIE);
echo PHP_EOL;

echo '$_SERVER', PHP_EOL;
var_dump($_SERVER);
echo PHP_EOL;
