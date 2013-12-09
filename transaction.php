<?php
require_once 'core/init.php';
require_once 'core/private.php';

if(!Input::exists('post') || !Input::get('vend')){
	Redirect::to('index.php');
}

$uid = (new User())->data()->uid;
$vend_info = explode(":", Input::get('vend'));
$vend_descript = $vend_info[0];
$vend_value = -$vend_info[1];
if(DB::getInstance()->insert('transactions', array(
	'amount' => $vend_value,
	'description' => $vend_descript,
	'uid' => $uid
))){
	setlocale(LC_MONETARY, 'en_US.UTF-8');
	$vend_money = money_format('%i', $vend_value);
	Session::flash('home', "You have paid {$vend_money} for {$vend_descript} .");
	Redirect::to('index.php');
} else{
	Session::flash('home', 'Error with transaction.');
	Redirect::to('index.php');
}
