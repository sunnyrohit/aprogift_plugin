<?php
class ApiController extends AppController {
    var $action;
    var $subAction;
    var $clientId;
    var $clientPath;
   
    var $api = array(
        "gift" => array(
            "defaultParams" => array(
                'product_code' => "product_code",
                'name' => "name",
                'description' => "description",
                'price' =>  "price",
            ),
            "functionName" => "gift",
            "subApi" => array(
                "add" => array(
                    "skipDefaultParams" => false,
                    "defaultParamsOptional" => false,
                    "extraParams" => array(),
                    "optionalParams" => array(),
                ),
                "edit" => array(
                    "skipDefaultParams" => false,
                    "defaultParamsOptional" => true,
                    "extraParams" => array("id"=>"id"),
                    "optionalParams" => array(),
                ),
                "view" => array(
                    "skipDefaultParams" => true,
                    "defaultParamsOptional" => true,
                    "extraParams" => array("id"=>"id"),
                    "optionalParams" => array(),
                ),
            )
        ),
        
        "organizer" => array(
            "defaultParams" => array(
                'gift_id' => 'gift_id',
                'name' => "name",
                'email' => "email",
                'deadline' =>  "deadline",
                'share' => "share",
            ),
            "functionName" => "organizer",
            "subApi" => array(
                "invalidate" => array(
                    "skipDefaultParams" => true,
                    "defaultParamsOptional" => true,
                    "extraParams" => array("id"=>"id"),
                    "optionalParams" => array("consistency_check"=>"consistency_check"),
                ),
                "add" => array(
                    "skipDefaultParams" => false,
                    "defaultParamsOptional" => false,
                    "extraParams" => array(),
                    "optionalParams" => array("message"=>"message", "imessage"=>"imessage", "fbID"=>"fbID"),
                ),
                "edit" => array(
                    "skipDefaultParams" => false,
                    "defaultParamsOptional" => true,
                    "extraParams" => array("id"=>"id"),
                    "optionalParams" => array("message"=>"message", "imessage"=>"imessage", "fbID"=>"fbID"),
                ),
                "view" => array(
                    "skipDefaultParams" => true,
                    "defaultParamsOptional" => true,
                    "extraParams" => array("id"=>"id"),
                    "optionalParams" => array(),
                ),
                "decrypt" => array(
                    "skipDefaultParams" => true,
                    "defaultParamsOptional" => true,
                    "extraParams" => array("encrypted"=>"encrypted"),
                    "optionalParams" => array(),
                ),
            )
        ),
        
        "recipient" => array(
            "defaultParams" => array(
                'name' => "name",
                'email' => "email",
                'occassion' => "occassion",
            ),
            "functionName" => "recipient",
            "subApi" => array(
                "add" => array(
                    "skipDefaultParams" => false,
                    "defaultParamsOptional" => false,
                    "extraParams" => array("organizer_id"=>"organizer_id"),
                    "optionalParams" => array(),
                ),
                "edit" => array(
                    "skipDefaultParams" => false,
                    "defaultParamsOptional" => true,
                    "extraParams" => array("id"=>"id"),
                    "optionalParams" => array(),
                ),
                "view" => array(
                    "skipDefaultParams" => true,
                    "defaultParamsOptional" => true,
                    "extraParams" => array("id"=>"id"),
                    "optionalParams" => array(),
                ),
                "decrypt" => array(
                    "skipDefaultParams" => true,
                    "defaultParamsOptional" => true,
                    "extraParams" => array("encrypted"=>"encrypted"),
                    "optionalParams" => array(),
                ),
            )
        ),
        
        "group_user" => array(
            "defaultParams" => array(
                'name' => "name",
                'email' => "email",
                'share' => "share",
            ),
            "functionName" => "groupUser",
            "subApi" => array(
                "add" => array(
                    "skipDefaultParams" => false,
                    "defaultParamsOptional" => false,
                    "extraParams" => array("organizer_id"=>"organizer_id"),
                    "optionalParams" => array('fbID'=>'fbID', 'message'=>'message'),
                ),
                "edit" => array(
                    "skipDefaultParams" => false,
                    "defaultParamsOptional" => true,
                    "extraParams" => array("id"=>"id"),
                    "optionalParams" => array('fbID'=>'fbID', 'message'=>'message'),
                ),
                "view" => array(
                    "skipDefaultParams" => true,
                    "defaultParamsOptional" => true,
                    "extraParams" => array("id"=>"id"),
                    "optionalParams" => array(),
                ),
                "delete" => array(
                    "skipDefaultParams" => true,
                    "defaultParamsOptional" => true,
                    "extraParams" => array("id"=>"id"),
                    "optionalParams" => array(),
                ),
                "decrypt" => array(
                    "skipDefaultParams" => true,
                    "defaultParamsOptional" => true,
                    "extraParams" => array("encrypted"=>"encrypted"),
                    "optionalParams" => array(),
                ),
            )
        ),
        
        "group" => array(
            "defaultParams" => array(
                'organizer_id'=>'organizer_id'
            ),
            "functionName" => "group",
            "subApi" => array(
                "add" => array(
                    "skipDefaultParams" => false,
                    "defaultParamsOptional" => false,
                    "extraParams" => array('count'=>'group_cardinality' /*others generated in function, they are specific to group*/),
                    "optionalParams" => array(),
                ),
                "edit" => array(
                    "skipDefaultParams" => false,
                    "defaultParamsOptional" => false,
                    "extraParams" => array(),
                    "optionalParams" => array(),
                ),
                "view" => array(
                    "skipDefaultParams" => false,
                    "defaultParamsOptional" => false,
                    "extraParams" => array(),
                    "optionalParams" => array(),
                ),
            )
        ),
        
        "full_group_gift" => array(
            "defaultParams" => array(
                'organizer_id'=>'organizer_id'
            ),
            "functionName" => "fullGroupGift",
            
            "subApi" => array(
                "view" => array(
                    "skipDefaultParams" => false,
                    "defaultParamsOptional" => false,
                    "extraParams" => array(),
                    "optionalParams" => array(),
                ),
                "delete" => array(
                    "skipDefaultParams" => false,
                    "defaultParamsOptional" => false,
                    "extraParams" => array(),
                    "optionalParams" => array(),
                ),
            )
        ),
        
        "message" => array(
            "defaultParams" => array(
                'organizer_id'=>'organizer_id',
                'to' => 'to',
                'email_type' => 'email_type'
            ),
            "functionName" => "sendMessage",
            "subApi" => array(
                "send" => array(
                    "skipDefaultParams" => false,
                    "defaultParamsOptional" => false,
                    "extraParams" => array(),
                    "optionalParams" => array('message'=>'message', 'group_user_id'=>'group_user_id','fb_sccess_token'=>'access_token'),
                )
            )
        ),
    );
   
   
    var $errorCodes = array(
        "SUCCESS" => 0,
        "UNKNOWN ACTION" => -1,
        "MISSING PARAMETERS" => -2,
        "MISSING ACTION PARAMETER" => -3,
        "UNAUTHORIZED" => -4,
        "UNKNOWN SUBACTION" => -5,
        "MISSING SUBACTION PARAMETER" => -6,
        "RECORD NOT FOUND" => -7,
        "INCONSISTENT TOTAL" => -8,
        "INVALID API CALL" => -9,
        "BAD PARAMETER" => -10

    );
   
