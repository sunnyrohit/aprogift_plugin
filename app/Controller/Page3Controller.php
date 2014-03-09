<?php

App::uses('Model', 'Model');

class Page3Controller extends AppController {

    
    public function index() {    
		App::uses('CakeSession', 'Model/Datasource');
		
        
		if (array_key_exists("command", $this->request->data)) {
			$command = $this->request->data['command'];
            $secret = explode("=", $this->request->data['secret']);
            $encrypted = $secret[1];
            $organizerId = $this->decryptFromAPI($encrypted, 'organizer');			

			if ($command == "update-message-from-organizer") {
                if (array_key_exists("message", $this->request->data) && array_key_exists("secret", $this->request->data)) {                    
					$message = htmlentities($this->request->data['message']);
                    
                    $res = $this->sendRequestToAPI(
                        array(
                            'action'=>'organizer',
                            'subaction'=>'edit',
                            'id'=>$organizerId,
                            'message'=>$message
                        )
                    );
					
					if ($res)
                        echo "success";
                    else
                        echo "error";
				} else
					echo "error";
			}
            elseif ($command == "save-shipping-details") {
                $groupGift = $this->getFullGroupGift($organizerId);            
                $organizerDetails = $this->getOrganizerDetails($groupGift);
                $recipientDetails = $this->getRecipientDetails($groupGift);
                $data = $this->request->data;
                
                $res = $this->sendRequestToAPI(
                    array(
                        'action'=>'recipient',
                        'subaction'=>'edit',
                        'id'=>$recipientDetails['id'],
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
            elseif ($command == "delete-shipping-details") {
                $groupGift = $this->getFullGroupGift($organizerId);            
                $organizerDetails = $this->getOrganizerDetails($groupGift);
                $recipientDetails = $this->getRecipientDetails($groupGift);
                
                $res = $this->sendRequestToAPI(
                    array(
                        'action'=>'recipient',
                        'subaction'=>'deleteShippingDetails',
                        'id'=>$recipientDetails['id'],
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

        $recipient = CakeSession::read('recipient');
        $recipientemail = CakeSession::read('recipientemail');
        
        $this->set('recipient', $recipient);
        
        $occassion = CakeSession::read('occassion');
		
        $giftDetails = CakeSession::read('giftDetails');

        $this->set('giftDetails', $giftDetails);
		
		$this->set("occasion", $occassion);
        
        //pass to api so it can post
        $accessToken = (isset($this->request->data['accessToken'])) ? $this->request->data['accessToken'] : 0;
        
        $organizer = $this->request->data['organizer'];
        $this->set('organizer', $organizer);
        
        $numPeople = $this->request->data['numpeople'];
        $share = $this->request->data['myshare'];
        $this->set('myshare', $share);
        
        $organizerEmail = $this->request->data['organizeremail'];
        $this->set('organizerEmail', $organizerEmail);
        
        $PermissionInviteMore = isset($this->request->data['permission_invite_more']) ? 1: 0;
        

        // add gifts
        foreach ($giftDetails['details'] as &$gift) {

            $res = $this->sendRequestToAPI(
                array(
                    "action"=>"product",
                    "subaction"=>"add",
                    "name"=>$gift['name'],
                    "description"=>$gift['description'],
                    "price"=>$gift['price'],
                    "product_code"=>$gift['product_code'],
                    "stock"=>$gift['stock'],
                )
            );
            $gift['id'] = $res['id'];
        }
        unset($gift);
        
        // add organizer
        $res = $this->sendRequestToAPI(
            array(
                "action"=>"organizer",
                "subaction"=>"add",
                'name' => $organizer,
                'email' => $organizerEmail,
                'share' => $share,
                'permission_invite_more' => $PermissionInviteMore,
                'imessage' => htmlentities($this->request->data['imessage']),
                'fbID' => (isset($this->request->data['personfbID'])) ? $this->request->data['personfbID']:"",
                'deadline' => $this->request->data['deadline'],
                'currency'=>$giftDetails['currency']
                
            )
        );
        $organizerId = $res['id'];
        $this->set('organizerDetails', $res);
        $this->set("organizerId", $organizerId);


        foreach ($giftDetails['details'] as $gift) {
            $this->sendRequestToAPI(
                array(
                    "action"=>"cart",
                    "subaction"=>"add",
                    "organizer_id"=>$organizerId,
                    "product_id"=>$gift['id'],
                    "quantity"=>$gift['quantity'],
                )
            );
        }

        
       
 
        
        $res = $this->sendRequestToAPI(
            array(
                "action"=>"recipient",
                "subaction"=>"add",
                'name' => $recipient,
                'email' => $recipientemail,
                'organizer_id' => $organizerId,
                'occassion' => $occassion
                
            )
        );
        
        $group = array(
            "action"=>"group",
            "subaction"=>"add",
            'organizer_id'=>$organizerId,
            'count'=>($numPeople-1)
            
        );
        
        for ($i = 2; $i <= $numPeople; $i++) {
		   
            $group['name'.($i-1)] = $this->request->data['person'.$i];
            $group['email'.($i-1)] = $this->request->data['personemail'.$i];
            $group['share'.($i-1)] = $this->request->data['share'.$i];
            $group['fbID'.($i-1)] = (isset($this->request->data['personfbID'.$i])) ? $this->request->data['personfbID'.$i]: "";   
        }
        $res = $this->sendRequestToAPI($group);
        
        $this->validateConsistencyCheck($organizerId);
        
        /* send welcome messages to organizer and group users */
        $this->sendMessage($organizerId, "organizer", "welcome", $accessToken);
        $this->sendMessage($organizerId, "group_users", "welcome", $accessToken);
  
    }
    

}

?>