<?php

class TrackerController extends AppController {

    public function index() {    
        App::uses('CakeSession', 'Model/Datasource');
        
        if (!array_key_exists("data", $this->request->query))
            $this->putError("data not found in URL", $dieBool=true);
        
        $organizerId = $this->decryptFromAPI($this->request->query['data'], 'organizer');
        
        
		if (array_key_exists("command", $this->request->data)) {
			$command = $this->request->data['command'];
			
			if ($command == "update-deadline") {
				if (array_key_exists("newDeadline", $this->request->data)) {
					
					$newDeadline = $this->request->data['newDeadline'];
					$res = $this->sendRequestToAPI(
                        array(
                            'action'=>'organizer',
                            'subaction'=>'edit',
                            'id'=>$organizerId,
                            'deadline'=>$newDeadline
                        )
                    );
					
					echo "success";
				} else
					echo "error";
			}
            elseif ($command == "message-invitees") {
                $accessToken = (isset($this->request->data['accessToken'])) ? $this->request->data['accessToken'] : 0;
                $res = $this->sendMessage($organizerId, "group_users", "reminder", $accessToken);
                $sent = array();
                foreach ($res['to'] as $invitee) {
                    array_push($sent, $invitee['name']);
                }
                
                echo "<ul>";
                foreach ($sent as $user)
                    echo "<li>{$user}</li>";
                echo "</ul>";
                
                //echo "success";
            }

            else
				echo "error";
		
			die;
		}

        $groupGift = $this->getFullGroupGift($organizerId);
        
        $organizerDetails = $this->getOrganizerDetails($groupGift);
        $this->addUserFbImage($organizerDetails);
        
       
		$recipientDetails = $this->getRecipientDetails($groupGift);
		$cartDetails = $this->getCartDetails($groupGift);
        $groupUsersDetails = $this->getGroupUsersDetails($groupGift);
        foreach ($groupUsersDetails as &$groupUser) {
            $this->addUserFbImage($groupUser);
        }
		
        $this->set('cartDetails', $cartDetails);
        $this->set('organizerDetails', $organizerDetails);
        $this->set('recipientDetails', $recipientDetails);
        
        
        // calculate days left till deadline
        $deadline = $organizerDetails['deadline'];
		$days_left = $this->getDaysLeft($deadline);        	
        $this->set('days_left', $days_left);

		// get total money paid        
		$totalPaid = $this->getTotalPaid($organizerDetails, $groupUsersDetails);		
		$remaining = $cartDetails['price'] - $totalPaid;
        $progress = $totalPaid * 100.0 / $cartDetails['price'];

	
		// segregate people (organizers and invitees) according
		// to their confirmation and payment status		
		$usersPaid = array();
		$usersLeft = array();

        if ($organizerDetails['paid']) {
        	array_push($usersPaid, $organizerDetails);
        	$organizerPaid = true;
        }
        else {
        	array_push($usersLeft, $organizerDetails);
        	$organizerPaid = false;
        }
		
        $this->set('organizerPaid', $organizerPaid);
        
        foreach ($groupUsersDetails as $user) {
            if ($user['paid']) {
                array_push($usersPaid, $user);
            } else {
                array_push($usersLeft, $user);
            }
        }

        $this->set(
            array(
                'remaining' => $remaining,
                'totalPaid' => $totalPaid,
                'progress' => $progress,
                'peopleLeft' => $usersLeft,
                'peoplePaid' => $usersPaid
            )
        );
		
		$changeInGift = CakeSession::read('changeInGift');
        CakeSession::delete('changeInGift');
		if ($changeInGift == '1')
			$this->set("flashMessage", "The shares have been updated");
    }

}

?>