    private function generateResponse($data) {
        //$error = array_flip($this->errorCodes); // #####
        $status = true;
        if ($data['code'] != "0") $status = false;
        $data['status'] = $status;
        //$data['code'] = $error[$data['code']]; // #####     
        if (!array_key_exists('data', $data)) {
        	$data['data'] = "";
        }
        $response = json_encode($data);
        echo $response;
    }
   
   
   
    private function authorize() {

        $clientId = array_key_exists('client_id', $this->request->data) ? $this->request->data['client_id'] : null;
        $clientSecret = array_key_exists('secret', $this->request->data) ? $this->request->data['secret'] : null;
        
        if ($clientId == null || $clientSecret == null)
            return false;
            
        $conditions = array(
            'secret' => $clientSecret,
            'id' => $clientId,
        );

        if ($this->client->hasAny($conditions)) {
            $client =  $this->client->findById($clientId);
            $this->clientId = $client['client']['id'];
            $this->clientPath = $client['client']['path'];
            $this->changeDataSourceForAll($this->getDataSourceName($client['client']['client_db_name']));
            return true;
        }
        else {
            return false;
        }
        
        return false;
    }
    
    
    private function reqdParams($action, $subAction) {
        $params = array();
        
        if ($this->api[$action]['subApi'][$subAction]['skipDefaultParams'] == false 
         && $this->api[$action]['subApi'][$subAction]['defaultParamsOptional'] == false) {
            
            $params = array_merge($params, $this->api[$action]['defaultParams']);
        }
        $params = array_merge($params, $this->api[$action]['subApi'][$subAction]['extraParams']);

        return $params;
    }
    
