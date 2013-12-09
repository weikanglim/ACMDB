<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once $base . "/core/init.php";
require_once $base . "/core/leader.php";

$error = "";
if(Input::exists('post')){
	if (Input::get ( 'memberName' )) {
		if (DB::getInstance ()->insert ( 'users_siggroups', array (
				'uid' => Input::get ( 'memberName' ),
				'gid' => Session::get ( 'gid' ) 
		) )) {
			Session::delete('gid');
			Session::flash ( 'addSuccess', 'The member has been added.' );
			Redirect::to('index.php');
		} else {
			$error = 'An error has occurred.';
		} 
	}
}

if(Input::get('gid')){
	$gid = Input::get('gid');
	Session::put('gid', $gid);
} else {
	Redirect::to('index.php');
}


?>

<html>
<head>
<title>Add New Member</title>
<link rel="stylesheet" type="text/css" href="/css/table.css">
<link rel="stylesheet" type="text/css" href="/css/base.css">
</head><body>
<h3>Add New Member</h3>
	<?php echo $error;
		$addForm = new AddMemberForm(Session::get('gid'));
		  echo $addForm->render(); ?>
</body>
</html>

