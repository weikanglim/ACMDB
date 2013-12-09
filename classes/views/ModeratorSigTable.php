<?php
class ModeratorSigTable extends Table{
	private $members_id, $gid;
	public function __construct($records, $headers, $link, $members_id, $gid){
		parent::__construct($records, $headers, $link, false, true);
		$this->members_id = $members_id;
		$this->gid = $gid;
	}
	

	protected function generateScripts(){
		$this->_scripts = "<script src=\"/jquery-1.10.2.min.js\"></script><script>
$('#delete_selected{$this->gid}').click( function(){
var deleteArr = [];
$('.delete_group{$this->gid}:checked').each(function(){
deleteArr.push($(this).val());
});
var deleteStr = deleteArr.join(\":\");
if(confirm('Are you sure you want to delete these members?')){
  var deltoken = document.getElementById('delete_token{$this->gid}').value;
  location.assign('index.php?delete=' + deleteStr + '&delete_token{$this->gid}=' + deltoken + '&gid=' + {$this->gid});
}
})
	
$('#select-deselect-all{$this->gid}').click( function(){
  if($(this).val() == 'Select all'){
    $('.delete_group{$this->gid}:not(checked)').each(function(){
       $(this).prop('checked', true);
	});
	$(this).val('Deselect all');
  } else {
    $('.delete_group{$this->gid}:checked').each(function(){
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
		$this->_tableTop = "<input type='button' 
		name='select-deselect-all{$this->gid}' id='select-deselect-all{$this->gid}' value='Select all'>";
	}
	protected function generateTableBody(){
		$generated = "";
		$records = $this->_records;
		if (count ( $records )) {
			$generated .= "<tbody>";
			$x = 0;
			foreach ( $records as $record ) {
				// Start of row
				$generated  .= "<tr>";
				$id = $this->members_id[$x];
				$deletion = "<td><input type='checkbox' name='delete' id='delete{$id}' class='delete_group{$this->gid}' value='{$id}'></td>";
				
				foreach	 ( $record as $field => $value ) {
					$generated .= "<td>{$value}</td>";
				}
				$generated .= "$deletion</tr>";
				// End of row
				$x ++;
			}
				
			$generated .= "</tbody>";
		}
		$this->_tableBody = $generated;
	}
	
	protected function generateTableHead(){
		$generated = "";
		$headers = $this->_headers;
		if($headers){
			$generated .= "<thead><tr>";
			foreach ( $headers as $header ) {
				$header_val = $header;
				$header = Format::nice ( $header );	// nice formatting
				$generated .= "<th>{$header}</th>";
			}
			$generated .= $this->_delete ? "<th>Delete</th>" : ""  . "</tr></thead>";
		}
		$this->_tableHead = $generated;
	}
	
	protected function generateTableFooter(){
		$token_name = "delete_token{$this->gid}";
		$token = Token::generate("delete_token{$this->gid}");
		$this->_tableFooter .= "<div style='float:left'><form action=\"addMember.php\" method=\"get\">
		<input type=\"hidden\" name=\"gid\" value=\"{$this->gid}\">
		<input type=\"submit\" value=\"Add new\">
		</form></div>
		<div><form action='index.php' method='post'>
		<input type='hidden' name='$token_name' id='$token_name' value='$token' >
		<input type='submit' name='delete_selected{$this->gid}' id='delete_selected{$this->gid}' value='Delete Selected' </form></div>";
		$this->_tableFooter .= $this->_scripts;
	}
	
	public function render(){
		$this->generateScripts();
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
}