    private function allPossibleParams($action, $subAction) {
        $params = array();
        if ($this->api[$action]['subApi'][$subAction]['skipDefaultParams'] == false) {
            $params = array_merge($params, $this->api[$action]['defaultParams']);
        }
        $params = array_merge($params, $this->api[$action]['subApi'][$subAction]['extraParams']);
        $params = array_merge($params, $this->api[$action]['subApi'][$subAction]['optionalParams']);
        return $params;
    }
 
    private function checkParams($params) {
        $parameters = array_keys($params);
        foreach ($parameters as $p) {
            if (!array_key_exists($p, $this->request->data) || $this->request->data[$p]=='') {
                return false;
            }
        }
        return true;
    }
    
    private function getParams($params) {
        $keys = array_keys($params);
        $values = array();
        foreach ($keys as $key) {
            if (array_key_exists($key, $this->request->data)) {
                $mappedKey = $params[$key];
                $values[$mappedKey] = $this->request->data[$key];                
            }
        }
        return $values;
    }
    
    private function appendNumber($data, $num) {
        $keys = array_keys($data);
        $newData = array();
        foreach ($keys as $key) {
            $newData[$key . $num] = $data[$key];
        }
        return $newData;
    }
    
    private function recordExists($params) {
        
        if (array_key_exists('gift_id', $params) && !$this->gift->exists($this->request->data['gift_id'])) {
            return false;
        }
        if (array_key_exists('organizer_id', $params) && !$this->organizer->exists($this->request->data['organizer_id'])) {
            return false;
        }
        if (array_key_exists('group_user_id', $params) && !$this->group_user->exists($this->request->data['group_user_id'])) {
            return false;
        }
        if (array_key_exists('recipient_id', $params) && !$this->recipient->exists($this->request->data['recipient_id'])) {
            return false;
        }
        if (array_key_exists('id', $params)) {
            switch ($this->action) {
                case "gift":
                    if(!$this->gift->exists($this->request->data['id']))
                        return false;
                    break;
                case "organizer":
                    if(!$this->organizer->exists($this->request->data['id']))
                        return false;
                    break;
                case "group_user":
                    if(!$this->group_user->exists($this->request->data['id']))
                        return false;
                    break;
                case "recipient":
                    if(!$this->recipient->exists($this->request->data['id']))
                        return false;
                    break;

            }
        }
        
        return true;
        
    }
    
    private function checkTotal($id) {
        $groupGift = $this->getCompleteGroupGift($id);
        $organizerDetails = $this->getOrganizerDetails($groupGift);
        $groupUsersDetails = $this->getGroupUsersDetails($groupGift);
        $giftDetails = $this->getGiftDetails($groupGift);
        
        $amount = $this->getTotal($organizerDetails, $groupUsersDetails);
        return ($amount == $giftDetails['price']);
    }
    
