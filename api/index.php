<?php
$_SERVER['APP_DEBUG'] = 'true';
$_ENV['APP_DEBUG'] = 'true';

$_SERVER['APP_ENV'] = 'local';
$_ENV['APP_ENV'] = 'local';

$_SERVER['VIEW_COMPILED_PATH'] = '/tmp';
$_ENV['VIEW_COMPILED_PATH'] = '/tmp';

$_SERVER['SESSION_DRIVER'] = 'cookie';
$_ENV['SESSION_DRIVER'] = 'cookie';

$_SERVER['CACHE_STORE'] = 'array';
$_ENV['CACHE_STORE'] = 'array';

$_SERVER['SCRIPT_NAME'] = '/index.php';

require __DIR__ . '/../public/index.php';