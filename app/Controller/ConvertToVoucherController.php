<?php

class ConvertToVoucherController extends AppController {

    public function index() {
    
        $this->autoRender = false;
    	// id type = organizer ID
        if (!array_key_exists("data", $this->request->query))
            $this->putError("data not found in URL", $dieBool=true);
        
        $organizerId = $this->decryptFromAPI($this->request->query['data'], 'organizer');
        
        $groupGift = $this->sendRequestToAPI(
            array(
                'action'=>'full_group_gift',
                'subaction'=>'convertToVoucher',
                'organizer_id'=>$organizerId,
                'fb_access_token'=>$this->request->data['accessToken']
            )
        );

        // TODO: get new redirect URL to new v1c tracker, and return it
        // done

        $res = array(
            'code'=>'0',
            'data' => array(
                'redirectURL'=>$groupGift['redirectURL']
            )
        );
        echo json_encode($res);
  }
}
?>