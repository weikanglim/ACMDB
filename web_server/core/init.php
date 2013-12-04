<?php
session_start();
$base = $_SERVER['DOCUMENT_ROOT'];

$GLOBALS['config'] = array(
	'postgresql' => array(
		'host' => '127.0.0.1',
		'username' => 'wk',
		'password' => 'EMC^2_qVB',
		'db' => 'ACMDB'
	),
	'remember' => array(
		'cookie_name' => 'hash',
		'cookie_expiry' => 604800,
	),
	'session' => array(
		'session_name' => 'user',
		'token_name' => 'token'
	),
	'public' => array(
		'login' => '/login.php',
		'register' => '/register.php'

	),
	'private' => array(
			'home' => '/index.php',
			'profile' => '/profile.php',
			'SIG Groups' => "/siggroups/index.php",
			'events' => "/events/index.php",
			'logout' => '/logout.php',
	),
	'admin' => array(
			'users' => '/admin/users/index.php',
			'events' => '/admin/events/index.php',
			'SIG Groups' => '/admin/siggroups/index.php',
			'companies' => '/admin/companies/index.php'
	),
	'moderator' => array(
			'My Sig Group' => '/moderator/siggroups/index.php'
	)
);


spl_autoload_register(function($class) {
	require_once $_SERVER['DOCUMENT_ROOT'] . "/classes/" . $class . '.php';
});

require_once $base . "/functions/sanitize.php";
require_once $base . "/functions/header.php";

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
	$hash = Cookie::get(Config::get('remember/cookie_name'));
	$hashCheck = DB::getInstance()->get('users_sessions', array('hash', '=', $hash));
	
	if($hashCheck->count()){
		Session::put(Config::get('session/session_name'), $hashCheck->first()->uid);
		$user = new User($hashCheck->first()->uid);
		$user->login();
	}
}
