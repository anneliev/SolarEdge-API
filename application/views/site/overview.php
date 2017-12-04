<?php

foreach($overviewObj as $key){
	echo '
  <div class="card" id="overview-card">
 		<div class="card-body"> 
	    <h4 class="card-title slim-title">Energi idag</h4>';
	    if($key->lastDayData->energy > 1000000){
	     	$lde = number_format($key->lastDayData->energy,2,',','.');
	     	echo'	<h4 class="card-text green-text" >'.round($lde,2).' MWh</h4>';
	    }else if($key->lastDayData->energy > 1000){
	     	$lde = number_format($key->lastDayData->energy,2,',','.');
	     	echo' <h4 class="card-text green-text" >'.round($lde,2).' kWh</h4>';
	    }else{
	     	echo'<h4 class="card-text green-text" >'.$key->lastDayData->energy.' wh</h4>';
	    }; 
	    echo '
	  </div>
	</div>
	<div class="card" id="overview-card">
 		<div class="card-body">
	    <h4 class="card-title slim-title">Energi månad</h4>';
	    if($key->lastMonthData->energy > 1000000){
	     	$lme = number_format($key->lastMonthData->energy,2,',','.');
	     	echo' <h4 class="card-text green-text">'.round($lme,2).' MWh</h4>';
	    }else if($key->lastMonthData->energy > 1000){
	     	$lme = number_format($key->lastMonthData->energy,2,',','.');
	    	echo' <h4 class="card-text green-text">'.round($lme,2).' kWh</h4>';
	    }else{
	     	echo' <h4 class="card-text green-text">'.$key->lastMonthData->energy.' wh</h4>';
	    }; 
	    echo '
	  </div>
	</div>
	<div class="card" id="overview-card">
 		<div class="card-body">
			<h4 class="card-title slim-title">Total energi</h4>';
	    if($key->lifeTimeData->energy > 1000000000){
	     	$ltd = number_format($key->lifeTimeData->energy,2,',','.');
	     	echo'	<h4 class="card-text green-text">'.round($ltd,2).' Gwh</h4> ';
	    }else if($key->lifeTimeData->energy > 1000000){
	     	$ltd = number_format($key->lifeTimeData->energy,2,',','.');
	     	echo' <h4 class="card-text green-text">'.round($ltd,2).' MWh</h4> ';
	    }else if($key->lifeTimeData->energy > 1000){
	     	$ltd = number_format($key->lifeTimeData->energy,2,',','.');
      	echo' <h4 class="card-text green-text">'.round($ltd,2).' kWh</h4> ';
      }else{
	     	echo'	<h4 class="card-text green-text">'.$key->lifeTimeData->energy.' wh</h4> ';
	    }; 
	    echo'
	  </div>
	</div>
  <div class="card" id="overview-card">
		<div class="card-body">
      <h4 class="card-title slim-title">Total inkomst</h4>
      <h4 class="card-text green-text">'.round($key->lifeTimeData->revenue).' kr</h4>
    </div>
  </div>
  ';
}


