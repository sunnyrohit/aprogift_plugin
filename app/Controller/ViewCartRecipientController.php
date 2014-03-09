<?php

class ViewCartRecipientController extends AppController {

    public function index() {
    	// id type = groupUserId
		if (!array_key_exists("data", $this->request->query))
            $this->putError("data not found in URL", $dieBool=true);

        $recipientId = $this->decryptFromAPI($this->request->query['data'], 'recipient');
        
        $recipient = $this->sendRequestToAPI(
            array(
                'action'=>'recipient',
                'subaction'=>'view',
                'id'=>$recipientId,
            )
        );
        $organizerId = $recipient['organizer_id'];
        
        
        $groupGift = $this->getFullGroupGift($organizerId);

        $organizerDetails = $this->getOrganizerDetails($groupGift);
		$cartDetails = $this->getCartDetails($groupGift);
		$recipientDetails = $this->getRecipientDetails($groupGift);

        $this->set('cartDetails', $cartDetails);
        $this->set('organizerDetails', $organizerDetails);
        $this->set('recipientDetails', $recipientDetails);

    
    }

}

?>