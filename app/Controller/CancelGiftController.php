<?php

class CancelGiftController extends AppController {

    public function index() {
    	// id type = organizer ID
        if (!array_key_exists("data", $this->request->query))
            $this->putError("data not found in URL", $dieBool=true);
        
        $organizerId = $this->decryptFromAPI($this->request->query['data'], 'organizer');
        
        $groupGift = $this->sendRequestToAPI(
            array(
                'action'=>'full_group_gift',
                'subaction'=>'delete',
                'organizer_id'=>$organizerId,
            )
        );
        
        $this->set("message", "Your group gift has been cancelled");
		
        $clientDetails = $this->getClientDetails();
		$this->set("redirectTarget", $clientDetails['redirectAfterCancelURL']);
    
  }
}
?>