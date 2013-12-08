<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once 'core/init.php';
require_once $base . "/core/public.php";

$user = new User();
if($user->isLoggedIn()){
	$user->logout();
	echo 'You have been successfully logged out.';
	echo '<br> Click <a href="login.php">here</a> to login.';
} else{
	Redirect::to('index.php');
}

?>
<link rel="stylesheet" type="text/css" href="/css/base.css">
