<?php

class GetAnalyticsController extends AppController {

	public function index() {   

		$this->autoRender = false;

            
		if (array_key_exists("metric", $this->request->data) && array_key_exists("plot-type", $this->request->data)
            && array_key_exists("start-date", $this->request->data) && array_key_exists("end-date", $this->request->data)) {
            
            $metric = $this->request->data['metric'];
			$plotType = $this->request->data['plot-type'];
			$startDate = $this->request->data['start-date'];
			$endDate = $this->request->data['end-date'];
            
            $detailed = ($plotType == "detailed") ? 1 : 0;
            
            $res = $this->sendRequestToAPI(
                array(
                    'action'=>'analytics',
                    'subaction'=>'view',
                    'metric'=>$metric,
                    'start_date'=>$startDate,
                    'end_date'=>$endDate,
                    'detailed'=>$detailed
                )
            );
            
            echo json_encode($res);
		}
        else {
            echo "error";
        }
	}
}
?>