    private function organizer($params) {
        switch ($this->subAction) {
            case "invalidate":
                $check = array_key_exists('consistency_check', $params) ? $params['consistency_check'] : 0;
                $orgId = $params['id'];
                
                if ($check == 1 && !$this->checkTotal($orgId)) {
                    $this->generateResponse(array('code' => $this->errorCodes["INCONSISTENT TOTAL"]));
                    return;
                }
                $this->organizer->id = $orgId;
                $this->organizer->saveField('consistency_check', $check);
                $data = array('id' => $orgId, 'consistency_check' => $check);
                $this->generateResponse(array('code' => $this->errorCodes["SUCCESS"], 'data' => $data));
                break;
            
            case "decrypt":
                $data = $this->decrypt_for_url($params['encrypted'], $this->getEncryptionKey());
                if (array_key_exists('organizer_id', $data)) {
                    $this->generateResponse(array('code'=>$this->errorCodes["SUCCESS"], 'data'=>$data));
                }
                break;
                
            case "edit":
                /* special check if editing the share */
                if (array_key_exists('share',$params)) {
                    $organizer = $this->getOrganizerById($params['id']);
                    if ($organizer['consistency_check'] == 1 && $params['share'] != $organizer['share']) {
                        $this->generateResponse(array('code' => $this->errorCodes["INCONSISTENT TOTAL"]));
                        return;
                    }
                }
                $this->organizer->id = $params['id'];;			
                $this->organizer->save($params);			
                $this->generateResponse(array('code' => $this->errorCodes["SUCCESS"], 'data' => $params));
                break;
                
            case "add": 
                $params = array_merge(array('created_on' => $this->getDateTime()), $params);
                $this->organizer->create();
                $this->organizer->save($params);
                $organizer = array_merge(array('id' => $this->organizer->id), $params);
                $this->addPaymentURL($organizer, $this->clientId);
                $organizer['edit_invitees_URL'] = $this->generateEditInviteesURL($this->organizer->id, $this->clientPath);
                $this->generateResponse(array('code' => $this->errorCodes["SUCCESS"], 'data' => $organizer));
                break;
                
            case "view":
                $organizer = $this->getOrganizerById($params['id']);
                if ($organizer['transaction_completed_on'] == NULL)
                    $this->addPaymentURL($organizer, $this->clientId);
                $this->generateResponse(array('code' => $this->errorCodes["SUCCESS"], 'data' => $organizer));
                break;
        }    
    }
    
    
    private function gift($params) {
        switch ($this->subAction) {
            case "edit":
                $this->gift->id = $params['id'];;
                $this->gift->save($params);
                $this->generateResponse(array('code' => $this->errorCodes["SUCCESS"], 'data' => $params));
                break;
            case "add":
                $this->gift->create();
                $this->gift->save($params);
                $gift = array_merge(array('id'=>$this->gift->id), $params);
                $this->generateResponse(array('code' => $this->errorCodes["SUCCESS"], 'data' => $gift));
                break;
            case "view":
                $gift = $this->getGiftById($params['id']);
                $this->generateResponse(array('code' => $this->errorCodes["SUCCESS"], 'data' => $gift));
                break;
        }
    }
    

