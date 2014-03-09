<?php

class Page1Controller extends AppController {

    //var $uses = array('gift', 'organizer', 'group_user');

    public function index() {  
    	
        App::uses('CakeSession', 'Model/Datasource');
        
        $clientDetails = $this->getClientDetails();
        if ($clientDetails['dev']) {
            $this->request->data = $this->request->query;
        }

        $giftDetails = $this->getPage1Parameters();
        $giftDetails = $this->getCartDetails(array('cart'=>$giftDetails));
        CakeSession::write("giftDetails", $giftDetails);
        $this->set('giftDetails', $giftDetails);
		
    }

}

?>