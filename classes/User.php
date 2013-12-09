<?php
class User {
	private $_db,
			$_data,
			$_sessionName,
			$_cookieName,
			$_error,
			$_isLoggedIn = false,
			$_isAdmin = false,
			$_isLeader = false;
	
	/**
	 * 
	 * @param string $user
	 */
	public function __construct($user = null){
		$this->_db = DB::getInstance();
		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');
		
		if(!$user){
			if(Session::exists($this->_sessionName)){
				$session = Session::get($this->_sessionName);
				
				if($this->find($session)){
					$this->_isLoggedIn = true;
					if($this->data()->userlevel == 2){
						$this->_isAdmin = true;
					} 
					
					$uid = $this->data()->uid;
					$check = $this->_db->get('siggroups', array('leader_id' , '=' , $uid));
					if($check->count()){
						$this->_isLeader = true;
					}
				}
			}
		} else {
			$this->find($user);	
		}
	}
	
	public function create($username, $password){
		$userCheck = $this->_db->get('users', array('username', '=', $username));
		if(!$userCheck->count()){
			$hashInfo = Hash::create_hash($password);
			if($this->_db->insert('users', array(
				'username' => $username,
				'password' => $hashInfo['hash'],
				'salt' => $hashInfo['salt']
			))){
				return true;
			} else {
				$this->setError('Error in registration.');
				return false;
			}
		} else {
			$this->setError('Username already exists.');
			return false;
		}
	}
	
	public function update($fields = array(), $uid = null){
		if(!$uid && $this->isLoggedIn()){
			$uid = $this->data()->uid;
		} 
		
		if($this->_db->update('users', array('uid', $uid), $fields)){
			return true;
		} else {
			$this->setError('Error updating details.');
		}

		return false;
	}
	
	
	public function find($user = null){
		if($user){
			$field = (is_int($user)) ? 'uid' : 'username';
			$data = $this->_db->get('users', array($field, '=', $user));
			
			if($data->count()){
				$this->_data = $data->first();
				return true;
			} 
		}
		return false;
	} 
	
	public function login($username = null, $password = null, $remember = false){
		if(!$username && !$password && $this->exists()){
			Session::put($this->_sessionName, $this->data()->uid);	
		} else {
			$user = $this->find($username);
				
			if($user){	
				if(Hash::validate_password($password, $this->data()->salt, $this->data()->password)){
					Session::put($this->_sessionName, $this->data()->uid);
					
					if($remember){
						$hashCheck = DB::getInstance()->get('users_sessions', array('uid', '=', $this->data()->uid));
						if(!$hashCheck->count()){
							$hash = md5(uniqid());
							$hashCheck->insert('users_sessions', array(
								'uid' => $this->data()->uid,
								'hash' => $hash
							));
						} else {
							$hash = $hashCheck->first()->hash;
						}
						
						Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
					}
					
					return true;
				} else{
					$this->setError('Invalid username password combination.');
				}
			} else {
				$this->setError('User not found.');
			}
		}
		return false;
	}
	
	public function isAdmin(){
		return $this->_isAdmin;
	}

	public function logout(){
		$this->_db->delete('users_sessions', array('uid', '=' ,$this->data()->uid));
		Session::delete($this->_sessionName);
		Cookie::delete($this->_cookieName);
	}
	
	public function data(){
		return $this->_data;
	}
	
	public function error(){
		return $this->_error;
	}
	
	
	public function isLeader(){
		return $this->_isLeader;
	}
	
	public function setError($error){
		$this->_error = $error;
	}
	
	public function exists(){
		return (!$this->data());
	}
	
	public function isLoggedIn(){
		return $this->_isLoggedIn;
	}
}