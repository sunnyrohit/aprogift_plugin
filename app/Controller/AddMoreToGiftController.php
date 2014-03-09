<?php

class AddMoreToGiftController extends AppController {

    public function index() {
    	// id type = organizerID
		if (!array_key_exists("data", $this->request->query))
            $this->putError("data not found in URL", $dieBool=true);
        
        $organizerId = $this->decryptFromAPI($this->request->query['data'], 'organizer');
        
       
        $groupGift = $this->getFullGroupGift($organizerId);

        $organizerDetails = $this->getOrganizerDetails($groupGift);
		$cartDetails = $this->getCartDetails($groupGift);

        $this->set('cartDetails', $cartDetails);
        $this->set('organizerDetails', $organizerDetails);

        
        echo "TODO by client<br>";
        echo "add more to the gift";
        
        
        die;

    }

}

?>