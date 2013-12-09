<?php
$user = new User();
if($user->isLoggedIn()){
	echo pageHeader('private');
	if($user->isAdmin()){
		echo pageHeader('admin');}
	if($user->isLeader()){
		echo pageHeader('leader');
	}
} else {
	Redirect::to("/login.php");
}