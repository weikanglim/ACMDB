<?php
class Group{
	public $gid, $group_name, $members;

	public function __construct($gid, $title){
		$this->gid = $gid;
		$this->group_name = $title;
	}
	
	public function renderHTML(){
		$link = htmllink($this->group_name, "index.php?edit={$this->gid}", 'Edit group information.'); 
		$output = "<h3>{$link}</h3>";
		$count = count($this->members);
		$output .= "Member count: $count<br>";
		$members = $this->members;
		$member_names = array_values($members);
		$member_ids = array_keys($members);
		$records = array();
		$headers = array('Member No.', 'Member Name');
		for($i = 1; $i <= count($members); $i++){
			$records[] = array($headers[0] => $i, $headers[1] => $member_names[$i-1]);
		}
		$table = new ModeratorSigTable($records, $headers, 'index.php', $member_ids, $this->gid);
		$output .= $table->render();
		return $output;
	}

}
