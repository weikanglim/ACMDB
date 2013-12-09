<?php

require_once 'core/init.php';
require_once 'core/private.php';

echo Session::flash('home');
$user = new User();
$uid = $user->data()->uid;
$greeting = "Hello, " . escape($user->data()->firstname) . '!';
$length = strlen($greeting);
setlocale(LC_MONETARY, 'en_US.UTF-8');
$currentBalance = money_format('%i' ,DB::getInstance()->get('users_balances_view', array('uid', '=', $uid))->first()->balance);
$dbo = DB::getInstance()->query("Select * from events_view AS ev where ev.eid IN (Select eid from users_events where UID = ?)", array($uid));


if(!$dbo->error() && $dbo->count()){
	$events = $dbo->results();
	$headers = array_keys((array) $events[0]);
} else if(!$dbo->count()){
	$column_names = $dbo->get('information_schema.columns', array('table_name' , '=', "users_events"), array('column_name'))->results();
	$headers = array();
	$events = array(); // empty records
	foreach($column_names as $column_name){
		$headers[] = $column_name->column_name;
	}
	$headers = array_reverse($headers);
}

unset($headers['0']);
?>

<html>
<head>
<title>ACM Member Page</title>
<link rel="stylesheet" type="text/css" href="/css/userTable.css">
<link rel="stylesheet" type="text/css" href="/css/base.css">
<body>
<pre class="cow">
  <?php echo delim('_', $length) . '<br>&lt; ' . $greeting . ' &gt;<br>  ' . delim('-', $length); ?> 
         \   ^__^ 
          \  (oo)\_______
             (__)\       )\/\
                 ||----w |
                 ||     ||
    
</pre>
<div class="balance">
<h3>Balance</h3>
Your current balance is  <?php echo $currentBalance ?>.
</div>
<div>
<h3>Vending</h3>
<?php echo VendingForm::render(); ?>
</div>
<div style="width:50%">
<h3>Upcoming Participating Events</h3>
<?php $upcomingTable = new UserTable($events, $headers, 'index.php');
	  echo $upcomingTable->render();?>
</div>

</body>
</html>