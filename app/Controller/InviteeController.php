<?php

class InviteeController extends AppController {

    public function index() {    
        App::uses('CakeSession', 'Model/Datasource');
        
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

		 
		// for ajax requests to self
		if (array_key_exists("command", $this->request->data)) {
			$command = $this->request->data['command'];
			
			if ($command == "update-message-from-invitee") {
				if (array_key_exists("message", $this->request->data)) {
					
					$message = htmlentities($this->request->data['message']);
					$res = $this->sendRequestToAPI(
                        array(
                            'action'=>'group_user',
                            'subaction'=>'edit',
                            'id'=>$groupUserId,
                            'message'=>$message
                        )
                    );
					
					echo "success";
				} else
					echo "error";
			} else
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
        
        
        
        $group_user = $this->getThisGroupUser($groupUsersDetails, $groupUserId);
        $group_user['canInviteCount'] = $this->getInviteMoreCount($group_user, $groupUsersDetails);

        $this->set('cartDetails', $cartDetails);
        $this->set('organizerDetails', $organizerDetails);
        $this->set('recipientDetails', $recipientDetails);
        $this->set('thisGroupUser', $group_user);

		if ($group_user['message']===NULL)
			$group_user['message']="";
        
		// calculate days left till deadline
        $deadline = $organizerDetails['deadline'];
		$days_left = $this->getDaysLeft($deadline);        	
        $this->set('days_left', $days_left);
        
		// get total money paid
		$totalPaid = $this->getTotalPaid($organizerDetails, $groupUsersDetails);
        $remaining = $cartDetails['price'] - $totalPaid;
        $progress = $totalPaid * 100.0 / $cartDetails['price'];

        $usersPaid = array();
        $usersLeft = array();

		if ($organizerDetails['paid'])
			array_push($usersPaid, $organizerDetails);
		else
			array_push($usersLeft, $organizerDetails);


		foreach ($groupUsersDetails as $user) {
			if ($user['paid']) {
				array_push($usersPaid, $user);
			} else {
				array_push($usersLeft, $user);
			}
		}

		$this->set('thisGroupUser', $group_user);
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