<?php
# uncomment this once we get rolling with composer
#require_once( __DIR__."/vendor/autoload.php");

spl_autoload_register(function($class) {
	$path = str_replace('\\', '/', $class) . '.php';
	if (file_exists(__DIR__."/".$path)){
		include  __DIR__."/".$path;
	}
});
