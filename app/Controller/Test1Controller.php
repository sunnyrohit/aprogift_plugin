<?php

class Test1Controller extends AppController {

    public function index() {  
    	
        $msg = "Test 1 => Success";
        $this->set('msg', $msg);
		
    }

}

?>