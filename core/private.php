<?php
$user = new User();
if($user->isLoggedIn()){	
	if($user->isAdmin() || $user->isLeader()){
		if(!Session::exists('navigation')){
			Session::put('navigation', 'user');
		}
		
		// User changes navigation
		if(Input::get('navigation')){
			$nav = Input::get('navigation');
			if(Session::get('navigation') !== $nav){
				Session::delete('navigation');
				Session::put('navigation', $nav);
			}
		}
				
		$default = Session::get('navigation');
		
		if($user->isAdmin() && $user->isLeader()){
			$selections = array('User', 'Leader', 'Admin');
		} else if ($user->isAdmin()){
			$selections = array('User', 'Leader');
		}
		else if($user->isLeader()){
			$selections = array('User', 'Leader');
		}
		
		$dropDownForm = "<div style='position:absolute;right=0px;'><form action='' method='post'>" .
				Selections::dropdownOnChange('navigation', $selections, $default) . "</form></div>";
		echo $dropDownForm;
		
		switch(strtolower(Session::get('navigation'))){
			case 'user': echo pageHeader('private'); break;
			case 'leader' : echo pageHeader('leader'); break;
			case 'admin': echo pageHeader('admin'); break;
		}
		
	}else {
		echo pageHeader('private');
	}
} else {
	Redirect::to('/login.php');
}