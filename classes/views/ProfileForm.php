<?php
class ProfileForm extends EditForm{
	public function __construct($data, $primary){
		parent::__construct($data, $primary);
	}
	
	public function generateFooter(){
		$token = Token::generate('update_token');
		$this->_footer = '<input type="submit" value="Update profile"> <input type="hidden"
			id="update_token" name="update_token" value="' . $token . '">';
	}
}