<?php
abstract class Form {
	public $_specials = array(
				'event_datetime' => Selections::DateTime,
		   		'meeting_time' => Selections::Time,
		   		'meeting_day' => Selections::DropDown,
		   		'accountcreated' => Selections::DateTime,
		   		'accountexpires' => Selections::DateTime,
				'leader' => Selections::DropDown,
				'oid' => Selections::DropDown,
				'memberName' => Selections::DropDown
	), $_long_input = array(
		'description', 'password', 'salt'
	);
		   
	protected function getOptions($field) {
		$options = array ();
		if ($field == 'leader' || $field == 'memberName') {
			$dbo = DB::getInstance ()->get ( 'users', array (
					'firstname' 
			) );
			$results = $dbo->results ();
			foreach ( $results as $result ) {
				$name = $result->firstname . ' ' . $result->lastname;
				$options [$name] = $result->uid;
			}
		} else if ($field == 'oid') {
			$dbo = DB::getInstance ()->get ( 'organizers', array (
					'title' 
			) );
			$results = $dbo->results ();
			foreach ( $results as $result ) {
				$options [$result->title] = $result->oid;
			}
		} else if ($field === 'meeting_day') {
			$options = array (
					'Monday',
					'Tuesday',
					'Wednesday',
					'Thursday',
					'Friday' 
			);
		} 
		return $options;
	}
	
	public abstract function render();
}