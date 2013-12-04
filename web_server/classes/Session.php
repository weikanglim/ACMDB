<?php
class Session{
	public static function put($name, $value){
		return $_SESSION[$name] = $value;
	}
	
	public static function exists($name){
		return (isset( $_SESSION[$name] ));
	}
	
	public static function get($name){
		return $_SESSION[$name];
	}
	
	public static function delete($name){
		if(self::exists($name)){
			unset($_SESSION[$name]);
		}
	}
	
	public static function flash($name, $string=''){
		if(self::exists($name)){
			$value = self::get($name);
			self::delete($name);
			return $value;
		} else{
			if($string !== ''){
				self::put($name, $string);
			}
		}
	}
}