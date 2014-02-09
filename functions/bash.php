<?php
function addList($list, $admin, $admin_pw){
	exec("ssh script@hack.ndacm.org '/etc/scripts/newList {$list} {$admin} {$admin_pw}'",$output,$status);
	if($status == 0){
		return true;
	} else {
		error_log(join($output , "\n"));
		return false;
	}
}

function addMember($member_email, $list){
	exec("ssh script@hack.ndacm.org '/etc/scripts/newMember {$member_email} {$list}'",$output,$status);
	if($status == 0){
		return true;
	} else {
		error_log(join($output , "\n"));
		return false;
	}
	
}

function rmList($list){
	exec("ssh script@hack.ndacm.org '/etc/scripts/rmList {$list}'",$output,$status);
	if($status == 0){
		return true;
	} else {
		error_log(join($output , "\n"));
		return false;
	}
}

function rmMember($member_email, $list){
	shell_exec("ssh script@hack.ndacm.org '/etc/scripts/rmMember {$member_email} {$list}'",$output,$status);
	if($status == 0){
		return true;
	} else {
		error_log(join($output , "\n"));
		return false;
	}
}