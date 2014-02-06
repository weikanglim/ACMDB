<?php
$user = new User();
if($user->isAdmin()){
}else {
		Redirect::to(403);
}