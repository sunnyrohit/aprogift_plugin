<?php

class LoginController extends AppController {

	public function index() {    
		App::uses('CakeSession', 'Model/Datasource');
		$this->validateForDemo(false);
		
		if (array_key_exists('username', $this->request->data) && array_key_exists('password', $this->request->data)) {
			if ($this->validUser($this->request->data['username'], $this->request->data['password'])) {
				$this->set("validUser", true);
				
				header('Location:Page1');
			} else {
				$this->set("validUser", false);
				$this->set("formMessage", "Invalid username and/or password");
			}
		
		} else {
			$this->set("validUser", false);
		}	
	}
	
	private function validUser($username, $password) {
		// TODO: Check from DB
		return ($username=="aprogift" && $password=="w!dge7");
	} 
}

?>