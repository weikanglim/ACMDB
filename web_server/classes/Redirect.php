<?php
class Redirect{
	public static function to($location=null, $time=0){
		if($location){
			if(is_int($location)){
				switch($location){
					case 403:
						header('HTTP/1.0 403 Forbidden Access');
						include 'includes/errors/403.php';
						exit();
						break;
					case 404:
						header('HTTP/1.0 404 Not Found');
						include 'includes/errors/404.php';
						exit();
						break;
					case 502:
					
						break;
				}
			}
			if($time){
				header("refresh:{$time};Location: " . $location);
			} else{
				header('Location: ' . $location);
			}
			exit();
		}
	}
	
}