    private function recipient($params) {
        switch ($this->subAction) {
            case "edit":
                $this->recipient->id = $params['id'];
                $this->recipient->save($params);
                $this->generateResponse(array('code' => $this->errorCodes["SUCCESS"], 'data' => $params));                
                break;
            case "add":
                $this->recipient->create();
                $this->recipient->save($params);
                $recipient = array_merge(array('id' => $this->recipient->id), $params);
                $this->generateResponse(array('code' => $this->errorCodes["SUCCESS"], 'data' => $recipient));                
                break;
            case "view":
                $recipient = $this->getRecipientById($params['id']);
                $this->generateResponse(array('code' => $this->errorCodes["SUCCESS"], 'data' => $recipient));
                break;
            case "decrypt":
                $data = $this->decrypt_for_url($params['encrypted'], $this->getEncryptionKey());
                if (array_key_exists('recipient_id', $data)) {
                    $this->generateResponse(array('code'=>$this->errorCodes["SUCCESS"], 'data'=>$data));
                }
                break;
        }  
    }
    
    
    private function fullGroupGift($params) {
        $organizerId = $params['organizer_id'];
        switch ($this->subAction) {
            case "view":
                $gift = $this->getCompleteGroupGift($organizerId);
                if ($gift['organizer']['transaction_completed_on'] == NULL) {
                    $this->addPaymentURL($gift['organizer'], $this->clientId);
                    foreach($gift['groupUsers'] as &$user)
                        $this->addPaymentURL($user, $this->clientId);
                }
                $this->generateResponse(array('code' => $this->errorCodes["SUCCESS"], 'data' => $gift));
                break;
                
            case "delete":
                $groupGift = $this->getCompleteGroupGift($organizerId);
                $this->recipient->delete($groupGift['recipient']['id']);
                $groupUsersDetails = $this->getGroupUsersDetails($groupGift);
                foreach ($groupUsersDetails as $user) {
                    $this->group_user->delete($user['id']);
                }
                $this->gift->delete($groupGift['gift']['id']);
                $this->organizer->delete($organizerId);
                $this->generateResponse(array('code' => $this->errorCodes["SUCCESS"]));
                break;
        }
    }
    
   
    
