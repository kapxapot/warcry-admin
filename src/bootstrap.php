<?php

function debugModeOn() {
	global $debug;
	
	if ($debug !== true) {
		error_reporting(E_ALL & ~E_NOTICE);
		ini_set("display_errors", 1);
		
		$debug = true;
	}
}

if (isset($_GET['debug'])) {
	debugModeOn();
}

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new \Dotenv\Dotenv(__DIR__ . '/..');
$dotenv->load();

session_start();

$src = __DIR__ . '/';

$settings = require $src . 'settings.php';

if ($settings['debug']) {
	debugModeOn();
}

$app = new \Slim\App($settings);
$container = $app->getContainer();

require $src . 'dependencies.php';
require $src . 'middleware.php';
require $src . 'routes.php';

// temp
$lib = __DIR__ . $container->get('settings')['folders']['lib'];

// потому что все в одном файле и надо конкретно переписывать все, чтобы нормально было по классам
// возможно, лучше использовать другую, более современную библиотеку
require $lib . 'feed-creator.php';

$app->run();
