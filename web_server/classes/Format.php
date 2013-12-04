<?php
class Format {
	public static function record($data, $print = null, $primary, $showPassword = false) {
		$output = "<form action=\"\" method=\"post\">";
		$output .= '<div class="user-record";"><table><thead><tr><th>Field</th><th>Value</th></tr></thead>';
		$x = 0;
		$primary_value = $data->$primary;
		$fields = implode ( ":", array_keys ( ( array ) $data ) );
		
		if ($print) {
			foreach ( $print as $id => $label ) {
				foreach ( $data as $field => $value ) {
					$type = "text";
					if ($field === $id) {
						if (substr_count ( $field, 'password' ) && ! $showPassword) {
							$type = 'password';
						}
						$label = nice($label);
						
						if ($x % 2 == 0) {
							$label = "<tr><td>{$label}</td>";
						} else {
							$label = "<tr class=\"alt\"><td>{$label}</td>";
						}
						
						if ($field === $primary) {
							$info = "<td><strong>{$value}</strong></td>";
						} else {
							$info = "<td><input class=\"long-input\" type=\"{$type}\" name=\"{$field}\" id=\"{$field}\" value=\"{$value}\" ></td></tr>";
						}
						
						$output = $output . $label . $info;
						$x ++;
					}
				}
			}
		} else {
			foreach ( $data as $field => $value ) {
				if (substr_count ( $field, 'password' ) && ! $showPassword) {
					$value = "";
				}
				
				if ($x % 2 == 0) {
					$label = "<tr><td>{$field}</td>";
				} else {
					$label = "<tr class=\"alt\"><td>{$field}</td>";
				}
				
				if ($field === 'uid') {
					$info = "<td><strong>{$value}</strong></td>";
				} else {
					$info = "<td><input class=\"long-input\" type=\"text\" name=\"{$field}\" id=\"{$field}\" value=\"{$value}\" ></td></tr>";
				}
				
				$output = $output . $label . $info;
				$x ++;
			}
		}
		$token = Token::generate ( 'edit_token' );
		$output .= "</table></div>";
		$output .= "<input type=\"hidden\" name=\"fields\" value=\"{$fields}\">
			<input type=\"hidden\" name=\"primary\" value=\"{$primary_value}\">
			<input type=\"hidden\" name=\"edit_token\" value=\"{$token}\">
			<input type=\"submit\" value=\"Edit\">
			</form>";
		return $output;
	}
	
	public static function sigRecord($data, $print = null, $primary, $showPassword = false) {
		$output = "<form action=\"\" method=\"post\">";
		$output .= '<div class="user-record";"><table><thead><tr><th>Field</th><th>Value</th></tr></thead>';
		$x = 0;
		$primary_value = $data->$primary;
		$fields = implode ( ":", array_keys ( ( array ) $data ) );
	
		if ($print) {
			foreach ( $print as $id => $label ) {
				foreach ( $data as $field => $value ) {
					$type = "text";
					if ($field === $id) {
						if (substr_count ( $field, 'password' ) && ! $showPassword) {
							$type = 'password';
						}
						$label = nice($label);
						
						if ($x % 2 == 0) {
							$label = "<tr><td>{$label}</td>";
						} else {
							$label = "<tr class=\"alt\"><td>{$label}</td>";
						}
	
						if ($field === $primary) {
							$info = "<td><strong>{$value}</strong></td>";
						} else if($field === "leader_id"){
							
						}else {
							$info = "<td><input class=\"long-input\" type=\"{$type}\" name=\"{$field}\" id=\"{$field}\" value=\"{$value}\" ></td></tr>";
						}
	
						$output = $output . $label . $info;
						$x ++;
					}
				}
			}
		} else {
			foreach ( $data as $field => $value ) {
				if (substr_count ( $field, 'password' ) && ! $showPassword) {
					$value = "";
				}
	
				if ($x % 2 == 0) {
					$label = "<tr><td>{$field}</td>";
				} else {
					$label = "<tr class=\"alt\"><td>{$field}</td>";
				}
	
				if ($field === 'uid') {
					$info = "<td><strong>{$value}</strong></td>";
				} else {
					$info = "<td><input class=\"long-input\" type=\"text\" name=\"{$field}\" id=\"{$field}\" value=\"{$value}\" ></td></tr>";
				}
	
				$output = $output . $label . $info;
				$x ++;
			}
		}
		$token = Token::generate ( 'edit_token' );
		$output .= "</table></div>";
		$output .= "<input type=\"hidden\" name=\"fields\" value=\"{$fields}\">
		<input type=\"hidden\" name=\"primary\" value=\"{$primary_value}\">
		<input type=\"hidden\" name=\"edit_token\" value=\"{$token}\">
		<input type=\"submit\" value=\"Edit\">
		</form>";
		return $output;
	}
	
