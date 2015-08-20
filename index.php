<?php
date_default_timezone_set('UTC');

function __autoload($className) {
	include $className . '.php';
}

$server = new Server();
$server->process();
