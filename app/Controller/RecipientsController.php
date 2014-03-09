<?php

class RecipientsController extends AppController {

    
    public function index() {
		// id type = recipientID
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

        // for ajax requests to self
		if (array_key_exists("command", $this->request->data)) {
			$command = $this->request->data['command'];
			
			if ($command == "thank-group-users") {
				if (array_key_exists("message", $this->request->data)) {
					
					$msg = htmlentities($this->request->data['message']);
					
                    $sent = array();
                    /* to organizer */
                    $res = $this->sendMessage($organizerId, "organizer", "thanks_from_recipient", 0, $msg);
                    array_push($sent, $res['to']['name']);
                    
                    /* to group_users */
                    $res = $this->sendMessage($organizerId, "group_users", "thanks_from_recipient", 0, $msg);
                    foreach ($res['to'] as $invitee) {
                        array_push($sent, $invitee['name']);
                    }
					
                    echo "<ul>";
                    foreach ($sent as $user)
                        echo "<li>{$user}</li>";
                    echo "</ul>";
                    
					//echo "success";
				} else
					echo "error";
			}
            elseif ($command == "save-shipping-details") {
                $data = $this->request->data;
                $res = $this->sendRequestToAPI(
                    array(
                        'action'=>'recipient',
                        'subaction'=>'edit',
                        'id'=>$recipientId,
                        'shipping_name'=> $data['name'],
                        'address'=>$data['address'],
                        'city_state_country'=>$data['city'].', '.$data['state'].', '.$data['country'] ,
                        'postal_code'=>$data['postal-code'],
                        'phone'=>$data['phone'],
                    )
                );
                
                if (isset($res))
                    echo "success";
                else
                    echo "error";
            }
            else
				echo "error";
		
			die;
		}
        
        
        
        
        $groupGift = $this->getFullGroupGift($organizerId);
		
		$organizerDetails = $this->getOrganizerDetails($groupGift);
		$cartDetails = $this->getCartDetails($groupGift);
		$groupUsersDetails = $this->getGroupUsersDetails($groupGift);
		$recipientDetails = $this->getRecipientDetails($groupGift);

        if ($organizerDetails['transaction_completed_on'] == NULL) {
            die;
        }
		$shippingAddressFilled = ($recipientDetails['address'] != NULL);
        
        $this->set('shippingAddressFilled', $shippingAddressFilled);
        $this->set('cartDetails', $cartDetails);
        $this->set('organizerDetails', $organizerDetails);
        $this->set('groupUsersDetails', $groupUsersDetails);
        $this->set('recipientDetails', $recipientDetails);

    }
    
}

?>