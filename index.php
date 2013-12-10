<?php

require_once 'core/init.php';
require_once 'core/private.php';

echo Session::flash('home');
$user = new User();
$uid = $user->data()->uid;
$display = false;
$greeting = "Hello, " . escape($user->data()->firstname) . '!';
$length = strlen($greeting);
setlocale(LC_MONETARY, 'en_US.UTF-8');
$currentBalance = money_format('%i' ,DB::getInstance()->get('users_balances_view', array('uid', '=', $uid))->first()->balance);
$dbo = DB::getInstance()->query("Select event_name, location, event_datetime, organizer 
		 from events_view AS ev where ev.eid IN (Select eid from users_events where UID = ?)", array($uid));
print_r($dbo->error());

if(!$dbo->error() && $dbo->count()){
	$display = true;
	$events = $dbo->results();
	$headers = array_keys((array) $events[0]);
} else{
	$display = false;
}
?>

<html>
<head>
<title>ACM Member Page</title>
<link rel="stylesheet" type="text/css" href="/css/userTable.css">
<link rel="stylesheet" type="text/css" href="/css/base.css">
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/pure/0.3.0/pure-min.css"></head>
<body>
<div class="main">

<pre class="cow" ">
  <?php echo delim('_', $length) . '<br>&lt; ' . $greeting . ' &gt;<br>  ' . delim('-', $length); ?> 
         \   ^__^ 
          \  (oo)\_______
             (__)\       )\/\
                 ||----w |
                 ||     ||
    
</pre>

<div class="content">
<h3>Balance</h3>
Your current balance is  <?php echo $currentBalance ?>.
</div>
<div class="content">
<h3>Vending</h3>
<?php echo VendingForm::render(); ?>
</div>
<div class="content">
<h3>Upcoming Participating Events</h3>
<?php if($display){
	$upcomingTable = new UserTable($events, $headers, 'index.php');
	echo $upcomingTable->render();
} else{
	echo 'None.';
}
	?>
</div>
</body>
</html>