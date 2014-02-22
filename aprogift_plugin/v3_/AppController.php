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
    
	//public $components = array('DebugKit.Toolbar');
	
	var $uses = array('gift', 'organizer', 'group_user', 'recipient', 'client', 'transaction');
	
	var $MandrillAPIKey = 'BfG95qPStPg6pQnCOnGr3g';
    var $encryptionKey = 'cIá†k½ÁÉúÀMÅÃ¤Ì¼8M[DÂaºSbLílÿrˆâ';
    var $paymentDelimiter = 'I';
    //var $MobikwikMid = ""; // TODO #####
    //var $MobikwikMerchantName = "AproGift";
    //var $MobikwikRedirectURL = "http://aprogift.com/v1a_api/PaymentResponse";
    

    
    public function getMobikwikDetails() {
        return array(
            //'mid'=>'MBK7746',
            'mid'=>'MBK9002',
            'merchantName'=>'Aprogift',
            'redirectURL'=>'http://aprogift.com/v1a_api/PaymentResponseMobikwik',
            //'targetURL'=>'https://www.MobiKwik.com/views/proceedtowalletpayment.jsp',
            'targetURL'=>'https://176.58.117.32/mobikwik/views/proceedtowalletpayment.jsp',
            //'checkStatusURL'=>'https://www.MobiKwik.com/wallet.do',
            'checkStatusURL'=>'https://176.58.117.32/mobikwik/wallet.do',
        );
    }
    
    public function getMobikwikSecretKey() {
        //return 'OAC9VUT6Jb7OBhU8HNWBs77i91BD';
        return 'ju6tygh7u7tdg554k098ujd5468o';
    }
    public function getPaymentURL() {
        return "http://aprogift.com/v1a_api/Payment";
    }
    
    public function generatePaymentURL($id, $userType, $clientId, $buyout=false) {
        $paymentDetails = array(
            "id"=>$id,
            "user_type"=>$userType,
            "client_id"=>$clientId,
        );
        
        $paymentURL = $this->getPaymentURL()."?data=".$this->encrypt_for_url($paymentDetails, $this->encryptionKey);
        if ($buyout) {
            $paymentURL .= "&payment_type=buyout";
        }
        
        return $paymentURL;
    }
    
    public function getOrganizerFromGroupUserId($groupUserId) {
        $groupUser = $this->group_user->findById($groupUserId);
        $organizerId = $groupUser['group_user']['organizer_id'];
        $organizer = $this->organizer->findById($organizerId);
        return $organizer['organizer'];
    }
    
    public function addPaymentURL(&$user, $clientId) {
        $userType = (isset($user['organizer_id'])) ? "group_user" : "organizer";
        
        $paymentDetails = array(
            "id"=>$user['id'],
            "user_type"=>$userType,
            "client_id"=>$clientId,
        );

        /* check whether transaction completed */
        if ($userType == "organizer") {
            $organizer = $this->getOrganizer($user['id']);
            if (!isset($organizer['transaction_completed_on']))
                $organizer['transaction_completed_on'] = NULL;
        }
        else {
            $organizer = $this->getOrganizerFromGroupUserId($user['id']);
        }
        $transaction_completed_on = $organizer['transaction_completed_on'];
        
        if ($transaction_completed_on == NULL) {
            if (!isset($user['paid']) || !$user['paid']) {
                $user['payment_URL'] = $this->generatePaymentURL($user['id'], $userType, $clientId);
            }
            
            if ($userType == "organizer") {
                $user['payment_URL_buyout'] = $this->generatePaymentURL($user['id'], $userType, $clientId, true);
            }
        }
    }

    public function getClientDetails($clientId) {
        if ($client = $this->client->findById($clientId)) {
            $client = $client['client'];
        }
        else {
            $this->putError(true);
        }

        return $client;
    }
    
    public function generateTrackerURL($organizerId, $client_path, $client_secret) {   
        $secret = $this->encrypt_for_url(array('organizer_id'=>$organizerId), $client_secret);
        $link = $client_path."/Tracker?secret=".$secret;
        
        return $link;
        
    }
    public function generateInviteeURL($groupUserId, $client_path, $client_secret) {
        $secret = $this->encrypt_for_url(array('group_user_id'=>$groupUserId), $client_secret);
        $link = $client_path."/Invitee?secret=".$secret;
        
        return $link;
    }
    public function generateRecipientURL($recipientId, $client_path, $client_secret) {
        $secret = $this->encrypt_for_url(array('recipient_id'=>$recipientId), $client_secret);
        $link = $client_path."/Recipients?secret=".$secret;
        
        return $link;
    }
    
    
	/*
	// New more efficient funtion for authenticating
	public function authenticate($user) {
		$secret = $this->request->query['secret'];
		$hash = $user['id'] . $user['name'] . $user['email'];
		$hash = md5($hash);
		if ($hash === $secret) {
			return;
		}
		else {
			throw new NotFoundException('Invalid parameters');
		}
	}
	
	public function getPath(){
		return "http://aprogift.com/v1a_integrated";
	}
    */
    
    public function getDataSourceName($client_db_name) {
        return 'aprogift_client_'.$client_db_name;
    }
    
    public function changeDataSourceForAll($client_db_name) {
        $this->gift->setDataSource($client_db_name);
        $this->recipient->setDataSource($client_db_name);
        $this->group_user->setDataSource($client_db_name); // problem ?
        $this->organizer->setDataSource($client_db_name);
        $this->transaction->setDataSource($client_db_name);
        
        $this->organizer->recursive = -1;
    }
    
    private function getDelimiter() {
        return "-----";
    }
    private function getEncryptionKey($secret) {
        return $secret;
    }
    private function getEncryptionIV($secret) {
        return substr(strrev($secret), 16);
    }
    
    public function putError($dieBool=false) {
        echo "Unknown error. Contact admin@aprogift.com";
        if ($dieBool)
            die;
    }
    
    public function encrypt_for_url($data, $secret) {
		$str = "";
		$keys = array_keys($data);
		$c = count($data);
		for ($i=0; $i < $c-1; $i++) {
			$str .= $keys[$i] . '=' . $data[$keys[$i]] . ($this->getDelimiter());
		}
		$str .= $keys[$c-1] . '=' . $data[$keys[$c-1]];
		
		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->getEncryptionKey($secret), $str, MCRYPT_MODE_CFB, $this->getEncryptionIV($secret)));
		$ready_for_url = rawurlencode($encrypted);
		
		return $ready_for_url;
	}
    
    public function decrypt_for_url($str, $secret) {
		$code = base64_decode(rawurldecode($str));
		$decrypted_code = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->getEncryptionKey($secret), $code, MCRYPT_MODE_CFB, $this->getEncryptionIV($secret));
		$data = explode($this->getDelimiter(), $decrypted_code);
		 
		$res = array();
		
		foreach ($data as $pair) {
				$data2 = explode('=', $pair);
				$res[$data2[0]] = $data2[1];
		}
		return $res;
	}
    
    public function generateRandomString($len=2) {
        $choices = 'abcdefghijklmnopqrstuvwxyz1234567890';
        $str = "";
        for ($i=0; $i<$len; $i++)
            $str .= $choices[rand(0, strlen($choices)-1)];
        return $str;
    }
    
    public function generateMobikwikOrderId($client_id, $user_type, $id) {

        $user_type_short = ($user_type=="organizer") ? "o" : "g";
        
        $orderId = array($client_id, $user_type_short, $id, $this->generateRandomString());
        
        $orderIdString = implode($this->paymentDelimiter, $orderId);
        return $orderIdString;
    }
    
    public function decodeMobikwikOrderId($orderId) {
        $data = explode($this->paymentDelimiter, $orderId);
        $finalData = array(
            'client_id'=>$data[0],
            'user_type'=> ($data[1] == 'o') ? 'organizer' : 'group_user',
            'id'=>$data[2]
        );
        
        return $finalData; 
        
    }
    
    public function generateMobikwikChecksum($arr) {
        $checksum_str = "";
        foreach ($arr as $data) {
            $checksum_str .= "'{$data}'";
        }
        return hash_hmac('sha256', $checksum_str, $this->getMobikwikSecretKey());
    }
    
    public function mobikwikCheckStatus($orderId) {
        $mobikwikDetails = $this->getMobikwikDetails();
        $checksum = $this->generateMobikwikChecksum(array('gettxnstatus', $mobikwikDetails['mid'], $orderId));
        
        $data = array(
            'action'=>$action,
            'mid'=>$mobikwikDetails['mid'],
            'orderid'=>$orderId,
            'checksum'=>$checksum
        );
        
        $response_string = $this->postToPage($mobikwikDetails['checkStatusURL'], $data);
        return simplexml_load_string($response_string);
    }
    
    public function getMobikwikMessageForStatus($statusCode) {
        $messages = array (
            "0"  => "Thank you! Your payment has been processed",
            "10" => "Sorry but the merchant key does not exist for AproGift. Please notify and contact admin@aprogift.com for further details",
            "20" => "Sorry but your wallet has been blocked due to some reason. Please contact compliance@mobikwik.com for solving this issue",
            "21" => "Sorry but the merchant AproGift has been blocked due to some reason. Please notify and contact admin@aprogift.com for further details",
            "22" => "Sorry but the merchant AproGift does not exist. Please notify and contact admin@aprogift.com for further details",
            "23" => "Sorry but the merchant AproGift has not been registered on mobikwik. Please notify and contact admin@aprogift.com for further details",
            "30" => "Sorry but the additional amount was not added to the wallet. Please try again",
            "31" => "Sorry but the amount was not debited from the wallet. Please try again",
            "32" => "Sorry but the amount was not credited to the wallet. Please try again",
            "40" => "The transaction was cancelled",
            "41" => "The transaction was cancelled",
            "42" => "The transaction was cancelled",
            "50" => "Sorry but the transaction has failed and the money was not debited due to duplicated order ID. Please contact admin@aprogift.com",
            "51" => "Sorry but the transaction has failed and the money was not debited due to duplicated order ID. Please contact admin@aprogift.com",
            "52" => "Sorry but the transaction has failed and the money was not debited due to duplicated order ID. Please contact admin@aprogift.com",
            "53" => "Please enter a valid email ID and try again",
            "60" => "Sorry but the authentication has failed. Please check login details and try again",
            "70" => "Sorry but the monthly topup/transaction limit for wallet has crossed. Please contact compliance@mobikwik.com",
            "71" => "Sorry but the monthly topup/transaction limit for wallet has crossed. Please contact compliance@mobikwik.com",
            "72" => "Sorry but the maximum transaction limit for this merchant has been crossed. Please contact admin@aprogift.com",
            "91" => "Sorry but an unexpected error has occurred",
            "99" => "Sorry but an unexpected error has occurred",
        );
        
        $message = array_key_exists($statusCode, $messages) ? $messages[$statusCode] : "Unknown Error";
        $message .= "<br> Redirecting you back in 10 seconds";
        
        return $message;
    }
    
	/*
	public function checkValidationForDemo() {
		App::uses('CakeSession', 'Model/Datasource');
		if (!CakeSession::read('validatedForDemoV1a')) {
			header('Location:Login');
			die;
		}
	}
	
	public function validateForDemo($valid) {
		App::uses('CakeSession', 'Model/Datasource');
		if ($valid) {
			CakeSession::write('validatedForDemoV1a', 1);
		} else {
			CakeSession::delete('validatedForDemoV1a');
		}
	}
	*/
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
	
    public function getTotal($organizerDetails, $groupUsersDetails) {
        $total = intval($organizerDetails['share']);
        
        foreach ($groupUsersDetails as &$groupUser)
            $total += $groupUser['share'];
        
        return $total;
    }
    
	public function getTotalPaid($organizerDetails, $groupUsersDetails) { 
		$total = 0;
		
		if ($organizerDetails['paid'] == '1') {
			$total += $organizerDetails['share'];
		}
		
		foreach ($groupUsersDetails as $user) {
			if ($user['paid'] == '1') {
				$total += intval($user['share']);
			}
		}
		return $total;
	}
	
    
    
    public function getTotalLeft($organizerDetails, $groupUsersDetails, $giftDetails) {
        $giftCost = intval($giftDetails['price']);
        $paid = intval($this->getTotalPaid($organizerDetails, $groupUsersDetails));
        return $giftCost - $paid;
    }
    
    
    
    public function getNotPaidCountGroupUsers($groupUsersDetails) {
        $count = 0;
        foreach ($groupUsersDetails as &$groupUser) {
            if (!$groupUser['paid'])
                $count++;
        }
        return $count;
    }
    
	function getGroupUser($id) {
		if ($this->group_user->exists($id)) {
			$group_user = $this->group_user->findById($id);
			return $group_user['group_user'];
		}
		else 
        	return null;
	}
	
	public function getDateTime() {
        date_default_timezone_set("Asia/Calcutta");
        return date('Y-m-d H:i:s');
    }
	
    public function finalizeGroupGift($organizerId) {
        $this->organizer->id = $organizerId;
        $this->organizer->saveField('transaction_completed_on', $this->getDateTime());
        
        // send email to recipient
    }
    
    public function isGroupGiftComplete($organizerId) {
        $groupGift = $this->getCompleteGroupGift($organizerId);
        $organizerDetails = $this->getOrganizerDetails($groupGift);
        $groupUsersDetails = $this->getGroupUsersDetails($groupGift);
        $giftDetails = $this->getGiftDetails($groupGift);
        
        $amountLeft = $this->getTotalLeft($organizerDetails, $groupUsersDetails, $giftDetails);
        
        return $amountLeft <= 0;
    }
    
	function getCompleteGroupGift($organizerId) {
		$organizer = $this->organizer->findById($organizerId);
        $gift = $this->gift->findById($organizer['organizer']['gift_id']);
        $recipient = $this->recipient->find(
            'first',
            array(
                'conditions'=> array(
                    'organizer_id'=>$organizerId
                )
            )
        );
        $groupUsersArray = $this->group_user->find(
            'all',
            array(
                'conditions'=> array(
                    'organizer_id'=>$organizerId
                )
            )
        );
        
        $groupUsers = array();
        foreach ($groupUsersArray as $groupUser) {
            array_push($groupUsers, $groupUser['group_user']);
        }
        
        
        return array(
            'organizer'=>$organizer['organizer'],
            'Gift'=>$gift['gift'],
            'Recipient'=>$recipient['recipient'],
            'GroupUsers'=>$groupUsers
        );
        
	}
	/*
	public function getGiftImgSrc($giftId) {
		return "img/item/img_".$giftId.".jpg";
	}
	*/
    
    public function getOrganizer($organizerId) {
        $organizer = $this->organizer->findById($organizerId);
        return $organizer['Organizer'];  
    }
    
	public function getOrganizerDetails($organizer) {
		return $organizer['organizer'];
	}
	
	public function getRecipientDetails($organizer) {
		return $organizer['Recipient'];
	}
	
	public function getGroupUsersDetails($organizer) {
		return $organizer['GroupUsers'];
	}
	
	public function getGiftDetails($organizer) {	
		return $organizer['Gift'];
        /*
        // for Tracker, Invitee, Recipient, etc (unpacking)
		if (isset($gift['name'])) {
			$gift['imgSrc'] = $this->getGiftImgSrc($gift['id']);
			return $gift;
		}
		// for Page 1 (retrieving)
		else {
			$gift1 = $this->gift->findById($gift['id']);
			$gift1 = $gift1['gift'];
			$gift1['imgSrc'] = $this->getGiftImgSrc($gift1['id']);
			return $gift1;
		}
        */
	}
	
	public function getSecret($user) {
		$hash = $user['id'] . $user['name'] . $user['email'];
		$hash = md5($hash);
		return $hash;
	}
    /*
	public function reportBackToAprogift($id) {
		App::uses('HttpSocket', 'Network/Http');
		$gift = $this->getCompleteGroupGift($id);
		$gift = json_encode($gift);
		$URL = "www.aprogift.com/widget/v1a_api/report";
		$HttpSocket = new HttpSocket();
		$results = $HttpSocket->post($URL, array('gift' => $gift));
		$this->log($results);
	}
	*/
	public function isFacebookEmail($email) {
		return strpos($email, "@facebook.com");
	}

