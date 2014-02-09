<?php
class AdminTableController{
	private $table, 
			$primary_key, 
			$primary_value,
			$dbo, 
			$edit = false, 
			$error = "", 
			$records, 
			$record,
			$headers,
			$edit_table,
			$validation;
	
	public function __construct($dbo, $table, $edit_table, $primary_key, $validation){
		$this->dbo = $dbo;
		$this->table = $table;
		$this->edit_table = $edit_table;
		$this->primary_key = $primary_key;
		$this->validation = $validation;
	}
	
	public function runController(){
		// Record initialization
		$this->initializeRecords();
		
		// Search
		if($this->inputExists()){
			if($this->searchInitiated()){
				$this->handleSearch();
			}
		
			// Order by field
			if($this->orderInitiated()){
				$order = $this->dbo->order( $this->table, Input::get('order'));
				$this->records = $order->results();
			}
		
			// Deletions
			if($this->deleteInitiated()){
				if(Token::check(Input::get('delete_token'), 'delete_token')){
					$errors = array();
					// Deletion of mailing list.
					if($this->edit_table == 'siggroups_edit_view'){ // Delete group mailing lists
						$lists = DB::getInstance()->get('siggroups_edit_view',array('gid','IN',explode(':' , Input::get('delete'))))
								->getResults('title');
						foreach($list as $lists){
							$list = strtolower($list);
							if(!rmList($list)){
								$errors[] = "Error removing mailing list for {$list}. 
								Please remove it manually at  <a href='http://lists.ndacm.org'>http://lists.ndacm.org</a>.";
							}
						}
						Session::flashErrors('errors', $errors);
					} else if($this->edit_table == 'users'){ // Delete subscribers from mailing lists
						$member_emails = DB::getInstance()->get('users',array('uid','IN',
							explode(':' , Input::get('delete'))))->getResults('email');
						foreach($member_email as $member_emails){
							$groups_joined = DB::getInstance()->get('users_siggroups')->getResults('gid');
							foreach($group as $groups_joined){
								$list = strtolower(DB::getInstance()->get('siggroups_edit_view',array('gid' ,'=',$group))->first()->title);
								if(!rmMember($member_email, $list)){
									$errors[] = "Error removing user {$member_email} from {$list} mailing list.
											Please remove them manually at  <a href='http://lists.ndacm.org'>http://lists.ndacm.org</a>.";
								}
							}
						}
						Session::flashErrors('errors', $errors);
					}
						
					if(DB::getInstance()->delete($this->edit_table, array($this->primary_key, 'IN',
							explode(':' , Input::get('delete')) ) )){
							Session::flash('editSuccess', 'Records have been deleted.');
							Redirect::to('index.php');
					}
				}
			}
				
				// Edit mode
			if ($this->editInitiated ()) {
				$this->primary_value = Input::get ( 'edit' );
				$this->record = $this->dbo->get ( $this->edit_table, array (
						"{$this->primary_key}",
						'=',
						"{$this->primary_value}" 
				) )->first ();
				$fields = array_keys ( ( array ) $this->record );
				$mappedFields = array ();
				foreach ( $fields as $field ) {
					$mappedFields [$field] = $field;
				}
				$this->edit = true;
			}
		}
		
		// Edit submission
		if($this->editSubmission()){
			$validate = new Validate ();
			$validation = $validate->check ( $_POST, $this->validation );
		
			if ($validation->passed ()) {
				$fields = explode(":", Input::get('fields'));
				$fieldAndValue = array();
				foreach($fields as $field){
					if(Input::get($field)  && $field !== $this->primary_key && $field !== 'userlevel'){
						$fieldAndValue["{$field}"] = Input::get("{$field}");
					}
				}
				if ($this->dbo->update($this->edit_table, array($this->primary_key, $this->primary_value), $fieldAndValue)) {
					Session::flash ( 'editSuccess', 'Record updated succesfully.' );
					Redirect::to("index.php");
				} else {
					$this->error =  "An error occurred in updating.";
				}
			} else {
				$validate_errors = $validation->errors ();
				foreach ( $validate_errors as $validate_error ) {
					$this->error .=  "$validate_error <br>";
				}
			}
		}
	}
	
	public function handleSearch(){
		$search_field = escape(Input::get ( 'search_field' ));
		$search_value = escape(Input::get ( 'search_value' ));
		$operation = Input::get('by');
		$search = $this->dbo->get ( $this->table, array (
				"{$search_field}",
				"{$operation}",
				"{$search_value}"
		) );
		
		if (! $search->error ()) {
		$this->records = $search->results ();
		if (! $search->count ()) {
		$this->error =  'No results.';
		} else if ($search->count() === 1){
		$primary_key =$this->primary_key;
		$id = $search->first()->$primary_key;
		Redirect::to("index.php?edit={$id}");
		}
		} else {
		$this->error =  "\"$search_value\" is not a valid type for $search_field.";
		}
	}
	
	public function initializeRecords(){
		if(!$this->dbo->error() && $this->dbo->count()){
			$this->records = $this->dbo->results();
			$this->headers = array_keys((array) $this->records[0]);
		} else if(!$this->dbo->count()){
			$column_names = $this->dbo->get('information_schema.columns', array('table_name' , '=', "{$this->table}"), array('column_name'))->results();
			$this->headers = array();
			$this->records = array(); // empty records
			foreach($column_names as $column_name){
				$this->headers[] = $column_name->column_name;
			}
			$this->headers = array_reverse($this->headers);
			$this->error = 'No records found.';
		}else {
			$this->error =  'Error retrieving data.';
		}
	}
	
	public function inputExists(){
		return Input::exists('get');
	}
	
	public function searchInitiated(){
		return Input::get('search_field') && Input::get('search_value');
	}
	
	public function orderInitiated(){
		return Input::get('order');
	}
	
	public function deleteInitiated(){
		return Input::get('delete');
	}
	
	public function editInitiated(){
		return Input::get ( 'edit' );
	}
	
	public function editSubmission(){
		return Input::exists('post');
	}
	public function records(){
		return $this->records;
	}
	
	public function record(){
		return $this->record;
	}
	
	public function headers(){
		return $this->headers;
	}
	
	public function error(){
		return $this->error;
	}
	
	public function edit(){
		return $this->edit;
	}
	
	public function primary_key(){
		return $this->primary_key;
	}
}

