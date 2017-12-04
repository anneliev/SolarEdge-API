<?php

$purchasedMore = round($purchasedMore, 2);
$soldMore = round($soldMore, 2);
echo '

<div class="card">
	<div class="card-body">
		<h4 class="card-title">Produktion</h4>';
		if($currentPower !== 0){
		  echo '<img class="img-fluid img-current" src="/../images/panel-color.png"/>';
		}else if($currentPower === 0){
			echo '<img class="img-fluid img-current" src="/../images/panel-black.png"/>';
		}
		if($currentPower > 1000){
      $cP = number_format($currentPower,2,',','.');
      	echo'<h5 class="card-text slim-title">'.round($cP,2).' kW</h5> ';
    }else{
     	echo'<h5 class="card-text slim-title">'.$currentPower.' W</h5>';
    };
		echo '
	</div>
</div>
<div class="card">
	<div class="card-body">
		<h4 class="card-title">Förbrukning</h4>';
		if($currentConsumption !== 0){
		  echo '<img class="img-fluid img-current" src="/../images/house-green-color.png"/>';
		}else if($currentConsumption === 0){
			echo '<img class="img-fluid img-current" src="/../images/house-green-black.png"/>';
		};
		if($currentConsumption > 1000){
     	$cC = number_format($currentConsumption,2,',','.');
     	echo' <h5 class="card-text slim-title">'.round($cC,2).' kW</h5>';
    }else{
     	echo'<h5 class="card-text slim-title">'.$currentConsumption.' W</h5>';
    };
		echo '
	</div>
</div>
<div class="card">
	<div class="card-body">';
		if($purchasedMore !== 0){
  	  echo '
		  <h4 class="card-title">Köper från nät</h4>
		  <img class="img-fluid img-current" src="/../images/cord-black.png"/>
		  ';
		}else if($soldMore !== 0){
		  echo '
		  <h4 class="card-title">Säljer till nät</h4>
		  <img class="img-fluid img-current" src="/../images/cord-color.png"/>
		  ';
		};
		if($purchasedMore !== 0){
		 	if($purchasedMore > 1000){
	     	$pM = number_format($purchasedMore,2,',','.');
	     	echo'<h5 class="card-text slim-title">'.round($pM,2).' kW</h5>';
	    }else{
	     	echo'<h5 class="card-text slim-title">'.$purchasedMore.' W</h5>';
	    };
	  }else if($soldMore !== 0){
	  	if($soldMore > 1000){
	     	$pM = number_format($soldMore,2,',','.');
	     	echo'<h5 class="card-text slim-title">'.round($sM,2).' kW</h5>';
	    }else{
	     	echo'<h5 class="card-text slim-title">'.$soldMore.' W</h5>';
	    };
	  }
	echo '
	</div>
</div>
';
