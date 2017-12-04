<?php
echo '<pre>';
var_dump($summaryObj);
echo '</pre>';
foreach ($summaryObj as $key) {
	echo '
	<div>
		<p>Name: '.$key->name.'</p>
		<p>Location: '.$key->location->country.', '.$key->location->city.'</p>
		<p>Installed: '.$key->installationDate.'</p>
		<p>Last updated: '.$key->lastUpdateTime.'</p>
		<p>Peak power: '.$key->peakPower.' kW</p>
	</div>
	';
}
