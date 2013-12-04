<?php
function pageHeader($type){
	$return = "<table><tr>";
	$indexes = Config::get($type);
	foreach($indexes as $index =>$page){
		$index = ucfirst($index);
		$return .= "<td>";
		$return .= Format::link($index, $page);
		$return .= "</td>";
	}
	$return .= "</table></tr>";
	return $return;
}

function nice($words){
	switch($words){
		case 'firstname' : $words = 'First Name'; break;
		case 'lastname' : $words = 'Last Name'; break;
		case 'accountcreated': $words = 'Account Created'; break;
		case 'accountexpires': $words = 'Account Expires'; break;
		default: if(strlen($words) == 3 && strpos($words, 'id')) $words = strtoupper($words); break;
	}
	
	return ucwords(str_replace('_', ' ', $words));
}