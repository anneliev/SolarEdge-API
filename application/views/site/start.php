<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SolarEdge API</title>
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/../css/main.css">
	
	<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.7/raphael.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
 
</head>
<body>
	<div class="container-fluid main-content">
		<div class="row">
			<div class="col-6">
				<h4>Just nu</h4>
				<div class="row card-group" id="power-details-current">
					<!-- ******Card group with current detailed info****** -->
				</div>
				<h4>Senaste veckan</h4>	
			  <div class="row card">
					<div class="row" id="power-details-week">
		  	  	<!-- ******Card with weekly detailed info****** -->
			  	</div>
			  	<div class="list-labels">
				  	<ul class="list-inline text-center">      
		          <li class="list-inline-item">
		            <h5 class="forbruknings-punkt"> &#11044;</h5>
		          </li>
		          <li class="list-inline-item">
		            <p>Förbrukning W</p>
		          </li>
		          <li class="list-inline-item">
		            <h5 class ="effekt-punkt"> &#11044;</h5>
		          </li>
		          <li class="list-inline-item">
		            <p>Effektproduktion W</p>
		          </li>
		          <li class="list-inline-item">
		            <h5 class="egenforbruknings-punkt"> &#11044;</h5>
		          </li>
		          <li class="list-inline-item">
		            <p>Energiproduktion wh</p>
		          </li>
		          <li class="list-inline-item">
		            <h5 class="energi-punkt"> &#11044;</h5>
		          </li>
		          <li class="list-inline-item">
		            <p>Egen förbrukning W</p>
		          </li>
	          </ul>
          </div>
				</div>
			</div>

			<div class="col-6" id="right-column" > 	
			  <h4>Översikt</h4>
				<div class="row card-group" id="site-overview">
					<!-- ******Card group with site overview info****** -->
			  </div>
			  
			  <div class="row">

				  <div class=" col-6"> 
				 		<h4>Miljöfördelar</h4>
				 		<div id="site-enviroment" >
    				  <!-- ******Cards with enviromental info****** -->
            </div>
				 	</div>

				  <div class=" col-6" id="right-column">  
				  	<h4>Väder</h4>
				  	<div id="site-weather" > 
				  	  <div class="row card>
				  	  	<div class="card">
				  		  	<!-- ******Card with weather info****** -->	
				  		  	<h4>Väderinfo</h4>
				  		  </div>
				  		</div>
				  	 </div>
				  </div>	
				<div class="row" id="site-logo">
					<img class="img" src="/../images/el-av-sol-logga-min.png" />
			  </div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>

<script type="text/javascript">

$(function(){
	load_overview();
	load_power_details_week();
	load_power_details_current();
});

function load_overview(){
	$.ajax({
		url: '/index.php/main/get_overview',
		type: 'POST',
		dataType : 'json',
		success: function(response){
			$('#site-overview').load('/index.php/main/display_overview', {
				response : response
			}),
			$('#site-enviroment').load('/index.php/main/get_enviroment_data', {
				response : response
			});
		},
		error: function(error){
			console.log(error);
		}
	});
}

function load_power_details_week(){
	$.ajax({
		url: '/index.php/main/get_power_details_1week',
		type: 'GET',
		dataType : 'json',
		success: function(response){
			load_chart(response);
		},
		error: function(error){
			console.log(error);
		}
	});
}
function load_power_details_current(){
	$('#power-details-current').load('/index.php/main/get_power_details_current');
}

function load_chart($graphObj){
	var graphData = $graphObj;
	var graph = new Morris.Area({
    element: 'power-details-week',
    data: graphData,
    lineColors: ['#2f3d4a', '#009efb', '#55ce63', '#00897b'],
    xkey: 'date',
    ykeys: ['systemPower', 'consumption', 'solarEnergy','selfConsumption'],
    labels: ['Effektproduktion', 'Förbrukning', 'Egen förbrukning', 'Energiproduktion'],
    xLabels : "day",
    pointSize: 0,
    lineWidth: 0,
    resize:true,
    fillOpacity: 0.8,
    behaveLikeLine: true,
    gridLineColor: '#e0e0e0',
    hideHover: 'auto'
  });
}

function load_enviroment_data(){
  $.ajax({
		url: '/index.php/main/get_overview',
		type: 'POST',
		success: function(response){
			$('#site-enviroment').load('/index.php/main/get_enviroment_data', {
				obj : response
			});
		},
		error: function(error){
			console.log(error);
		}
	});
}

</script>