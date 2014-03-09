<?php

App::uses('Model', 'Model');

class Page2Controller extends AppController {


    public function index() {    
		App::uses('CakeSession', 'Model/Datasource');
		
        
        $recipient = $this->request->data['recipient'];
        $recipientemail = $this->request->data['recipientemail'];
        
        $occassion = $this->request->data['occassion'];
        
		$giftDetails = CakeSession::read('giftDetails');
        $this->set('giftDetails', $giftDetails);
        
        CakeSession::write('recipient', $recipient);
        CakeSession::write('recipientemail', $recipientemail);
        CakeSession::write('occassion', $occassion);
        
    }

}

?>