	public static function create($fields, $print) {
		$output = '<div class="user-record";"><table><thead><tr><th>Field</th><th>Value</th></tr></thead>';
		$x = 0;
		$type = "";
		foreach ( $print as $id => $label ) {
			foreach ( $fields as $field ) {
				if ($field === $id) {
					if (substr_count ( $field, 'password' )) {
						$type = "password";
					} else {
						$type = "text";
					}
					
					if ($x % 2 == 0) {
						$label = "<tr><td>{$label}</td>";
					} else {
						$label = "<tr class=\"alt\"><td>{$label}</td>";
					}
					
					if ($field === 'uid') {
						$info = "<td><strong>{$value}</strong></td>";
					} else {
						$value = Input::get ( $field );
						$info = "<td><input class=\"long-input\" type=\"{$type}\" name=\"{$field}\" id=\"{$field}\"
								value=\"{$value}\"></td></tr>";
					}
					
					$output = $output . $label . $info;
					$x ++;
				}
			}
		}
		$output .= "</table></div>";
		return $output;
	}
	public static function adminView($records = array(), $headers, $page = 'index.php') {
		$output = "";
		if ($headers) {
			// header generation
			$output .= '<div class="datagrid";"><table><thead><tr>';
			$delete = "<tr>Delete</tr>";
			$token = Token::generate ();
			$token_name = Config::get ( 'session/token_name' );
			$x = 0;
			foreach ( $headers as $header ) {
				$header_val = $header;
				$header = nice ( $header );
				$output .= "<th><a href=\"{$page}?order={$header_val}\">{$header}</a></th>";
			}
			$output .= "</tr></thead>";
			
			if (count ( $records )) {
				$output .= "<tbody>";
				// body generation
				foreach ( $records as $record ) {
					if ($x % 2 == 0) {
						$output .= "<tr>";
					} else {
						$output .= "<tr class =\"alt\">";
					}
					$y = 0;
					foreach ( $record as $field => $value ) {
						if ($y === 0) {
							$link = $value;
							$output .= "<td><a href=\"{$page}?edit={$link}&{$token_name}={$token}\">{$value}</a></td>";
						} else {
							$output .= "<td>{$value}</td>";
						}
						$y ++;
					}
					$output .= "</tr>";
					$x ++;
				}
			}
			$output .= "</tbody></table></div>";
			$output .= "<div><form action=\"create.php\" method=\"get\"><input type=\"submit\" value=\"Add new\">
						</form></div>";
			$output .= "<script src=\"/jquery-1.10.2.min.js\"></script>	<script>
				$(document).ready(function() {
				    $('.datagrid tr').click(function() {
				        var href = $(this).find(\"a\").attr(\"href\");
				        if(href) {
				            window.location = href;
				        }
				    });
				}); </script>";
		}
		return $output;
	}
	public static function sigView($records = array(), $headers, $page = 'index.php', $joinedGroups) {
		$output = "";
		if ($headers) {
			// header generation
			$output .= '<div class="datagrid";"><table><thead><tr>';
			$token = Token::generate ( 'participate_token' );
			$token_name = 'ptoken';
			$x = 0;
			foreach ( $headers as $header ) {
				$header_val = $header;
				$header = nice ( $header );
				$output .= "<th><a href=\"{$page}?order={$header_val}\">{$header}</a></th>";
			}
			$output .= "<th>Membership</th></tr></thead>";
			
			if (count ( $records )) {
				$output .= "<tbody>";
				// body generation
				foreach ( $records as $record ) {
					if ($x % 2 == 0) {
						$output .= "<tr>";
					} else {
						$output .= "<tr class =\"alt\">";
					}
					$y = 0;
					foreach ( $record as $field => $value ) {
						if ($field === 'gid') { // assume index in first column
							$gid = $value;
							if (! in_array ( $gid, $joinedGroups )) {
								$endOfRow = "<td>
								<form action=\"\" method=\"post\" align=\"center\" onsubmit=\"return confirmJoin();\">
								<input type=\"submit\" value=\"Join\">
								<input type=\"hidden\" name=\"join\" value=\"{$gid}\">
								<input type=\"hidden\" name=\"{$token_name}\" value=\"{$token}\">
								</form></td>";
								// <a href=\"{$page}?join={$gid}&{$token_name}={$token}\">Join</a></td>";
							} else {
								$endOfRow = "<td>
								<form action=\"\" method=\"post\" align=\"center\" onsubmit=\"return confirmLeave();\">
								<input type=\"submit\" value=\"Leave\">
								<input type=\"hidden\" name=\"leave\" value=\"{$gid}\">
								<input type=\"hidden\" name=\"{$token_name}\" value=\"{$token}\">
								</form></td>";
								// $endOfRow = "<td><a href=\"{$page}?leave={$gid}&{$token_name}={$token}\">Leave</a></td>";
							}
						}
						
						$output .= "<td>{$value}</a></td>";
						$y ++;
					}
					$output .= "{$endOfRow}</tr>";
					$x ++;
				}
			}
			$output .= "</tbody></table></div>";
		}
		return $output;
	}
	public static function eventView($records = array(), $headers, $page = 'index.php', $joinedEvents) {
		$output = "";
		if ($headers) {
			// header generation
			$output .= '<div class="datagrid";"><table><thead><tr>';
			$token = Token::generate ( 'participate_token' );
			$token_name = 'ptoken';
			$x = 0;
			foreach ( $headers as $header ) {
				$header_val = $header;
				$header = nice ( $header );
				$output .= "<th><a href=\"{$page}?order={$header_val}\">{$header}</a></th>";
			}
			$output .= "<th>RSVP</th></tr></thead>";
			
			if (count ( $records )) {
				$output .= "<tbody>";
				// body generation
				foreach ( $records as $record ) {
					if ($x % 2 == 0) {
						$output .= "<tr>";
					} else {
						$output .= "<tr class =\"alt\">";
					}
					$y = 0;
					foreach ( $record as $field => $value ) {
						if ($field === 'eid') { // assume index in first column
							$eid = $value;
							if (! in_array ( $eid, $joinedEvents )) {
								$endOfRow = "<td>
								<form action=\"\" method=\"post\" align=\"center\" onsubmit=\"return confirmJoin();\">
								<input type=\"submit\" value=\"Join\">
								<input type=\"hidden\" name=\"join\" value=\"{$eid}\">
								<input type=\"hidden\" name=\"{$token_name}\" value=\"{$token}\">
								</form></td>";
							} else {
								$endOfRow = "<td>
								<form action=\"\" method=\"post\" align=\"center\" onsubmit=\"return confirmLeave();\">
								<input type=\"submit\" value=\"Leave\">
								<input type=\"hidden\" name=\"leave\" value=\"{$eid}\">
								<input type=\"hidden\" name=\"{$token_name}\" value=\"{$token}\">
								</form></td>";
							}
						}
						
						$output .= "<td>{$value}</a></td>";
						$y ++;
					}
					$output .= "{$endOfRow}</tr>";
					$x ++;
				}
			}
			$output .= "</tbody></table></div>";
		}
		return $output;
	}
	public static function options($options = array()) {
		$output = "";
		foreach ( $options as $option ) {
			$output .= "<option value =\"{$option}\"> {$option}</option>";
		}
		return $output;
	}
	public static function link($text, $to) {
		$text = htmlspecialchars_decode ( $text );
		$to = htmlspecialchars_decode ( $to );
		return "<a href=\"{$to}\">{$text}</a>";
	}
}