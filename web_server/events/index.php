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
$dbo = DB::getInstance()->get($table);
$edit = false;
$error = "";

if(!$dbo->error() && $dbo->count()){
	$records = $dbo->results();
	$columns = array_keys((array) $records[0]);
} else if(!$dbo->count()){
	$column_names = $dbo->get('information_schema.columns', array('table_name' , '=', "{$table}"), array('column_name'))->results();
	$headers = array();
	$records = array(); // empty records
	foreach($column_names as $column_name){
		$columns[] = $column_name->column_name;
	}
	$headers = array_reverse($columns);
	$error = 'No records found.';
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
<link rel="stylesheet" type="text/css" href="/records.css">
<link rel="stylesheet" type="text/css" href="/table.css">
</head>
<body>
<div>
<?php 
	echo $error;
?>
</div>

<div style='float:left'>
	<form action="" method="get">
		<label for="Search">Search</label> 
		<input type="text" name="search_value" id="search_value"> 
			<select name="search_field">
				<?php echo Format::options($headers); ?>
			</select>
			<input type="submit" value="Search">
	</form>
</div>
<div>
	<form action="">
		<input type="submit" value="Clear">	
	</form>
	</div>
		
		<?php echo Format::eventView($records, $headers, 'index.php', $joinedEvents); ?>
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