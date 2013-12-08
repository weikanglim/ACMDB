<?php
$user = new User();
if($user->isLoggedIn()){
	echo pageHeader('private');
	if($user->isAdmin()){
		echo pageHeader('admin');}
	if($user->isModerator()){
		echo pageHeader('moderator');
	}
} else {
	Redirect::to("/login.php");
}