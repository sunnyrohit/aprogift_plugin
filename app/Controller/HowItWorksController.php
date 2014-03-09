<?php

class HowItWorksController extends AppController {

    public function index() {  
    
        $this->layout = false;
        
        $imgSrc='img/How-it-works.jpg';
        $this->set('imgSrc', $imgSrc);
		
    }

}

?>