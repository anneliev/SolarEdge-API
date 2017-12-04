<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function __construct()
  {
    parent::__construct();
    $this->load->library('session');
  }

	public function index()
	{
		$this->session->set_userdata('api', 'https://monitoringapi.solaredge.com/site/');
		$this->session->set_userdata('site_id', '******');
		$this->session->set_userdata('api_key', '******');

		$this->load->view('site/start');
	}

/*----------Get the power values from 15 minutes ago----------*/
	public function get_power_details_current()
	{
		$today = new DateTime();
		
		$interval = new DateInterval('PT15M');
		$quarter_ago = $today->sub($interval);
		$today = new DateTime();

		$startTime = $quarter_ago->format('Y-m-d H:i:s');
		$endTime = $today->format('Y-m-d H:i:s');
		
		$url = $this->session->userdata('api') . $this->session->userdata('site_id');
    $data = array(
      'startTime' => $startTime,
      'endTime' => $startTime,
      'api_key' => $this->session->userdata('api_key'),
    );
 		
    $ch = curl_init($url . '/powerDetails.json?' . http_build_query($data) );
    curl_setopt($ch, CURLOPT_HTTPHEADER,array("Content-type: application/x-www-form-urlencoded"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);       
    curl_close($ch);

    $powerDetailsObj = json_decode($output);

    foreach ($powerDetailsObj as $key) {
			$meters = $key->meters;
			$keys = [
				'Production',
				'Consumption',
				'SelfConsumption',
				'FeedIn',
				'Purchased',
			];

			usort($meters, function($a, $b) use($keys){
				$a = array_search($a->type, $keys);
				$b = array_search($b->type, $keys);
				if($a == $b) {
					return 0;
				}
				return $a > $b ? 1 : -1;
			});

			$systemProductionCurrent = $meters[0];
			$consumptionCurrent = $meters[1];
			$selfConsumptionCurrent = $meters[2];
			$feedInCurrent = $meters[3];
			$purchasedCurrent = $meters[4];
			
			$purchasedMore = 0;
			$soldMore = 0;
      /*If value is empty, set it as 0*/
			if(!isset($purchasedCurrent->values[0]->value)){
				$purchasedCurrent->values[0]->value = 0;
			}
			if(!isset($feedInCurrent->values[0]->value)){
				$feedInCurrent->values[0]->value = 0;
			}
			if($purchasedCurrent->values[0]->value > $feedInCurrent->values[0]->value){
				$purchasedMore = $purchasedCurrent->values[0]->value - $feedInCurrent->values[0]->value;
			}else if($feedInCurrent->values[0]->value > $purchasedCurrent->values[0]->value){
				$soldMore = $feedInCurrent->values[0]->value - $purchasedCurrent->values[0]->value;
			}
			if(isset($systemProductionCurrent->values[0]->value)){
				$currentPower = round($systemProductionCurrent->values[0]->value, 2);
			}else{
				$currentPower = 0;
			}
			if(isset($consumptionCurrent->values[0]->value)){
				$currentConsumption = round($consumptionCurrent->values[0]->value, 2);
			}else{
				$currentConsumption = 0;
			}
			
      $this->load->view('site/power_details_current', array(
      	'currentPower' => $currentPower,
      	'currentConsumption' => $currentConsumption,
      	'purchasedMore' => $purchasedMore,
      	'soldMore' => $soldMore,
      ));
      
		}  
	}
/*----------Get data from last week, inserts into morris chart----------*/	
	public function get_power_details_1week()
	{
		$today = new DateTime();
		$interval = new DateInterval('P6D');
		$week_ago = $today->sub($interval);
		$today = new DateTime();

		$timeUnit = 'QUARTER_OF_AN_HOUR';
		$startTime = $week_ago->format('Y-m-d');
		$startTime .= ' 00:00:00';
		$endTime = $today->format('Y-m-d');
		$endTime .= ' 23:59:59';
		$url = $this->session->userdata('api') . $this->session->userdata('site_id');
    $data = array(
      'timeUnit' => $timeUnit,
      'startTime' => $startTime,
      'endTime' => $endTime,
      'api_key' => $this->session->userdata('api_key'),
    );
    /*Get the Power values for the last week*/ 		
    $ch = curl_init($url . '/powerDetails.json?' . http_build_query($data) );
    curl_setopt($ch, CURLOPT_HTTPHEADER,array("Content-type: application/x-www-form-urlencoded"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);       
    curl_close($ch);
    $powerDetailsObj = json_decode($output);
    foreach ($powerDetailsObj as $key) {
			$meters = $key->meters;
			$keys = [
				'Production',
				'Consumption',
				'SelfConsumption',
				'FeedIn',
				'Purchased',	
			];

			usort($meters, function($a, $b) use($keys){
				$a = array_search($a->type, $keys);
				$b = array_search($b->type, $keys);
				if($a == $b) {
					return 0;
				}
				return $a > $b ? 1 : -1;
			});

			$systemProduction1weekPower = $meters[0];
			$consumption1weekPower = $meters[1];
			$selfConsumption1weekPower = $meters[2];
			$feedIn1weekPower = $meters[3];
			$purchased1weekPower = $meters[4];	
		}  
    /*Get the Energy values for the last week*/
		$ch2 = curl_init($url . '/energyDetails.json?' . http_build_query($data) );
    curl_setopt($ch2, CURLOPT_HTTPHEADER,array("Content-type: application/x-www-form-urlencoded"));
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
    $output2 = curl_exec($ch2);       
    curl_close($ch2);
    $energyDetailsObj = json_decode($output2);
    //var_dump($powerDetailsObj);
    foreach ($energyDetailsObj as $key) {
			$meters = $key->meters;
			$keys = [
				'Production',
				'Consumption',
				'SelfConsumption',
				'FeedIn',
				'Purchased',	
			];

			usort($meters, function($a, $b) use($keys){
				$a = array_search($a->type, $keys);
				$b = array_search($b->type, $keys);
				if($a == $b) {
					return 0;
				}
				return $a > $b ? 1 : -1;
			});

			$solarProduction1weekEnergy = $meters[0];
			$consumption1weekEnergy = $meters[1];
			$selfConsumption1weekEnergy = $meters[2];
			$feedIn1weekEnergy = $meters[3];
			$purchased1weekEnergy = $meters[4];	
		} 
    /*Change the propertyname of value in the power/energy objects*/
		foreach($systemProduction1weekPower->values as $key){
			if(empty($key->value)){
				$key->value = 0;
			}else{
				$key->value = intval($key->value);
			}
			$key->systemPower = $key->value;
			unset($key->value);
		}
		foreach($solarProduction1weekEnergy->values as $key){
			if(empty($key->value)){
				$key->value = 0;
			}else{
				$key->value = intval($key->value);
			}
			$key->solarEnergy = $key->value;
			unset($key->value);
		}
		foreach($consumption1weekEnergy->values as $key){
			if(empty($key->value)){
				$key->value = 0;
			}else{
				$key->value = intval($key->value);
			}
			$key->consumption = $key->value;
			unset($key->value);
		}
		foreach($selfConsumption1weekEnergy->values as $key){
			if(empty($key->value)){
				$key->value = 0;
			}else{
				$key->value = intval($key->value);
			}
			$key->selfConsumption = $key->value;
			unset($key->value);
		}

		$systemObj = $systemProduction1weekPower->values;
		$solarObj = $solarProduction1weekEnergy->values;
		$consumptionObj = $consumption1weekEnergy->values;
		$selfConsumptionObj = $selfConsumption1weekEnergy->values;
    /*Merge all four arrays*/
		$mergedArr = [];
		foreach ($systemObj as $key => $value) {
			if(!isset($mergedArr[$value->date])){
			  $mergedArr[$value->date] = new stdClass();
				$mergedArr[$value->date]->date = $value->date;
			}
			$mergedArr[$value->date]->systemPower = $value->systemPower;
		}
		foreach ($solarObj as $key => $value) {
			if(!isset($mergedArr[$value->date])){
			  $mergedArr[$value->date] = new stdClass();
				$mergedArr[$value->date]->date = $value->date;
			}
			$mergedArr[$value->date]->solarEnergy = $value->solarEnergy;
		}
		foreach ($consumptionObj as $key => $value) {
			if(!isset($mergedArr[$value->date])){
				$mergedArr[$value->date] = new stdClass();
				$mergedArr[$value->date]->date = $value->date;
			}
			$mergedArr[$value->date]->consumption = $value->consumption;
		}
		foreach ($selfConsumptionObj as $key => $value) {
			if(!isset($mergedArr[$value->date])){
				$mergedArr[$value->date] = new stdClass();
				$mergedArr[$value->date]->date = $value->date;
			}
			$mergedArr[$value->date]->selfConsumption = $value->selfConsumption;
		}

		$dataObj = array_values($mergedArr);
		echo json_encode($dataObj);
	}
/*----------Get overview data----------*/	
	public function get_overview()
	{
		$url = $this->session->userdata('api') . $this->session->userdata('site_id');
    $data = array(
     'api_key' => $this->session->userdata('api_key'),
    );
 
    $ch = curl_init($url . '/overview.json?' . http_build_query($data) );
    curl_setopt($ch, CURLOPT_HTTPHEADER,array("Content-type: application/x-www-form-urlencoded"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);       
    curl_close($ch);

    $overviewObj = json_encode($output);
    echo $overviewObj;
	}
/*----------Display overview data----------*/	
	public function display_overview(){
		$overviewObj = $this->input->post('response');

		$this->load->view('site/overview', array(
 			'overviewObj' => json_decode($overviewObj)
    ));
	}	
/*----------Get data on enviromental saves and display them----------*/
	public function get_enviroment_data()
	{
		/*Data from get_overview()*/
		$overviewObj = json_decode($this->input->post('response'));
    foreach ($overviewObj as $key) {
      $lifeTimeData = $key->lifeTimeData->energy;
    }
    
    $co2Saved = $lifeTimeData * 0.392;
    //0.392 data from https://monitoringpublic.solaredge.com/solaredge-web/p/site/public?name=Ala&locale=en_GB#/dashboard . 
    //Co2 Emission saved / Lifetime energy
    $co2Saved = $co2Saved / 1000;
    $data = $co2Saved;
    $co2Saved = number_format($co2Saved,2,',',' ');

    $treesPlanted = $data / 299.25;
    //299.25 data from https://monitoringpublic.solaredge.com/solaredge-web/p/site/public?name=Ala&locale=en_GB#/dashboard . 
    //Lifetime energy / Eqvivalent Trees Planted
    $treesPlanted = number_format($treesPlanted, 2,',',',');

    $lightbulbs = $data *7.73;
    //7.73 data from https://www.solaredge.com/sites/default/files/solaredge-monitoring-portal-user-guide.pdf  
    //Light Bulbs Powered / Co2 Emission Saved
    $lightbulbs = round($lightbulbs, 2);
    $lightbulbs = number_format($lightbulbs,2,',',' ');
 
    $this->load->view('site/enviroment', array(
 			'co2Saved' => $co2Saved,
 			'treesPlanted' => $treesPlanted,
 			'lightbulbs' => $lightbulbs
    ));
	}

}