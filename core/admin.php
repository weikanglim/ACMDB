<?php
$user = new User();
if($user->isLoggedIn()){
	if($user->isAdmin()){
		echo pageHeader('private');
		echo pageHeader('admin');
	} else {
		Redirect::to(403);
		
	}
} else {
	Redirect::to('/login.php');
}