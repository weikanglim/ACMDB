<?php
class TransactionTable extends Table{
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
		$token_name = 'paytoken';
		$token = Token::generate($token_name);
		$user = Input::get('user');
		$this->_tableFooter = "<form class='pure-form pure-form-aligned' method='post'>
								<input type='hidden' name='{$token_name}' value='{$token}'>
								<div class='pure-control-group'>
								<label for='amount'>Amount</label><input type='text' name='amount'>
								<div class='pure-controls'><button class='pure-button' type='submit'>Authorize Payment</button></div</form>";
	}
}