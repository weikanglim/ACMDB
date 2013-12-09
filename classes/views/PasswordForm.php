<?php
class PasswordForm extends EditForm{
	public function __construct($data, $primary){
		parent::__construct($data, $primary);
	}
	
	public function generateFooter(){
		$tokenName = 'pwd_token';
		$token = Token::generate('pwd_token');
		$user = Input::get('user');
		$this->_footer = '<input type="hidden" name="'. $tokenName . '"
				value="' . $token .'"> <input
				type="hidden" name="user" value="'. $user .'">
				<input type="submit" value="Submit">' .
				"<input type='reset' value='Reset'> <a class='alink' href='profile.php' style='margin-left:20px'>Back</a>";
	}
	
	public function render(){
		return parent::render(false, 'profile.php');
	}
}