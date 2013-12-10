<?php
$base = $_SERVER ['DOCUMENT_ROOT'];
require_once $base . "/core/init.php";
require_once $base . "/core/admin.php";
require_once $base . "/core/private.php";



$table = 'events_view';
$edit_table = 'events';
$primary_key = 'eid';
$dbo = DB::getInstance ()->get ( $table );
$edit = false;
$error = "";
$validation = array (
	'event_name' => array('required'=>true),
	'oid' => array('required'=>true)
);

$control = new AdminTableController($dbo, $table, $edit_table, $primary_key, $validation);
$control->runController();

?>

<html>
<head>
<title>Events</title>
<link rel="stylesheet" media="all" type="text/css"
	href="/css/jquery-ui-1.10.3.custom.css" />
<link rel="stylesheet" type="text/css" href="/css/records.css">
<link rel="stylesheet" type="text/css" href="/css/table.css">
<link rel="stylesheet" type="text/css" href="/css/base.css"><link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/pure/0.3.0/pure-min.css">
<link type="text/css" rel="stylesheet" href="/css/jquery.qtip.css" />
</head><body>
<?php if(!$control->edit()){
	echo "<div class='admin'>";
} else {
	echo "<div class='long-record'>";
}?>
<div>
<h3>Events</h3>

	<div><?php echo Session::flash ( 'editSuccess' );
echo Session::flash ( 'addSuccess');
	echo $control->error(); ?></div>


		<?php
		if(!$control->edit()){
			$eventsTable = new Table($control->records(), $control->headers(), 'index.php');
			echo $eventsTable->render();			
		}else{
			$eventEdit = new EditForm($control->record(), $control->primary_key());
			echo $eventEdit->render();
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
	<script type="text/javascript">
			$(function()
			{
				$('#event_datetime').datetimepicker({
						dateFormat: 'yy-mm-dd',
						timeFormat: 'HH:mm:ss'
				});
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
	<?php if(!$control->edit()) echo $eventsTable->scripts(); ?>
</div>
</div></body>
</html>