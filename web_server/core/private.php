<?php
$user = new User();
if($user->isLoggedIn()){
	echo pageHeader('private');
	if($user->isAdmin()){
		echo pageHeader('admin');}
} else {
	Redirect::to("/login.php");
}