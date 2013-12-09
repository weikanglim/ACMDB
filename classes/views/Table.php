<?php
class Table{
	protected $_headers = array(), 
			$_records = array(), 
			$_scripts, 
			$_link, 
			$_edit = true,
			$_delete = true, 
			$_tableTop,
			$_table,
			$_tableHead,
			$_tableBody,
			$_tableFooter;
	
	public function __construct($records, $headers, $link, $edit = true, $delete = true){
		$this->_link = $link;
		$this->_edit = $edit;
		$this->_headers = $headers;
		$this->_records = $records;
		$this->_delete = $delete;
	}
	
	protected function generateScripts(){
		$this->_scripts = "<script src=\"/jquery-1.10.2.min.js\"></script>	<script>
				$(document).ready(function() {
				    $('.datagrid tr td').click(function() {
				        var href = $(this).find(\"a\").attr(\"href\");
				        if(href) {
				            window.location = href;
				        }
				    })});
$('#delete_selected').click( function(){
var deleteArr = [];
$('.delete_group:checked').each(function(){
deleteArr.push($(this).val());
});
var deleteStr = deleteArr.join(\":\");
if(confirm('Are you sure you want to delete these members?')){
  var deltoken = document.getElementById('delete_token').value;
  location.assign('index.php?delete=' + deleteStr + '&delete_token=' + deltoken);
}
})
				
$('#select-deselect-all').click( function(){
  if($(this).val() == 'Select all'){
    $('.delete_group:not(checked)').each(function(){
       $(this).prop('checked', true);
	});
	$(this).val('Deselect all');
  } else {
    $('.delete_group:checked').each(function(){
       $(this).prop('checked', false);
	});
	$(this).val('Select all');
  }
});
				</script>";
	}
	
	protected function generateTableTop(){
		$field_options = Format::options($this->_headers);
		$operators = array('=', '<', '>', '>=', '<=');
		$by_options = Format::options($operators);
$this->_tableTop = <<<EOD
<div style='float: left'>
<form action="" method="get">
<label for="Search">Search</label> <input type="text" name="search_value" id="search_value"> 
		<select name ="by">$by_options</select>
		<select name="search_field">$field_options</select> 
		<input type="submit" value="Search">
		</form>
</div>
	<div style='float:left'>
		<form action="">
			<input type="submit" value="Clear">
		</form>
	</div>

	<input style='margin-left:100px' type="button" name="select-deselect-all" id="select-deselect-all" value="Select all">
EOD;
	}
	
	protected function generateTableHead(){
		$generated = "";
		$headers = $this->_headers;
		if($headers){
			$generated .= "<thead><tr>";
			foreach ( $headers as $header ) {
				$header_val = $header;
				$header = Format::nice ( $header );	// nice formatting
				$generated .= "<th><a href=\"{$this->_link}?order={$header_val}\">{$header}</a></th>";
			}
			$generated .= $this->_delete ? "<th>Delete</th>" : ""  . "</tr></thead>";
		}	
		$this->_tableHead = $generated;		
	}
	
	protected function generateTableBody(){
		$generated = "";
		$records = $this->_records;
		if($this->_edit){
			$token = Token::generate ();
			$token_name = Config::get ( 'session/token_name' );
		}
		if (count ( $records )) {
			$generated .= "<tbody>";
			$x = 0;
			foreach ( $records as $record ) {
				// Start of row	
				$generated  .= "<tr>";
				
				foreach ( $record as $field => $value ) {
					if(substr_count(strtolower($field), 'id')){
						$id = $value;
						if($this->_delete){
							$deletion = "<td><input type='checkbox' name='delete' id='delete{$id}' class='delete_group' value='{$id}'></td>";
						}
					}
					
					if ($this->_edit && $id) { // is primary id and table is clickable
						// generate a link for editing the value
						$generated .= "<td><a href=\"{$this->_link}?edit={$id}\">{$value}</a></td>";
					}else{
						$generated .= "<td>{$value}</td>";
					}
						
				}
				
				if($this->_delete){
					$generated .= "$deletion</tr>";
				} else {
					$generated .= "</tr>";
				}
				$x ++;
				// End of row
			}
			
			$generated .= "</tbody>";
		}
		$this->_tableBody = $generated;
	}
	
	protected function generateTableFooter(){
		$token_name = 'delete_token';
		$token = Token::generate('delete_token');
		$this->_tableFooter .= "<div style='float:left'><form action=\"create.php\" method=\"get\"><input type=\"submit\" value=\"Add new\">
						</form></div>
				<div><form action='index.php' method='post'>
				<input type='hidden' name='$token_name' id='$token_name' value='$token' >
				<input type='submit' name='delete_selected' id='delete_selected' value='Delete Selected' </form></div>";
		$this->_tableFooter .= $this->_scripts;
	}
	
	public function render(){
		if($this->_edit){ // script for making rows clickable
			$this->generateScripts();
		}
		
		$this->generateTableTop();
		$this->generateTableHead();
		$this->generateTableBody();
		$this->generateTableFooter();
				
		
		$table = $this->_tableTop .
				'<div class="datagrid"><table>' .
				$this->_tableHead . 
				$this->_tableBody . 
				"</table></div>" . 
				$this->_tableFooter;
		return $table;
	}	
}