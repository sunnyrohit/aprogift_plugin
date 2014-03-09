<?php

class EditCartController extends AppController {

    public function index() {
    	// id type = organizerID
		if (!array_key_exists("data", $this->request->query))
            $this->putError("data not found in URL", $dieBool=true);
        
        $organizerId = $this->decryptFromAPI($this->request->query['data'], 'organizer');
        
        if (array_key_exists("command", $this->request->data)) {
			$command = $this->request->data['command'];
			
			if ($command == "update-quantity") {
				if (array_key_exists("newQuantity", $this->request->data) && array_key_exists("productID", $this->request->data)) {
					
					$newQuantity = $this->request->data['newQuantity'];
					$productId = $this->request->data['productID'];
					
                    $res = $this->sendRequestToAPI(
                        array(
                            'action'=>'cart',
                            'subaction'=>'edit',
                            'organizer_id'=>$organizerId,
                            'product_id'=>$productId,
                            'quantity'=>$newQuantity,
                        )
                    );
					
					echo "success";
				} else
					echo "error";
			}
            elseif ($command == "remove-product") {
                if (array_key_exists("productID", $this->request->data)) {
                    $productId = $this->request->data['productID'];
					
                    $res = $this->sendRequestToAPI(
                        array(
                            'action'=>'cart',
                            'subaction'=>'delete',
                            'organizer_id'=>$organizerId,
                            'product_id'=>$productId,
                        )
                    );
                    
                    echo "success";
                } else
                    echo "error";
                
            }

            else
				echo "error";
		
			die;
		}
        
        $groupGift = $this->getFullGroupGift($organizerId);

        $organizerDetails = $this->getOrganizerDetails($groupGift);
		$cartDetails = $this->getCartDetails($groupGift);

        $this->set('cartDetails', $cartDetails);
        $this->set('organizerDetails', $organizerDetails);

    
    }

}

?>