    private function groupUser($params) {
   
        if ($this->subAction == "add") {
            $organizerDetails = $this->getOrganizerById($params['organizer_id']);
        }
        else {
            $organizerDetails = $this->getOrganizerFromGroupUserId($params['id']);
            $groupUserDetails = $this->getGroupUserById($params['id']);
        }
        
        switch($this->subAction) {
            case "add": 
                if ($params['share'] != "0" && $organizerDetails['consistency_check'] == 1) {
                    $this->generateResponse(array('code' => $this->errorCodes["INCONSISTENT TOTAL"]));
                    return;
                }
                $this->group_user->create();
                $this->group_user->save($params);
                $groupUser = array_merge(array('id'=>$this->group_user->id), $params);
                $this->addPaymentURL($groupUser, $this->clientId);

                $group_cardinality = $organizerDetails['group_cardinality'];
                $this->organizer->id = $organizerDetails['id'];
                $this->organizer->saveField('group_cardinality', intval($group_cardinality)+1);
                
                $this->generateResponse(array('code' => $this->errorCodes["SUCCESS"], 'data' => $groupUser));
                break;


            case "edit":
                if (array_key_exists('share', $params) && $organizerDetails['consistency_check'] == 1 && $groupUserDetails['share'] != $params['share']) {
                    $this->generateResponse(array('code' => $this->errorCodes["INCONSISTENT TOTAL"]));
                    return;
                }
                $this->group_user->id = $params['id'];
                $this->group_user->save($params);
                $this->generateResponse(array('code' => $this->errorCodes["SUCCESS"], 'data'=>$params));
                break;
                
            case "delete":
                if ($groupUserDetails['share'] != "0" && $organizerDetails['consistency_check'] == 1) {
                    $this->generateResponse(array('code' => $this->errorCodes["INCONSISTENT TOTAL"]));
                    return;
                }
                
                $this->group_user->delete($params['id']);
                $group_cardinality = $organizerDetails['group_cardinality'];
                $this->organizer->id = $organizerDetails['id'];
                $this->organizer->saveField('group_cardinality', intval($group_cardinality)-1);
                $this->generateResponse(array('code' => $this->errorCodes["SUCCESS"]));
                break;

            case "view":
                $this->addPaymentURL($groupUserDetails, $this->clientId);
                $this->generateResponse(array('code' => $this->errorCodes["SUCCESS"], 'data' => $groupUserDetails));
                break;
            case "decrypt":
                $data = $this->decrypt_for_url($params['encrypted'], $this->getEncryptionKey());
                if (array_key_exists('group_user_id', $data)) {
                    $this->generateResponse(array('code'=>$this->errorCodes["SUCCESS"], 'data'=>$data));
                }
                break;
        }
    
    }

    
    private function group($params) {
    
        switch ($this->subAction) {
            case "add":
                    $organizer = $this->getOrganizerById($params['organizer_id']);
                    $gift = $this->getGiftById($organizer['gift_id']);
                    
                    if ($organizer['group_cardinality'] != 1) {
                    	// second call to add! invalid!!!
                    	$this->generateResponse(array('code' => $this->errorCodes["INVALID API CALL"]));
                    	return;
                    }
                    
                    $total = $gift['price'] - $organizer['share'];
                    
                    // check all other parameters also passed:
                    
                    $groupUserParams = $this->reqdParams('group_user', 'add');

                    unset($groupUserParams['organizer_id']);

                    $error = false;
                    for ($i = 1; $i <= $params['group_cardinality']; $i++) {
                        $userParams = $this->appendNumber($groupUserParams, $i);

                        if (!$this->checkParams($userParams)) {
                            $error = true;
                            break;
                        }
                        else {
                            $total -= $this->request->data['share'.$i];
                        }
                    }
                    if ($error) {
                        $this->generateResponse(array('code' => $this->errorCodes["MISSING PARAMETERS"]));
                        return;
                    }
                    if ($total != 0) {
                        $this->generateResponse(array('code' => $this->errorCodes["INCONSISTENT TOTAL"]));
                        return;
                    }
                    
                    $this->organizer->id = $params['organizer_id'];
                    $this->organizer->save(
                        array(
                            'group_cardinality' => intval($params['group_cardinality'])+1,
                        )
                    );
                    
                    $data = array();
                    $data['group_users'] = array();
                    $groupUserParams = $this->allPossibleParams('group_user', 'add');
                    unset($groupUserParams['organizer_id']);
                    
                    for ($i = 1; $i <= $params['group_cardinality']; $i++) {
                        $userParams = $this->appendNumber($groupUserParams, $i);

                        $user = $this->getParams($userParams);
                        $user = array_merge(array('organizer_id' => $params['organizer_id']), $user);
                        $this->group_user->create();
                        $this->group_user->save($user);
                        $user = array_merge(array('id' => $this->group_user->id), $user);
                        array_push($data['group_users'], $user);

                    }
                    $this->generateResponse(array('code' => $this->errorCodes["SUCCESS"], 'data' => $data));
                break;

            // Must specify all contributions: share<groupuser_id>
            case "edit":
                $organizerId = $params['organizer_id'];
                $groupGift = $this->getCompleteGroupGift($organizerId);
                
                $organizer = $this->getOrganizerDetails($groupGift);
                $gift = $this->getGiftDetails($groupGift);
                $groupUsers = $this->getGroupUsersDetails($groupGift);

                $total = $gift['price'] - $organizer['share'];
                $shares = array();
                $error = false;
                foreach($groupUsers as $user) {
                    if (!array_key_exists('share'.$user['id'], $this->request->data)) {
                        $error = true;
                        break;
                    }
                    else{
                        $shares[$user['id']] = $this->request->data['share'.$user['id']];
                        $total -= $shares[$user['id']];
                    }
                }
                if ($error) {
                    $this->generateResponse(array('code' => $this->errorCodes["MISSING PARAMETERS"]));
                    return;
                }
                if ($total != 0) {
                    $this->generateResponse(array('code' => $this->errorCodes["INCONSISTENT TOTAL"]));
                    return;
                }

                $ids = array_keys($shares);
                $group_user_data = array();
                foreach ($ids as $id) {
                    $this->group_user->id = $id;
                    $this->group_user->saveField('share', $shares[$id]);
                    array_push($group_user_data, array('id' => $id, 'share' => $shares[$id]));
                }
                $data = array('group_users' => $group_user_data);
                $this->generateResponse(array('code' => $this->errorCodes["SUCCESS"], 'data' => $data));
                break;

            case "view":
                $organizerId = $params['organizer_id'];
                $groupGift = $this->getCompleteGroupGift($organizerId);
                $groupUsers = $this->getGroupUsersDetails($groupGift);
                $users = array("group_users"=>$groupUsers);
                $this->generateResponse(array('code' => $this->errorCodes["SUCCESS"], 'data' => $users));
                break;
        }
    
    }
    
    
    
