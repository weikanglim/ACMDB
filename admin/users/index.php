<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once $base . "/core/init.php";
require_once $base . "/core/admin.php";


echo Session::flash ('editSuccess');
echo Session::flash ('addSuccess');
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
<link rel="stylesheet" type="text/css" href="/css/base.css">
<link rel="stylesheet" type="text/css" href="/css/base.css">
</head><body>
<h3>Users</h3>
<div>
<?php 
	echo $control->error();
?>
</div>
		<?php 
		if(!$control->edit()){
			$usersTable = new Table($control->records(), $control->headers(), 'index.php');
			echo $usersTable->render();			
		}else{
			$userEdit = new EditForm($control->record(), $control->primary_key());
			echo $userEdit->render();
		}
		?>

			<!--  loading jQuery -->
	<script type="text/javascript"
		src="/jquery-1.10.2.min.js"></script>
	<script type="text/javascript"
		src="/jquery-ui-1.10.3.custom.js"></script>
	<script type="text/javascript" src="/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="/jquery-ui-sliderAccess.js"></script>
	<script>
			$(function()
			{
				$('#accountexpires').datetimepicker();
				$('#accountcreated').datetimepicker();
			});
		</script>
</body>
</html>