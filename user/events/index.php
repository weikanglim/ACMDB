<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once $base . "/core/init.php";
require_once $base . "/core/private.php";

echo Session::flash ('participate');

$user = new User();
$uid = $user->data()->uid;
$get = DB::getInstance()->get('users_events', array('uid', '=', "$uid"));
$results = $get->results();
$joinedEvents = array();
foreach($results as $result){
	$joinedEvents[] = $result->eid;
}

$table = 'events_view'; 
$primary_key = 'eid';
$dbo = DB::getInstance()->get($table, array('event_datetime', '>', 'NOW'));
$edit = false;
$error = "";

if(!$dbo->error() && $dbo->count()){
	$records = $dbo->results();
	$headers = array_keys((array) $records[0]);
} else if(!$dbo->count()){
	$column_names = $dbo->get('information_schema.columns', array('table_name' , '=', "{$table}"), array('column_name'))->results();
	$headers = array();
	$records = array(); // empty records
	foreach($column_names as $column_name){
		$columns[] = $column_name->column_name;
	}
	$headers = array_reverse($columns);
	$error = 'No upcoming events.';
}else {
	$error =  'Error retrieving data.';	
}

if(Input::exists('get')){
	if(Input::get('search_field') && Input::get('search_value')){
		$search_field = Input::get('search_field');
		$search_value = Input::get('search_value');
		$search = $dbo->get ( $table, array (
				"{$search_field}",
				'=',
				"{$search_value}" 
		) );
		if (! $search->error ()) {
			$records = $search->results ();
			if (! $search->count ()) {
				$error =  'No results.';
			}
		} else {
			$error =  "\"$search_value\" is not a valid type for $search_field.";
		}
	}
	
	if(Input::get('order')){
		$order = $dbo->order( $table, Input::get('order'));
		$records = $order->results();
	}
} 

if(Input::exists('post')){
	if(Input::get('ptoken')){
		if(Token::check(Input::get('ptoken'), 'participate_token')){
			if(Input::get('join')){
				if(DB::getInstance()->insert('users_events', array( 'uid' => $uid, 'eid' => Input::get('join')))){
					Session::flash('participate', 'You have RSVP\'d.');
					Redirect::to(Config::get('private/events'));
				}
			} else if(Input::get('leave')){
				if(DB::getInstance()->query("DELETE FROM USERS_EVENTS WHERE UID = ? AND EID = ?", array($uid, Input::get('leave')))){
					Session::flash('participate', 'You have cancelled your RSVP.');
					Redirect::to(Config::get('private/events'));
				}
			}
		}
	}
}

?>

<html>
<head>
<title>SIG Groups</title>
<link rel="stylesheet" type="text/css" href="/css/userTable.css">
<link rel="stylesheet" type="text/css" href="/css/table.css">
<link rel="stylesheet" type="text/css" href="/css/base.css">
</head><body>
<h2>Upcoming Events</h2>
<div>
<?php 
	echo $error;
?>
</div>
		<?php $eventTable = new EventsTable($records, $headers, 'index.php', $joinedEvents); 
			  echo $eventTable->render(); ?>
</body>
<script>
function confirmJoin()
{
return confirm("Are you sure you want to RSVP?");
}

function confirmLeave()
{
return confirm("Are you sure you want to cancel your RSVP?");	
}
</script>
</html>