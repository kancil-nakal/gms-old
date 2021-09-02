<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">Manage Client</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<div class="box">
			<div class="box-body">
				<div class="table-responsive">
					<div id="toolbarMember">
						<a href="<?php echo base_url('client/form'); ?>" class="btn btn-block btn-success">
							<i class="glyphicon glyphicon-plus"></i> New Client
						</a>
					</div>
					<table class="table table-bordered" id="listClient"></table>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="frm_detailReport" class="modal fade black-overlay" data-backdrop="false">
	<div class="modal-dialog modal-lg" style="width: 1200px;">
		<div class="modal-content">
			<div class="modal-body p-lg">
				<div class="row">
					<div class="col-md-3 cols-sm-6">
						<div class="box p-a">
							<div class="pull-left m-r">
								<i class="fa fa-car text-2x m-y-sm"></i>
							</div>
							<div class="clear">
							<div class="text-muted">Total Jarak Tempuh</div>
								<h4 class="m-a-0 text-md _600" id="jarak"></h4>
							</div>
						</div>
					</div>

					<div class="col-md-3 cols-sm-6">
						<div class="box p-a">
							<div class="pull-left m-r">
								<i class="fa fa-car text-2x m-y-sm"></i>
							</div>
							<div class="clear">
							<div class="text-muted">Total Viewer</div>
								<h4 class="m-a-0 text-md _600" id="viewer"></h4>
							</div>
						</div>
					</div>

					<div class="col-md-3 cols-sm-6">
						<div class="box p-a">
							<div class="pull-left m-r">
								<i class="fa fa-money text-2x m-y-sm"></i>
							</div>
							<div class="clear">
							<div class="text-muted">Akumulasi Biaya</div>
								<h4 class="m-a-0 text-md _600" id="akumulasi"></h4>
							</div>
						</div>
					</div>

					<div class="col-md-3 cols-sm-6">
						<div class="box p-a">
							<div class="pull-left m-r">
								<i class="fa fa-money text-2x m-y-sm"></i>
							</div>
							<div class="clear">
							<div class="text-muted">Total Saldo</div>
								<h4 class="m-a-0 text-md _600" id="sisa"></h4>
							</div>
						</div>
					</div>

					<div class="col-xs-12 col-sm-6">
						<div class="table-responsive">
							<table class="table table-bordered" id="getArea"></table>
						</div>
					</div>

					<div class="col-xs-12 col-sm-6">
						<div class="table-responsive">
							<table class="table table-bordered" id="getDetail"></table>
						</div>
					</div>

					<div class="col-xs-12 col-sm-12">
						<div id="map" style="width: 100% !important; height: 500px;"></div>
					</div>
				</div>
			</div>

			<div class="dker modal-footer">
				<button type="button" class="btn btn-fw danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div id="frm_detailDriver" class="modal fade black-overlay" data-backdrop="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body p-lg" style="height: 420px;">
				<div id="map_canvas" style="width: 100% !important; height: 100% !important"></div>
			</div>

			<div class="dker modal-footer">
				<button type="button" class="btn btn-fw danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<input type="hidden" id="idClient" value="0"></input>
<script type="text/javascript" src="<?= base_url() ?>scripts/apps/client.js?1"></script>

<script type="text/javascript">
var clientID 		= $("#idClient").val();
var neighborhoods 	= [];
var markers 		= [];
var map;

function getMap() {
	$.ajax({
		type		: 'get',
		url 		: BASE_URL+'gmap/map/'+$("#idClient").val(),
		dataType	: 'json',
		success		: function(result) {
			var loc = [];
			$.each(result, function (index, personne) {
				loc.push(personne);
			});
			neighborhoods = loc ;
		},
		async: false
	});
	drop(neighborhoods);
}

if( $("#idClient").val() != 0) {
	socket.on('broadcast-region', function(data) {
		$.ajax({
			type		: 'get',
			url 		: BASE_URL+'client/map/'+$("#idClient").val(),
			dataType	: 'json',
			success		: function(result) {
				var loc = [];
				$.each(result, function (index, personne) {
					loc.push(personne);
				});
				neighborhoods = loc ;
			},
			async: false
		});
		drop(neighborhoods);
	});
}

function initMap() {
	map = new google.maps.Map(document.getElementById('map'), {
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
			position: {lat: parseFloat(position.lat), lng: parseFloat(position.lng)},
			title: position.member_name,
			icon: image,
			map: map
            // animation: google.maps.Animation.DROP
            // animation: google.maps.Animation.BOUNCE
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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDfVHt_u3hEO6R5Gavi8XWcCYQkA3nNhik&callback=initMap"></script>
