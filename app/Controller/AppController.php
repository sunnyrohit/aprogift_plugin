<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    

    var $clientDetails = array(
        'id'=>'20004',
        'secret'=>'sB6TpcD6sXarzJ6pbiju3NarmrOORtUjVzdk3GGKEGcdz8s1G2KipL12cpqU0le5BhFeOvP3bR71zPRfrCo7yEY2Tpv31zXc8CgvXVOYMuzmjrs1T7XrlVIele8B45E',
        'fbAppId'=>'409470442488744',
        'logo'=>'http://static.peppercloset.com/frontend/peepercloset/default/images/logo.png',
        'redirectAfterCancelURL'=>'http://peppercloset.com',
        'dev'=>false,
        'ApiHost'=>'http://aprogift.com/v2a_api/api'
    );

    public function beforeFilter() {
        $this->set('fbAppId', $this->clientDetails['fbAppId']);
        $this->set('siteLogo', $this->clientDetails['logo']);
    }
    
    public function getClientDetails() {
        return $this->clientDetails;
    }
    
    private function maxSecondaryInviteCount() {
        return 5;
    }
    
    public function getPage1Parameters() {
        $i=1;
        $giftCart = array('details'=>array());
        while (isset($this->request->data['product_code'.$i]) && isset($this->request->data['product_name'.$i]) 
            && isset($this->request->data['product_description'.$i]) && isset($this->request->data['product_price'.$i]) 
            && isset($this->request->data['product_quantity'.$i]) && isset($this->request->data['product_stock'.$i])) {
            $gift = array(
                'product_code' => $this->request->data['product_code'.$i],
                'name' => $this->request->data['product_name'.$i],
                'quantity' => $this->request->data['product_quantity'.$i],
                'description' => $this->request->data['product_description'.$i],
                'price' => $this->request->data['product_price'.$i],
                'stock' => $this->request->data['product_stock'.$i],
            );
            array_push($giftCart['details'], $gift);
            $i++;
        }
        if (count($giftCart['details']) == 0 || !isset($this->request->data['currency'])) {
            $this->putError("Reqd parameters not passed", true);
        }
        $giftCart['currency'] = $this->request->data['currency'];
        return $giftCart;
    }
    
    public function errorAPI($errorCode) {
        echo "Error Code : $errorCode";
        die;
    }
    
    public function sendRequestToAPI($data) {
    
        $data['client_id'] = $this->clientDetails['id'];
        $data['secret'] = $this->clientDetails['secret'];

        $postdata = http_build_query($data);

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );

        $context  = stream_context_create($opts);
        $response = file_get_contents($this->clientDetails['ApiHost'], false, $context);
        
        /* echo "<pre>\n";
        print_r($data);
        print_r($response);
        echo "</pre>\n"; */
        
        $result = json_decode($response, true);
        if ($result['code'] != 0) {
            unset($data['client_id']);
            unset($data['secret']);
            print_r($data);
            $this->errorAPI($result['code']);
        }
        
        return $result['data'];
    }
    
    public function decryptFromAPI($encrypted, $action) {

        $actionToIdMapping = array(
            'organizer'=>'organizer_id',
            'recipient'=>'recipient_id',
            'group_user'=>'group_user_id',
        );
        
        $decrypted = $this->sendRequestToApi(
            array(
                'action'=>$action,
                'subaction'=>'decrypt',
                'encrypted'=>$encrypted
            )
        );

        $idType = $actionToIdMapping[$action];
        
        if (!isset($decrypted[$idType])) {
            $this->putError("API decryption problem", $dieBool=true);
        }
        
        return $decrypted[$idType];
        
    }
    
    public function putError($message='', $dieBool=false) {
        echo "Unknown error : {$message}. Contact admin@aprogift.com";
        if ($dieBool)
            die;
    }
 

	public function getDaysLeft($deadline) {

		$date1 = explode('-', $deadline);
        date_default_timezone_set("Asia/Calcutta");
        $dateToday = date('Y-m-d');
        
        $target_date =  date('F d, Y', strtotime($deadline));
        $this->set('target_date', $target_date);
        
        $days_left = intval((strtotime($deadline) - strtotime($dateToday))/86400);
        
		if ($days_left < 0)
        	$days_left = "Target Date Passed";
        elseif ($days_left == 1)
        	$days_left = "{$days_left} day left";
        else
        	$days_left = "{$days_left} days left";      

		return $days_left;
	}
	
	public function getTotalPaid($organizerDetails, $groupUsersDetails) { 
		$total = 0;
		
		if ($organizerDetails['paid'] == '1') {
			$total += $organizerDetails['share'];
		}
		
		foreach ($groupUsersDetails as $user) {
			if ($user['paid'] == '1') {
				$total += floatval($user['share']);
			}
		}
		return $total;
	}

    private function getAlreadyInvitedCount(&$thisGroupUser, &$groupUsersDetails) {
        $inviteeId = $thisGroupUser['id'];
        $count = 0;
        foreach ($groupUsersDetails as &$groupUser) {
            if ($inviteeId == $groupUser['invitee_id'])
                $count++;
        }
        return $count;
    }
    
    public function getInviteMoreCount($thisGroupUser, $groupUsersDetails) {
        return ($this->maxSecondaryInviteCount()) - $this->getAlreadyInvitedCount($thisGroupUser, $groupUsersDetails);
    }
	
	public function getFullGroupGift($organizerId) {
		$groupGift = $this->sendRequestToAPI(
            array(
                'action'=>'full_group_gift',
                'subaction'=>'view',
                'organizer_id'=>$organizerId,
            )
        );
        return $groupGift;
	}

    public function invalidateConsistencyCheck($organizerId) {
        $this->sendRequestToAPI(
            array(
                'action'=>'organizer',
                'subaction'=>'validation',
                'id'=>$organizerId,
                'consistency_check'=>0
            )
        );
    }
    public function validateConsistencyCheck($organizerId) {
        $this->sendRequestToAPI(
            array(
                'action'=>'organizer',
                'subaction'=>'validation',
                'id'=>$organizerId,
                'consistency_check'=>1
            )
        );
    }
    
    public function updateOrganizerShare($organizerId, $newShare) {
        $this->sendRequestToAPI(
            array(
                'action'=>'organizer',
                'subaction'=>'edit',
                'id' => $organizerId, 
                'share'=>$newShare
            )
        );
    }
    public function updateGroupUserShare($groupUserId, $newShare) {
        $this->sendRequestToAPI(
            array(
                'action'=>'group_user',
                'subaction'=>'edit',
                'id' => $groupUserId, 
                'share'=>$newShare
            )
        );
    }
    
    public function sendMessage($organizerId, $to/*organizer, group_users, recipient*/, $messageType/*welcome, reminder, thanks-from-recipient*/, $fbAccessToken=0, $message='', $groupUserId=0) {
        $sent = $this->sendRequestToAPI(
            array(
                'action'=>'message',
                'subaction'=>'send',
                'to'=>$to,
                'organizer_id'=>$organizerId,
                'email_type'=>$messageType,
                'group_user_id'=>$groupUserId,
                'fb_access_token'=>$fbAccessToken,
                'message'=>$message
            )
        );
        
        return $sent;
    }
    
	/*edit this function according to your website structure*/
	public function getProductImgSrc($productCode) {
		return "img/item/img_".$productCode.".jpg";
	}
	
	
    public function getCartDetails($cart) {	

        $cart = $cart['cart'];
        if (count($cart['details']) == 1) {
            $cart['name'] = $cart['details'][0]['name'];
            $cart['price'] = floatval($cart['details'][0]['price']) * intval($cart['details'][0]['quantity']);
            $cart['quantity_str'] = ($cart['details'][0]['quantity'] == 1) ? "" : " x {$cart['details'][0]['quantity']}";
            $cart['details'][0]['imgSrc'] = $this->getProductImgSrc($cart['details'][0]['product_code']);
            $cart['imgSrc'] = $cart['details'][0]['imgSrc'];
        }
        else { /* more than one item*/
            $cart['name'] = 'Gift Box';
            $cart['quantity_str'] = "";
            $cart['imgSrc'] = "img/item/giftbox.jpg";
            
            $total = 0;
            foreach ($cart['details'] as &$product) {
                $total += floatval($product['price']) * intval($product['quantity']);
                $product['imgSrc'] = $this->getProductImgSrc($product['product_code']);
            }
            $cart['price'] = $total;
        }
        return $cart;
	}
	
	
	public function getOrganizerDetails($groupGift) {
		return $groupGift['organizer'];
	}
	
	public function getRecipientDetails($groupGift) {
		return $groupGift['recipient'];
	}
	
	public function getGroupUsersDetails($groupGift) {
		return $groupGift['group_users'];
	}
	public function getThisGroupUser($groupUsersDetails, $groupUserId) {
        foreach ($groupUsersDetails as $groupUser) {
            if ($groupUser['id'] == $groupUserId)
                return $groupUser;
        }
    
    }
    
    public function addUserFbImage(&$user) {
        $userImgSrc = ($user['fbID'] === NULL || $user['fbID'] == 0) ? "img/user-thumb.png" : "https://graph.facebook.com/{$user['fbID']}/picture";
        $user['imgSrc'] = $userImgSrc;
    }
    

}