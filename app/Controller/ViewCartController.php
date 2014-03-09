<?php

class ViewCartController extends AppController {

    public function index() {
    	// id type = groupUserId
		if (!array_key_exists("data", $this->request->query))
            $this->putError("data not found in URL", $dieBool=true);

        $groupUserId = $this->decryptFromAPI($this->request->query['data'], 'group_user');
        
        $groupUser = $this->sendRequestToAPI(
            array(
                'action'=>'group_user',
                'subaction'=>'view',
                'id'=>$groupUserId,
            )
        );
        $organizerId = $groupUser['organizer_id'];
        
        
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