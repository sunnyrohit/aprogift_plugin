<?php

class FAQController extends AppController {

    public function index() {  
    	
        $this->layout = false;
        
        $logoSrc='http://static.peppercloset.com/frontend/peepercloset/default/images/logo.png';
        $this->set('logoSrc', $logoSrc);
		
    }

}

?>