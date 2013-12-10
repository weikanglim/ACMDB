<?php
$user = new User();
if($user->isLeader()){
}else {
		Redirect::to(403);
}
