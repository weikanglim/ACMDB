<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once $base . "/core/init.php";
require_once $base . "/core/admin.php";
require_once $base . "/core/private.php";


$table = 'users_view';
$edit_table = 'users';
$primary_key = 'uid';
$dbo = DB::getInstance()->get($table);
$edit = false;
$error = "";
$validation = array (
					'username' => array (
							'required' => true,
							'min' => 4,
							'max' => 20,
							'unique' => array($table, 'onUpdate')
					),
					'firstname' => array (
							'required' => true
					),
					'lastname' => array (
							'required' => true
					),
					'email' => array (
							'required' => true,
							'email' => true
					),
					'phone' => array (
							'phone' => true,
					)
			) ;

$control = new AdminTableController($dbo, $table, $edit_table, $primary_key, $validation);
$control->runController();


?>

<html>
<head>
<title>Users</title>
	<link rel="stylesheet" media="all" type="text/css"
	href="/css/jquery-ui-1.10.3.custom.css" />
<link rel="stylesheet" type="text/css" href="/css/table.css">
<link rel="stylesheet" type="text/css" href="/css/records.css">
<link rel="stylesheet" type="text/css" href="/css/base.css"><link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/pure/0.3.0/pure-min.css">
<link type="text/css" rel="stylesheet" href="/css/jquery.qtip.css" />
</head><body>
<?php if(!$control->edit()){
	echo "<div class='admin'>";
} else {
	echo "<div class='long-record'>";
}?>
<div>
<?php 
echo Session::flash ('editSuccess');
echo Session::flash ('addSuccess');

if($control->edit()){
	$user = DB::getInstance()->get('users_view', array('uid', '=', Input::get('edit')))->first();
	echo "<h3>User Information for $user->name</h3>";
} else{
	echo "<h3>Users</h3>";
}
	echo $control->error();
?>
</div>
		<?php 
		if(!$control->edit()){
			$usersTable = new Table($control->records(), $control->headers(), 'index.php');
			echo $usersTable->render();			
		}else{
			$userEdit = new EditForm($control->record(), $control->primary_key());
			echo $userEdit->render(false);
		}
		?>

			<!--  loading jQuery -->
	<script type="text/javascript"
		src="/jquery-1.10.2.min.js"></script>
	<script type="text/javascript"
		src="/jquery-ui-1.10.3.custom.js"></script>
	<script type="text/javascript" src="/jquery.qtip.js"></script>
	<script type="text/javascript" src="/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="/jquery-ui-sliderAccess.js"></script>
	<script>
			$(function()
			{
				$('#accountexpires').datetimepicker();
				$('#accountcreated').datetimepicker();
			});
			 $(document).ready(function()
					 {
					     $('[title]').qtip({
					         position: {
					             target: 'mouse', // Track the mouse as the positioning target
					             adjust: { x: 5, y: 5 } // Offset it slightly from under the mouse
					         }
					     });
					 });
	</script>
	<?php if(!$control->edit()) echo $usersTable->scripts(); ?>
	</div>
</div></body>
</html>