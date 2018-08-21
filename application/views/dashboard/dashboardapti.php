<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">Dashboard</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<?php foreach ($dailyreport as $getReport) { ?>
			<div class="col-md-4 cols-sm-12">
				<div class="box p-a">
					<div class="pull-left m-r">
						<i class="fa fa-car text-2x m-y-sm"></i>
					</div>
					<div class="clear">
					<div class="text-muted">Total Jarak Tempuh</div>
						<h4 class="m-a-0 text-md _600"><?php echo $getReport->total_km;?> KM</h4>
					</div>
				</div>
			</div>

			<div class="col-md-4 cols-sm-12">
				<div class="box p-a">
					<div class="pull-left m-r">
						<i class="fa fa-car text-2x m-y-sm"></i>
					</div>
					<div class="clear">
					<div class="text-muted">Total Viewer</div>
						<h4 class="m-a-0 text-md _600"><?php echo $getReport->total_viewer;?> </h4>
					</div>
				</div>
			</div>

			<div class="col-md-4 cols-sm-12">
				<div class="box p-a">
					<div class="pull-left m-r">
						<i class="fa fa-money text-2x m-y-sm"></i>
					</div>
					<div class="clear">
					<div class="text-muted">Akumulasi Biaya</div>
						<h4 class="m-a-0 text-md _600"><?php echo $getReport->total_saldo;?> IDR</h4>
					</div>
				</div>
			</div>
		<?php } ?>

		<div class="col-sm-12 col-md-12">
			<div class="box p-a">
				<div id="map_canvas" style="width: 100% !important; height: 500px !important"></div>
			</div>
		</div>

		<div class="col-md-12 col-sm-12">
			<div class="box">
				<table class="table table-striped b-t">
					<thead>
						<tr>
							<th>Tanggal</th>
							<th style="text-align: center;">Total KM</th>
							<th style="text-align: center;">Total Viewer</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php
					$totaljarak = 0;
					for($i = 0 ; $i< count($dailytask['tanggal']); $i++)
					{
					?>
						<tr>
							<td><?php echo @$dailytask['tanggal'][$i]; ?></td>
							<td style="text-align: center;"><?php echo @$dailytask['totalKm'][$i]; ?></td>
							<td style="text-align: center;"><?php echo @$dailytask['totalViewer'][$i]; ?></td>
							<td style="text-align: center;"><button class="btn btn-icon btn-rounded btn-info" data-id="<?php echo @$dailytask['id'][$i];?>"><i class="fa fa-search"></i></button></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
$(function() {
	drop();
	setInterval(function() {
		drop();
	}, 20000);
});

var neighborhoods = [];
setInterval(function() {
	$.ajax({
		type		: 'get',
		url 		: BASE_URL+'dashboard/mapAdmin',
		dataType	: 'json',
		success		: function(data) {
			var loc = [];
			$.each(data, function (index, personne) {
				loc.push(personne);
			});
			neighborhoods = loc ;
		},
		async: false
	});
}, 10000);

var setData = [];
$.ajax({
		type		: 'get',
		url 		: BASE_URL+'dashboard/mapAdmin',
		dataType	: 'json',
		success		: function(data) {
			var loc = [];
			$.each(data, function (index, personne) {
				loc.push(personne);
			});
			setData = loc ;
		},
		async: false
});	

var markers = [];
var map;

function initMap() {
	map = new google.maps.Map(document.getElementById('map_canvas'), {
		zoom: 11,
		center: {lat: -6.2664721, lng: 106.8440418},
		scrollwheel: false,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		disableDefaultUI: true
	});
	var trafficLayer = new google.maps.TrafficLayer();
	trafficLayer.setMap(map);
}

function drop() {
	clearMarkers();
	if(neighborhoods.length == '') {
		for (var i = 0; i < setData.length; i++) {
			addMarkerWithTimeout(setData[i], i * 200);
		}
	} else {
		for (var i = 0; i < neighborhoods.length; i++) {
			addMarkerWithTimeout(neighborhoods[i], i * 200);
		}
	}
}

function addMarkerWithTimeout(position, timeout) {
	var image = {
		url: BASE_URL+'assets/images/marker.png',
		size: new google.maps.Size(20, 32),
		origin: new google.maps.Point(0, 0),
		anchor: new google.maps.Point(0, 32)
	};

	window.setTimeout(function() {
		markers.push(new google.maps.Marker({
		// markers.push(new google.maps.TrafficLayer({
			position: {lat: parseFloat(position.lat), lng: parseFloat(position.lng)},
			icon: image,
			map: map,
			title: position.member_name
		}));
	}, timeout);
}

function clearMarkers() {
	for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(null);
	}
	markers = [];
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDfVHt_u3hEO6R5Gavi8XWcCYQkA3nNhik&callback=initMap" async defer></script>
