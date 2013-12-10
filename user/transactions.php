<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once $base . "/core/init.php";
require_once $base . "/core/private.php";

$user = new User();
$uid = $user->data()->uid;
$name = $user->data()->firstname . ' ' . $user->data()->lastname;
$table = 'transactions';
$edit_table = 'transactions';
$primary_key = 'tid';
$error = "";
$dbo = DB::getInstance()->get('transactions', array('uid', '=' ,$uid), array(
	'tid', 'amount', 'description','time_initiated'
));

if(!$dbo->error() && $dbo->count()){
	$records = $dbo->results();
	$headers = array_keys((array) $records[0]);
} else if(!$dbo->count()){
	$column_names = $dbo->get('information_schema.columns', array('table_name' , '=', "{$table}"), array('column_name'))->results();
	$headers = array();
	$records = array(); // empty records
	foreach($column_names as $column_name){
		if($column_name != 'uid'){
			$headers[] = $column_name->column_name;
		}
	}
	$headers = array_reverse($headers);
	$error = 'No records found.';
}else {
	$error =  'Error retrieving data.';
}


?>
<html>
<head>
<title>Member Transaction History</title>
	<link rel="stylesheet" media="all" type="text/css"
	href="/css/jquery-ui-1.10.3.custom.css" />
<link rel="stylesheet" type="text/css" href="/css/userTable.css">
<link rel="stylesheet" type="text/css" href="/css/table.css">
<link rel="stylesheet" type="text/css" href="/css/base.css">
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/pure/0.3.0/pure-min.css">
</head><body>
<div class='main'>
<h3>Transaction history for <?php echo $name; ?></h3>
<div>
<?php
if($error) echo '<div class="ui-state-error ui-corner-all">
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><strong>Error:</strong> ' .$error. '</p> </div>';
?>
</div>

		<?php 
			$transactionHistory = new UserTable($records, $headers, 'index.php');
			echo ($transactionHistory->render());
		?>
		
</div></body>
</div>
</html>