// Dont use this function
    public function create_email_template($title="", $body="", $fb=false) {

	$img_logo = "http://aprogift.com/img/logo_white_edited_1.1.jpg";
	$img_giftbox = "http://aprogift.com/img/giftbox_upper.jpg";
	$img_collective_gift = "http://aprogift.com/img/Collective_Gift_edited_1.jpg";
	$img_ecommerce_currency = "http://aprogift.com/img/E_commerce_website.jpg";
	
	if ($fb) {
		$fontSize = "16px";
		$width = "345px";
	} else {
		$fontSize = "14px";
		$width = "600px";	
	}
	
	$message  = "<html><body><table style='background-color:#f7fa98; width:99%; font-family:tahoma, verdana,arial;font-size:{$fontSize}'>
	<tr><td><table style='width:{$width};margin:0 auto'>";
	$message .= "\n<tr><td style='text-align:center;font-size:10px'>{$title}</td></tr>";
	
	if ($fb) {
		$message .= "<tr><td style='background-color:#2D3192;color:#ffffff;font-size:23px;text-align:center;padding:4px 0;
		letter-spacing:2px;font-weight:bold'>Aprogift.com</td></tr>";
	} else { 
		$message .= "<tr><td><img src='{$img_logo}' style='width:100%'></td></tr>";
		$message .= "\n<tr><td><img src='{$img_giftbox}' style='width:100%'></td></tr>";
	}
	$message .= "<tr><td style='text-align:left;background-color:#fff;padding:10px'>";
	$message .= $body;
	$message .= "\n<br>-<br>Team APROGift<br><br>";
	
	$message .= "\n<div style='width:180px;font-size:14px;padding:5px;margin:0 auto;background-color:#EEEEEE;
	border-radius:10px;text-align:center'>
	<a href='http://www.aprogift.com' style='color:#111;'>Recommend to a friend!</a></div>";
	if (!$fb) {
		$message .= "<img src='{$img_collective_gift}'> <img src='{$img_ecommerce_currency}'>";
	}
	$message .= "\n</tr></td>";

	$message .= "<tr><td style='background-color:#F7F5AE'>";
	
	$message .= "<div style='text-align:center'>";
	$message .= "<a href='https://twitter.com/APROGift'>follow on Twitter</a>|";
	$message .= "\n<a href='https://www.facebook.com/APROGift'>friend on Facebook</a>|";
	$message .= "<a href='http://aprogift.tumblr.com/'>On Tumblr</a>";
	$message .= "</div><br><br>";
	$message .= "Copyright &copy; 2013  APROGift, All rights reserved.<br>";
	$message .= "<br><b>For any queries:</b><br>admin@aprogift.com";
	
	$message .= "\n</tr></td>";
	
	$message .= "</td></tr></table></table></body></html>";
	
	return $message;
    }


    // Use the following functions
    public function create_email_welcome_organizer($organizer_name, $recipient_name, $url_pay_my_share="", $url_manage_group_gift, $fb=false) {
	
	$message = "\n<h2 style='text-align:center'>Welcome ".ucfirst($organizer_name)."</h2>";
	$message .= "\nSay 'hello' to the new era of gifting conveniently through APROGift! And say good-bye to traditional money 
	splitting headaches. <br> Since you have set a group gift for {$recipient_name}, we welcome you to keep these links handy -<br>";
	
	if ($url_pay_my_share != "") {
		$message .= "\n<br><div style='width:160px;font-size:18px;padding:4px;margin:0 auto;background-color:#E86848;
	border-radius:7px;text-align:center;border:1px solid #333;box-shadow:1px 1px 1px #888;'><a href='{$url_pay_my_share}' style='text-decoration:none;color:#FFFFFF';>Pay My Share</a></div>";
		$message .= "<div style='font-size:0.8em;text-align:center'>For contributing your share of payment for the gift</div>";
	}
	$message .= "<br><b><div style='width:210px;font-size:18px;padding:4px;margin:0 auto;background-color:#E86848;
	border-radius:7px;text-align:center;border:1px solid #333;box-shadow:1px 1px 1px #888;'><a href='{$url_manage_group_gift}' style='text-decoration:none;color:#FFFFFF'>Manage ".ucfirst($recipient_name)."'s group gift</a></div></b><br>";
	
	$message .= "\nThis will be useful for anything else you might need -"; 
	$message .= "<ul><li> Editing your contribution</li>";
	$message .= "<li>Editing the gift</li>";
	$message .= "<li>Adding more friends in the group for splitting the cost</li>";
	$message .= "<li>And, reminding your friends of their due contribution and the corresponding final date! 
	(in case they are forgetful ;) )</li></ul><br>";
	$message .= "\n<br>Happy Gifting and sharing!<br>";
	$message .= "\n<br>P.S. Don't forget that you also have the option of \"buying the gift at any moment by completing the 
	transaction from your side, and then waiting for the other friends to contribute their share, so that you can be assured 
	that {$recipient_name} will definitely be getting this gift!\"<br>";
	
	$final_email = $this->create_email_template($title="As an organizer of a group gift campaign, this is an email from APROGift to you", 
				$body=$message, $fb);
	
	return $final_email;
    }

    public function create_email_reminder_organizer($organizer_name, $recipient_name, $url_pay_my_share="", $url_manage_group_gift, $fb=false)
    {

	$message = "\n<h2 style='text-align:left'>Hi ".ucfirst($organizer_name)."</h2>";
	$message .= "\nJust a friendly reminder that the date of gifting ".ucfirst($recipient_name)." is approaching and you might 
	want to check that everything is in order for purchase.<br><br> Links to help you again -<br>";
	
	if ($url_pay_my_share != "") {
		$message .= "\n<br><div style='width:160px;font-size:18px;padding:4px;margin:0 auto;background-color:#E86848;
	border-radius:7px;text-align:center;border:1px solid #333;box-shadow:1px 1px 1px #888;'><a href='{$url_pay_my_share}' style='text-decoration:none;color:#FFFFFF';>Pay My Share</a></div>";
		$message .= "<div style='font-size:0.8em;text-align:center'>For contributing your share of payment for the gift</div>";
	}
	
	$message .= "<br><b><div style='width:160px;font-size:18px;padding:4px;margin:0 auto;background-color:#E86848;
	border-radius:7px;text-align:center;border:1px solid #333;box-shadow:1px 1px 1px #888;'><a href='{$url_manage_group_gift}' style='text-decoration:none;color:#FFFFFF'>Manage ".ucfirst($recipient_name)."'s group gift</a></div></b><br>";	
	$message .= "\nThis will be useful for anything else you might need -"; 
	$message .= "<ul><li> Editing your contribution</li>";
	$message .= "<li>Editing the gift</li>";
	$message .= "<li>Adding more friends in the group for splitting the cost</li>";
	$message .= "<li>And, reminding your friends of their due contribution and the corresponding final date! 
	(in case they are forgetful ;) )</li></ul><br>";
	$message .= "\n<br>Happy Gifting and sharing!<br>";
	$message .= "\n<br><b>Most importantly if you are about to run of time</b>, don't forget that you also have the option of 
	\"buying the gift at any moment by completing the transaction from your side, and then waiting for the other friends to 
	contribute their share, so that you can be assured that {$recipient_name} will definitely be getting this gift!\"<br>";
	
	$final_email = $this->create_email_template($title="As an organizer of a group gift campaign, this is an email from APROGift to you", 
				$body=$message, $fb);
	
	return $final_email;
    }

    public function create_email_welcome_invitee($organizer_name, $recipient_name, $invitee_name, $url_pay_my_share="", 
    						$url_invite_more_friends="", $fb=false, $personal_message="") {

                            
    if ($fb) {
        $message = "Aprogift.com\nWelcome $invitee_name\n\n 
Say 'hello' to the new era of gifting conveniently through APROGift! And say good-bye to traditional money splitting headaches. 
Since you have been invited by $organizer_name to contribute to a group gift for $recipient_name, we welcome you to keep these links handy -\n\n";
    $message .= 
"Pay My Share\n
$url_pay_my_share \n
For contributing your share of payment for the gift\n\n

$organizer_name has sent this request with an attached message\n
$personal_message\n\n

Happy Gifting and sharing!\n\n

-\n
Team APROGift";

    return $message;
    
    
    }                        
    // else                        
                            
	$message = "\n<h2 style='text-align:center'>Welcome ".ucfirst($invitee_name)."</h2>";
	$message .= "Say 'hello' to the new era of gifting conveniently through APROGift! And say good-bye to traditional money 
	splitting headaches. <br> Since you have been invited by ".ucfirst($organizer_name)." to contribute to a group gift 
	for ".ucfirst($recipient_name).", we welcome you to keep these links handy -<br>";
	
	if ($url_pay_my_share != "") {
		$message .= "\n<br><div style='width:160px;font-size:18px;padding:4px;margin:0 auto;background-color:#E86848;
	border-radius:7px;text-align:center;border:1px solid #333;box-shadow:1px 1px 1px #888;'><a href='{$url_pay_my_share}' style='text-decoration:none;color:#FFFFFF';>Pay My Share</a></div>";
		$message .= "<div style='font-size:0.8em;text-align:center'>For contributing your share of payment for the gift</div>";
	}
	/*
	if ($url_invite_more_friends != "") {
		$message .= "<br><b><a href='{$url_invite_more_friends}'>Inviting more friends to this group!</a></b><br>";
		$message .= "This will be useful for  -"; 
		$message .= "<ul><li> Adding more friends in the group for splitting the cost through Facebook Or Email</li></ul><br>";
	}*/
	
	if ($personal_message != "" && $personal_message != null) {
        $message .= "\n<br></div style='text-align:center;font-size:0.9em'>{$organizer_name} has sent this request with an attached message</div>";
        $message .= "<div style='border:1px solid #222;background-color:#eee;padding:0.7em 0.4em;text-align:center'>{$personal_message}</div><br>";
    }
	$message .= "\n<br>Happy Gifting and sharing!<br>";
	
	$final_email = $this->create_email_template($title="As a person invited to be a part of a group gift campaign, this is an email 
	from APROGift to you", $body=$message, $fb);
	
	return $final_email;
    }


    public function create_email_reminder_invitee($organizer_name, $recipient_name, $invitee_name, $url_pay_my_share="",
     $url_invite_more_friends="", $url_mail_organizer="", $fb=false) {

    if ($fb) {
        $message = "Aprogift.com\nHi $invitee_name\n\n
Just a friendly reminder that you have been invited by $organizer_name to contribute to a group gift for $recipient_name.\nAnd since the date of gifting is drawing near, you might want to put in your share now! \nKey links once again for you-\n\n";
    $message .= 
"Pay My Share\n
$url_pay_my_share \n
For contributing your share of payment for the gift\n\n

Happy Gifting and sharing!\n\n

-\n
Team APROGift";

    return $message;    
    }
     
	$message = "\n<h2 style='text-align:left'>Hi ".ucfirst($invitee_name)."</h2>";
	$message .= "Just a friendly reminder that you have been invited by ".ucfirst($organizer_name)." to contribute to a group gift 
	for ".ucfirst($recipient_name).".<br>And since the date of gifting is drawing near, you might want to put in your share now!<br>
	<br>Key links once again for you -<br>";
	
	if ($url_pay_my_share != "") {
		$message .= "\n<br><div style='width:160px;font-size:18px;padding:4px;margin:0 auto;background-color:#E86848;
	border-radius:7px;text-align:center;border:1px solid #333;box-shadow:1px 1px 1px #888;'><a href='{$url_pay_my_share}' style='text-decoration:none;color:#FFFFFF';>Pay My Share</a></div>";
		$message .= "<div style='font-size:0.8em;text-align:center'>For contributing your share of payment for the gift</div>";
	}
	if ($url_invite_more_friends != "") {
		$message .= "<br><b><a href='{$url_invite_more_friends}'>Inviting more friends to this group!</a></b><br>";
		$message .= "This will be useful for  -"; 
		$message .= "<ul><li> Adding more friends in the group for splitting the cost through Facebook Or Email</li></ul><br>";
	}
	if ($url_mail_organizer != '') {
		$message .= "\n<br><b><a href='{$url_mail_organizer}'>Mail ".ucfirst($organizer_name)." to notify if your plan has 
		changed</a></b><br>";
	}
	$message .= "\n<br>Happy Gifting and sharing!<br>";
	
	$final_email = $this->create_email_template($title="As a person invited to be a part of a group 
				gift campaign, this is an email from APROGift to you", $body=$message, $fb);
	
	return $final_email;
    }
	
	
	public function create_email_welcome_recipient($organizer_name, $recipient_name, $url_recipient, $fb=false) {

	$organizer_name = ucwords($organizer_name);	
	$recipient_name = ucwords($recipient_name);	
    
    if ($fb) {
    
        $message = "Aprogift.com\nWelcome $recipient_name\n\n 
Say 'hello' to the new era of gifting conveniently through APROGift! And see how awesome it feels to receive a gift from your group of amazing friends.\n\n 
As $organizer_name and a bunch of friends have sent you this gift, you can use the following 
		link for redeeming it as well as thanking them all\n";
    $message .= 
"Thank my friends and Redeem the gift\n
$url_recipient \n

Enjoy! We hope you like the experience and come back for creating your own group gift campaign for some of your pals\n
P.S. Don't forget that you also have the option of converting it into a voucher or redeeming it at a later date!\n
Happy Gifting and sharing!\n\n

-\n
Team APROGift";

    return $message;   
    }

	$message = "\n<h2 style='text-align:center'>Welcome ".ucfirst($recipient_name)."</h2>";
	$message .= "Say 'hello' to the new era of gifting conveniently through APROGift! 
		And see how awesome it feels to receive a gift from your group of amazing friends. <br><br>\n
		As {$organizer_name} and a bunch of friends have sent you this gift, you can use the following 
		link for redeeming it as well as thanking them all -<br>\n";
	
	$message .= "\n<br><div style='width:190px;font-size:18px;padding:4px;margin:0 auto;background-color:#E86848;
		border-radius:7px;text-align:center;border:1px solid #333;box-shadow:1px 1px 1px #888;'><a href='{$url_recipient}' style='text-decoration:none;color:#FFFFFF';>Thank my friends and Redeem the gift</a></div><br>\n";
	
	$message .= "Enjoy! We hope you like the experience and come back for creating your own group gift campaign for some of your pals<br><br>\n 
		P.S. Don't forget that you also have the option of converting it into a voucher or redeeming it at a later date!<br>\n";
		
	$message .= "\n<br>Happy Gifting and sharing!<br>";
	
	$final_email = $this->create_email_template($title="As a recipient of a group gift, this is an email from APROGift to you", 
		$body=$message, $fb);
	
	return $final_email;
    }
	
	
	public function create_email_thank_group_users($group_user_name, $recipient_name, $personal_message, $fb=false) {

	$recipient_name = ucwords($recipient_name);
	$group_user_name = ucwords($group_user_name);

    if ($fb) {
        $message = "Remember the gift that you sent along with your group of friends to $recipient_name. Seems like $recipient_name loved it and is conveying a big 'Thank You'! \n\n";
        if ($personal_message != "") {
            $message .= "Here's the message from $recipient_name:\n$personal_message\n\n";
        }
        $message .= "We hope you liked the experience and come back for creating another group gift campaign for some of your pals. \n";
        $message .= "Happy Gifting and sharing!\n\n

-\n
Team APROGift";

return $message;
    }
	
	$message = "\n<h2 style='text-align:center'>Welcome ".ucfirst($group_user_name)."</h2>";
	$message .= "Remember the gift that you sent along with your group of friends to {$recipient_name}. Seems like {$recipient_name} 
		loved it and is conveying a big 'Thank You'! \n<br><br>";
	
	$message .= "<div style='border:1px solid #222;background-color:#eee;padding:0.7em 0.4em;text-align:center'>{$personal_message}</div>";
	
	$message .= "<br>We hope you liked the experience and come back for creating another group gift campaign for some of your pals. <br>\n";
	$message .= "\n<br>Happy Gifting and sharing!<br><br>";
	
	$final_email = $this->create_email_template($title="Following up on your successful gift for {$recipient_name}, this is an email from APROGift to you", $body=$message, $fb);
	
	return $final_email;
    }
	
    
    
    
    
    public function PostToPage($target, $data) {
        $postdata = http_build_query($data);

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );

        $context  = stream_context_create($opts);
        $response = file_get_contents($target, false, $context);
        //print_r($response);
        
        return $response;
    }
    
    private function sendThroughMandrill($from_name, $from_email, $to_name, $to_email, $subject, $message) {
        $target = 'https://mandrillapp.com/api/1.0/messages/send.json';
        
        $data = array(
            "key"=>$this->MandrillAPIKey,
            "message"=>array(
                "html"=>$message,
                "subject"=> $subject,
                "from_email"=> $from_email,
                "from_name"=> $from_name,
                "to"=>array(
                    array(
                        "email"=> $to_email,
                        "name"=> $to_name,
                        "type"=> "to"
                    )
                )
            )
        );
        //print_r($data);
        
        $this->PostToPage($target, $data);
   
    }
    
    private function postToOrganizerFbWall($accessToken, $organizerFbID, $taggedFbID, $message) {
        $target = "https://graph.facebook.com/$organizerFbID/feed";
        $data = array(
            'access_token'=>$accessToken,
            'message'=>$message,
            'privacy'=>array(
                'value'=>'CUSTOM',
                'allow'=>"$taggedFbID"
            ),
            'place'=>'109524955741121',
            'tags'=>"$taggedFbID"
        );
        //print_r($target);
        //print_r($data);
        $this->postToPage($target, $data);
    }
    
    
    
    
    
    
    
	public function send_email_organizer($accessToken, $organizerDetails, $recipientDetails, $emailType="welcome", $client_id, $thankMessage="") {
        if (!($emailType=="welcome" || $emailType=="reminder" || $emailType=="thanksFromRecipient"))
            $emailType = "reminder";

        $clientDetails = $this->getClientDetails($client_id);
        $link = $this->generateTrackerURL($organizerDetails['id'], $clientDetails['path'], $clientDetails['secret']);
        
        if ($emailType == "welcome") {
            $message = $this->create_email_welcome_organizer($organizerDetails['name'], $recipientDetails['name'],
                $url_pay_my_share="", 
                $url_manage_group_gift="{$link}", $fb=$this->isFacebookEmail($organizerDetails['email']));
            $subject = "Welcome from Aprogift";
        }
        else if ($emailType == "reminder") {
            $message = $this->create_email_reminder_organizer($organizerDetails['name'], $recipientDetails['name'],
                $url_pay_my_share="", 
                $url_manage_group_gift="{$link}", $fb=$this->isFacebookEmail($organizerDetails['email']));
            $subject = "Reminder from Aprogift";
        }
        else {
            $message = $this->create_email_thank_group_users(
                    $organizerDetails['name'], $recipientDetails['name'], $thankMessage, $fb=$this->isFacebookEmail($organizerDetails['email']));
            $subject = "{$recipientDetails['name']} says a big 'Thank You' !";
        }
        
       
        $this->sendThroughMandrill("AproGift", "noreply@aprogift.com", $organizerDetails['name'], $organizerDetails['email'], $subject, $message);
    }
    

    
    public function send_email_invitees($accessToken, $organizerDetails, $recipientDetails, $groupUsersDetails, $emailType="welcome", $client_id, $groupUserId=0 ,$thankMessage="") {
        if (!($emailType=="welcome" || $emailType=="reminder" || $emailType=="thanksFromRecipient"))
            $emailType = "reminder";
        
        $clientDetails = $this->getClientDetails($client_id);
        
        foreach ($groupUsersDetails as $invitee) {
            if ($emailType == "reminder" && $invitee['paid']=='1')
                continue;
            /* if id given, send only to that invitee */
            if ($groupUserId != 0 && $groupUserId != $invitee['id'])
                continue;

            
            $link = $this->generateInviteeURL($invitee['id'], $clientDetails['path'], $clientDetails['secret']);
            
            if ($emailType == "welcome") {
                $message = $this->create_email_welcome_invitee($organizerDetails['name'], $recipientDetails['name'], $invitee['name'],
                    $url_pay_my_share="{$link}", 
                    $url_invite_more_friends="",
                    $fb=$this->isFacebookEmail($invitee['email']),
					$personal_message=$organizerDetails['imessage']);
                $subject = "Welcome from Aprogift";
            }
            else if ($emailType == "reminder") {
                $message = $this->create_email_reminder_invitee($organizerDetails['name'], $recipientDetails['name'], $invitee['name'],
                    $url_pay_my_share="{$link}", 
                    $url_invite_more_friends="",
                    $url_mail_organizer="",
                    $fb=$this->isFacebookEmail($invitee['email']));
                $subject = "Reminder from Aprogift";
            }
            else {
                $message = $this->create_email_thank_group_users(
                        $invitee['name'], $recipientDetails['name'], $thankMessage, $fb=$this->isFacebookEmail($invitee['email']));
                $subject = "{$recipientDetails['name']} says a big 'Thank You' !";
            }
            
            if ($this->isFacebookEmail($invitee['email'])) {
                $this->postToOrganizerFbWall($accessToken, $organizerDetails['fbID'], $invitee['fbID'], $message);
            }
            else
                $this->sendThroughMandrill("AproGift", "noreply@aprogift.com", $invitee['name'], $invitee['email'], $subject, $message);
        }
    }
    

    
    public function send_email_recipient($accessToken, $organizerDetails, $recipientDetails, $groupUsersDetails, $client_id, $emailType="welcome") {
        $clientDetails = $this->getClientDetails($client_id);
        $link =  $link = $this->generateRecipientURL($recipientDetails['id'], $clientDetails['path'], $clientDetails['secret']);
        
        $message = 
        $this->create_email_welcome_recipient(
            $organizerDetails['name'], $recipientDetails['name'],
            $url_recipient="{$link}",
            $fb=$this->isFacebookEmail($recipientDetails['email'])
        );
        
        $subject = "Welcome from Aprogift";
        
        if ($this->isFacebookEmail($recipientDetails['email'])) {
            $this->postToOrganizerFbWall($accessToken, $organizerDetails['fbID'], $recipientDetails['fbID'], $message);
        }
        else
        $this->sendThroughMandrill("AproGift", "noreply@aprogift.com", $recipientDetails['name'], $recipientDetails['email'], $subject, $message);
        
        
    }
	
}