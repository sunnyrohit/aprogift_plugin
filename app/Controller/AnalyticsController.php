<?php

class AnalyticsController extends AppController {

	public function index() {    
		if (array_key_exists('username', $this->request->data) && array_key_exists('password', $this->request->data)) {
			if ($this->validUser($this->request->data['username'], $this->request->data['password'])) {
				$this->set("validUser", true);
                date_default_timezone_set("Asia/Calcutta");
                $endDate = date('Y-m-d');
                $startDate = date('Y-m-d', strtotime($endDate)-(3600*24*30));
                $this->set('startDate', $startDate);
                $this->set('endDate', $endDate);
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