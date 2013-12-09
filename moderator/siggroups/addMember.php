<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once $base . "/core/init.php";
require_once $base . "/core/moderator.php";

if(!Input::exists('get') && !Input::exists('post')){
	Redirect::to('index.php');
}

if(Input::exists('post')){
	if (Input::get ( 'memberName' )) {
		if (DB::getInstance ()->insert ( 'users_siggroups', array (
				'uid' => Input::get ( 'memberName' ),
				'gid' => Session::get ( 'gid' ) 
		) )) {
			Session::flash ( 'addSuccess', 'The member has been added.' );
			Redirect::to('index.php');
		}
	}
}

if(Input::get('gid') && Input::get('add_token' . Input::get('gid'))){
	$gid = Input::get('gid');
	$token =  Input::get('add_token' . Input::get('gid'));
	Session::put('gid', $gid);
	if(!Token::check($token, "add_token{$gid}")){
		Redirect::to('index.php');
	}
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
	<?php $addForm = new AddMemberForm();
		  echo $addForm->render(); ?>
</body>
</html>

