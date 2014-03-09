<?php

class FinalizeGiftController extends AppController {

    public function index() {      	
        $this->autoRender = false;
        
        $authentication = $this->request->data['authentication'];
        $clientDetails = $this->getClientDetails();
        
        if ($authentication['client_id'] == $clientDetails['id'] && $authentication['client_secret'] == $clientDetails['secret']) {
            
            $groupGift = $this->request->data;
            $organizerDetails = $this->getOrganizerDetails($groupGift);
            $groupUsersDetails = $this->getGroupUsersDetails($groupGift);
            $recipientDetails = $this->getRecipientDetails($groupGift);
            $cartDetails = $this->getCartDetails($groupGift);
        
            // TODO: Use these details as per your convenience
            
            // TODO: Email recipient with details
            // this should ideally be done by the client
            // as they usually send emails with their voucher codes
        
        }

		
    }

}

?>