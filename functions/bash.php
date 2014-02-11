<?php

function findMember($member, $list){
 	$output = shell_exec("ssh script@hack.ndacm.org '/etc/scripts/findMember {$member} {$list}'");
 	if(substr_count($output, $member)){ //output string from cmd contains the group name
 		return true;
 	}
 	return false;
}

function cloneMember($old, $new){
	exec("ssh script@hack.ndacm.org '/etc/scripts/cloneMember {$old} {$new}'",$output,$status);
	if($status == 0){
		return true;
	} else {
		error_log(join($output , "\n") . $status);
		return false;
	}
}

function addList($list, $admin, $admin_pw){
	exec("ssh script@hack.ndacm.org '/etc/scripts/newList {$list} {$admin} {$admin_pw}'",$output,$status);
	if($status == 0){
		return true;
	} else {
		error_log(join($output , "\n") . $status);
		return false;
	}
}

function addMember($member_email, $list, $notify = true){
	if($notify){
		exec("ssh script@hack.ndacm.org '/etc/scripts/newMember {$member_email} {$list}'",$output,$status);
	} else{
		exec("ssh script@hack.ndacm.org '/etc/scripts/newMemberN {$member_email} {$list}'",$output,$status);
	}
	if($status == 0){
		return true;
	} else {
		error_log(join($output , "\n") . $status);
		return false;
	}
	
}

function rmList($list){
	exec("ssh script@hack.ndacm.org '/etc/scripts/rmList {$list}'",$output,$status);
	if($status == 0){
		return true;
	} else {
		error_log(join($output , "\n") . $status);
		return false;
	}
}

function rmMember($member_email, $list, $notify = true){
	if($notify){
		exec("ssh script@hack.ndacm.org '/etc/scripts/rmMember {$member_email} {$list}'",$output,$status);
	}else{
		exec("ssh script@hack.ndacm.org '/etc/scripts/rmMemberN {$member_email} {$list}'",$output,$status);
	}
	if($status == 0){
		return true;
	} else {
		error_log(join($output , "\n") . $status);
		return false;
	}
}