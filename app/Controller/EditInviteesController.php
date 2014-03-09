<?php

App::uses('Model', 'Model');

class EditInviteesController extends AppController {

    public function index() {
        App::uses('CakeSession', 'Model/Datasource');
        
        if (!array_key_exists("data", $this->request->query))
            $this->putError("data not found in URL", $dieBool=true);
        
        $organizerId = $this->decryptFromAPI($this->request->query['data'], 'organizer');

        /* POSTed from Tracker Page */
		if (array_key_exists('organizerShare', $this->request->data)) {
			$this->set('organizerShare',  $this->request->data['organizerShare']);
		}

        
        if (array_key_exists('submitted', $this->request->data)) {
            /*
            echo "<pre>";
            print_r($this->request->data);
            die;
            */
            // boolean to track whether any user added, deleted or share changed
            $changeInGift = false;
            
            $groupGift = $this->getFullGroupGift($organizerId);
            
            $organizerDetails = $this->getOrganizerDetails($groupGift);
            $groupUsersDetails = $this->getGroupUsersDetails($groupGift);
        
            $numPeople = $this->request->data['numpeople'];
            $accessToken = (isset($this->request->data['accessToken'])) ? $this->request->data['accessToken'] : 0;
            
            $this->invalidateConsistencyCheck($organizerId);
        
            for ($i = 2; $i <= $numPeople; $i++) {
                if (array_key_exists('deletenew'.$i, $this->request->data)
                    && $this->request->data['deletenew'.$i] == "on") {
                    continue;
                }
                
                /* add new group user*/
                $newUser = $this->sendRequestToAPI(
                    array(
                        'action'=>'group_user',
                        'subaction'=>'add',
                        'name' => $this->request->data['person'.$i],
                        'email' => $this->request->data['personemail'.$i],
                        'fbID' => $this->request->data['personfbID'.$i],
                        'organizer_id' => $organizerId,
                        'share' => $this->request->data['newshare' . $i],
                    )
                );
                
                /* send welcome message */
                $this->sendMessage($organizerId, "group_users","welcome", $accessToken, '', $newUser['id']);
                $changeInGift = true;
            }
            
            foreach($groupUsersDetails as $person) {
            /* editing shares of already existing users */
                if (array_key_exists('delete'.$person['id'], $this->request->data) && $this->request->data['delete'.$person['id']] == "on") {
                /* deletion */
                    $res = $this->sendRequestToAPI(
                        array(
                            'action'=>'group_user',
                            'subaction'=>'delete',
                            'id'=>$person['id']
                        )
                    ); 
                    $changeInGift = true;
                    
                }
                else {
                /* updation */
                    if ($person['paid'] == 0 && $this->request->data['id'.$person['id'].'share'] != $person['share']) {
                        $this->updateGroupUserShare($person['id'], $this->request->data['id'.$person['id'].'share']);
                        $changeInGift = true;
                    }
                }    
            }
            
            if ($organizerDetails['paid'] == 0 && $this->request->data['organizershare'] != $organizerDetails['share']) {  
                $this->updateOrganizerShare($organizerDetails['id'], $this->request->data['organizershare']); 
                $changeInGift = true;
            }
            
            $this->validateConsistencyCheck($organizerId);
            
            if ($changeInGift)
                CakeSession::write('changeInGift', "1");
            else    
                CakeSession::write('changeInGift', "0");
                
			header("Location:Tracker?".$_SERVER['QUERY_STRING']);
            die;
        }
		
        $groupGift = $this->getFullGroupGift($organizerId);
        
        $organizerDetails = $this->getOrganizerDetails($groupGift);
		$recipientDetails = $this->getRecipientDetails($groupGift);
		$cartDetails = $this->getCartDetails($groupGift);
        $groupUsersDetails = $this->getGroupUsersDetails($groupGift);

        $this->set('organizerDetails', $organizerDetails);
		$this->set('cartDetails', $cartDetails);
		$this->set('people', $groupUsersDetails);
		
        
		$totalPaid = $this->getTotalPaid($organizerDetails, $groupUsersDetails);
        $costLeft = floatval($cartDetails['price']) - floatval($totalPaid);
		
        $this->set('giftcostleft', $costLeft);
    }

}

?>