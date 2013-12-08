<?php
class Format {
	public static function options($options = array()) {
		$output = "";
		foreach ( $options as $option ) {
			$output .= "<option value =\"{$option}\"> {$option}</option>";
		}
		return $output;
	}
	function nice($words) {
		switch ($words) {
			case 'firstname' :
				$words = 'First Name';
				break;
			case 'lastname' :
				$words = 'Last Name';
				break;
			case 'accountcreated' :
				$words = 'Account Created';
				break;
			case 'accountexpires' :
				$words = 'Account Expires';
				break;
			case 'userlevel' :
				$words = 'User Level';
				break;
			case 'oid' :
				$words = 'Organizer';
				break;
			default :
				if (strlen ( $words ) == 3 && strpos ( $words, 'id' ))
					$words = strtoupper ( $words );
				break;
		}
		
		return ucwords ( str_replace ( '_', ' ', $words ) );
	}
}