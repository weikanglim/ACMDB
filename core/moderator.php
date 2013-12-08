<?php
$user = new User();
if($user->isLoggedIn()){
	if($user->isModerator()){
		echo pageHeader('private');
		echo pageHeader('moderator');
	} else {
		Redirect::to(403);
		
	}
} else {
	Redirect::to('/login.php');
}