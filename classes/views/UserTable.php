<?php
class UserTable extends Table{
	public function __construct($records, $headers, $link){
		parent::__construct($records, $headers, $link, false, false);
	}
	
	protected function generateScripts(){
	}
	protected function generateTableTop(){
		
	}
	
	public function render(){
		$this->generateTableTop();
		$this->generateTableHead();
		$this->generateTableBody();
		$this->generateTableFooter();
	
	
		$table = $this->_tableTop .
		'<div class="user-table"><table>' .
		$this->_tableHead .
		$this->_tableBody .
		"</table></div>" .
		$this->_tableFooter;
		return $table;
	}
	
	protected function generateTableFooter(){
		$this->_tableFooter = "";
	}
}