    private function sendMessage($params) {
        $users = array('organizer', 'group_users', 'recipient');
        $occassion_type = array('welcome', 'reminder', 'thanks_from_recipient');
        
        if (!in_array($params['to'], $users) || !in_array($params['email_type'], $occassion_type)) {
            $this->generateResponse(array('code' => $this->errorCodes["BAD PARAMETER"]));
            return;
        }
        
        $groupGift = $this->getCompleteGroupGift($params['organizer_id']);
        $organizerDetails = $this->getOrganizerDetails($groupGift);
        $recipientDetails = $this->getRecipientDetails($groupGift);
        $groupUsersDetails = $this->getGroupUsersDetails($groupGift);
        
        $message = (isset($params['message'])) ? $params['message'] : "";
        $fbAccessToken = (isset($params['fb_access_token'])) ? $params['fb_access_token'] : 0;
        $groupUserId = isset($params['group_user_id']) ? $params['group_user_id'] : 0;

        switch ($params['to']) {
            case "organizer":
                $sent = $this->send_email_organizer($fbAccessToken, $organizerDetails, $recipientDetails, $params['email_type'], $this->clientId, $message);
                break;
            case "group_users":
                $sent = $this->send_email_invitees($fbAccessToken, $organizerDetails, $recipientDetails, $groupUsersDetails, $params['email_type'], $this->clientId, $groupUserId, $message);
                break;
            case "recipient":
                $sent = $this->send_email_recipient($fbAccessToken, $organizerDetails, $recipientDetails, $groupUsersDetails, $params['email_type'], $this->clientId);
                break;
        }
           
        return $this->generateResponse(array('code' => $this->errorCodes["SUCCESS"], 'data'=>$sent));
    }
    
    
    public function index() {

    	$this->autoRender = false;
        //$this->request->data = $this->request->query; // #####

        $this->action = array_key_exists('action', $this->request->data) ? $this->request->data['action'] : null;
        $this->subAction = array_key_exists('subaction', $this->request->data) ? $this->request->data['subaction'] : null;

        if (!$this->authorize()) {
            $this->generateResponse(array('code' => $this->errorCodes["UNAUTHORIZED"]));
        }
        elseif ($this->action == null) {
            $this->generateResponse(array('code' => $this->errorCodes["MISSING ACTION PARAMETER"]));
        }
        elseif (!array_key_exists($this->action, $this->api)) {
            $this->generateResponse(array('code' => $this->errorCodes["UNKNOWN ACTION"]));
        }
        elseif ($this->subAction == null) {
            $this->generateResponse(array('code' => $this->errorCodes["MISSING SUBACTION PARAMETER"]));
        }
        elseif (!array_key_exists($this->subAction, $this->api[$this->action]['subApi'])) {
            $this->generateResponse(array('code' => $this->errorCodes["UNKNOWN SUBACTION"]));
        }
        elseif (!$this->checkParams($this->reqdParams($this->action, $this->subAction))){
            $this->generateResponse(array('code' => $this->errorCodes["MISSING PARAMETERS"]));
        }
        elseif (!$this->recordExists($this->reqdParams($this->action, $this->subAction))) {
            $this->generateResponse(array('code' => $this->errorCodes["RECORD NOT FOUND"]));
        }
        else {            
            $allPostedParams = $this->getParams($this->allPossibleParams($this->action, $this->subAction));
            $actionFunction = $this->api[$this->action]['functionName'];
            $this->$actionFunction($allPostedParams);
        }
    
    }

}

?>