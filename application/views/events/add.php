<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">New Events</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<form action="<?php echo base_url('events/save'); ?>" method="post" enctype="multipart/form-data">
			<div class="box">
				<div class="box-body">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group row">
								<label class="col-sm-3 form-control-label"><b>Event Name</b></label>
								<div class="col-sm-9">
									<input type="text" class="form-control" placeholder="Enter Event Name" name="event_name" id="event_name" required>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 form-control-label"><b>Active</b></label>
								<div class="col-sm-9">
									<div class="switch-toggle switch-2 well" style="margin-bottom:0px !important">
										<input id="Y" name="status" type="radio" checked value="0">
										<label for="Y" onclick="">Yes</label>
										<input id="N" name="status" type="radio" value="1">
										<label for="N" onclick="">No</label>
										<a class="btn btn-primary"></a>
									</div>
								</div>
							</div>
						</div>

						<div class="col-sm-6">
							<div class="form-group row">
								<label class="col-sm-3 form-control-label"><b>Start</b></label>
								<div class="col-sm-9" id="sandbox-container">
									<div class="input-group date">
										<input type="text" class="form-control" placeholder="Enter End Date" name="date_start">
										<span class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 form-control-label"><b>End</b></label>
								<div class="col-sm-9" id="sandbox-container">
									<div class="input-group date">
										<input type="text" class="form-control" placeholder="Enter End Date" name="date_end">
										<span class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</span>
									</div>
								</div>
							</div>
						</div>

						<div class="col-sm-12 col-md-12" style="margin-top: 10px;">
							<input id="pac-input" class="controls form-control" type="text" placeholder="Search Box" name="search_name">
							<div id="pac-inputs" class="input-group m-b">
								<input id="radius" class="controls form-control" type="number" name="radius_meter" placeholder="Radius" onclick="setRadius()" onkeyup="setRadius();" readonly>
								<span class="input-group-addon"><b>Meter</b></span>
							</div>
							<div id="map_canvas" style="width: 100% !important; height: 500px !important"></div>
						</div>

						<div class="col-sm-3">
							<input type="text" class="form-control" name="lat" id="lat">
						</div>
						<div class="col-sm-3">
							<input type="text" class="form-control" name="lng" id="lng">
						</div>
						<div class="col-sm-3">
							<input type="hidden" class="form-control" name="south" id="south">
						</div>
						<div class="col-sm-3">
							<input type="hidden" class="form-control" name="west" id="west">
						</div>
						<div class="col-sm-3">
							<input type="hidden" class="form-control" name="north" id="north">
						</div>
						<div class="col-sm-3">
							<input type="hidden" class="form-control" name="east" id="east">
						</div>
					</div>
				</div>

				<div class="dker p-a text-left">
					<input type="hidden" name="id" value="0"></input>
					<button type="submit" class="btn btn-fw info">Submit</button>
					<a href="<?php echo base_url('events'); ?>" class="btn btn-fw danger">Cancel</a>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
var cityCircle;
$(function() {
	$("#event_name").focus();
	$("#sandbox-container .input-group.date").datepicker({
		autoclose: true,
		todayHighlight: true
	});
});

function setRadius() {
	var radius = $("#radius").val();
	cityCircle.setRadius( parseFloat(radius) );
}

function initAutocomplete() {
	var map = new google.maps.Map(document.getElementById('map_canvas'), {
		center: {lat: -6.7814026, lng: 107.5719325},
		zoom: 8,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		fullscreenControl: true,
		scrollwheel: false,
		draggable: false,
		disableDefaultUI: true
	});

	var input = document.getElementById('pac-input');
	var inputs = document.getElementById('pac-inputs');
	var searchBox = new google.maps.places.SearchBox(input);
	map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
	map.controls[google.maps.ControlPosition.TOP_LEFT].push(inputs);

	map.addListener('bounds_changed', function() {
		searchBox.setBounds(map.getBounds());
	});

	map.addListener('dblclick', function(e) {
		cityCircle.setMap(null);
		cityCircle = new google.maps.Circle({
			strokeColor: '#FF0000',
			strokeOpacity: 0.8,
			strokeWeight: 2,
			fillColor: '#FF0000',
			fillOpacity: 0.35,
			map: map,
			center: e.latLng,
			radius: parseFloat($("#radius").val()), // in Meter
			draggable: true,
			editable: true
		});
		map.panTo(e.latLng);
		var clickCircle = JSON.parse(JSON.stringify(e.latLng));
		$("#lat").val(clickCircle.lat);
		$("#lng").val(clickCircle.lng);

		var cirLats = JSON.parse(JSON.stringify(cityCircle.getBounds()));
		$("#south").val(cirLats.south);
		$("#west").val(cirLats.west);
		$("#north").val(cirLats.north);
		$("#east").val(cirLats.east);

		cityCircle.addListener('radius_changed', function() {
			var xRadius = JSON.parse(JSON.stringify(cityCircle.getBounds()));
			$("#south").val(xRadius.south);
			$("#west").val(xRadius.west);
			$("#north").val(xRadius.north);
			$("#east").val(xRadius.east);
			$("#radius").val(parseFloat(cityCircle.radius.toFixed(2)));
		});

		cityCircle.addListener('drag', function() {
			var newLats = JSON.parse(JSON.stringify(cityCircle.getBounds()));
			$("#south").val(newLats.south);
			$("#west").val(newLats.west);
			$("#north").val(newLats.north);
			$("#east").val(newLats.east);

			var clickCircle2 = JSON.parse(JSON.stringify(cityCircle.center));
			$("#lat").val(clickCircle2.lat);
			$("#lng").val(clickCircle2.lng);
		});
	});

	var markers = [];
	searchBox.addListener('places_changed', function() {
		var places = searchBox.getPlaces();
		if (places.length == 0) {
			return;
		}

		markers.forEach(function(marker) {
			marker.setMap(null);
		});
		markers = [];

		var bounds = new google.maps.LatLngBounds();
		var lat;
		var lng;
		places.forEach(function(place) {
			var obj = JSON.parse(JSON.stringify(place.geometry.location));
			$("#radius").prop("readonly", false);
			$("#radius").val('500');
			if (!place.geometry) {
				console.log("Returned place contains no geometry");
				return;
			}

			if (place.geometry.viewport) {
				bounds.union(place.geometry.viewport);
			} else {
				bounds.extend(place.geometry.location);
			}
			map.setCenter( {lat:obj.lat, lng:obj.lng} );
			$("#lat").val(obj.lat);
			$("#lng").val(obj.lng);
			cityCircle = new google.maps.Circle({
				strokeColor: '#FF0000',
				strokeOpacity: 0.8,
				strokeWeight: 2,
				fillColor: '#FF0000',
				fillOpacity: 0.35,
				map: map,
				center: {lat: obj.lat, lng: obj.lng },
				radius: parseFloat($("#radius").val()), // in Meter
				draggable: true,
				editable: true
			});
			var cirLat = JSON.parse(JSON.stringify(cityCircle.getBounds()));
			$("#south").val(cirLat.south);
			$("#west").val(cirLat.west);
			$("#north").val(cirLat.north);
			$("#east").val(cirLat.east);

			cityCircle.addListener('radius_changed', function() {
				var xRadius1 = JSON.parse(JSON.stringify(cityCircle.getBounds()));
				$("#south").val(xRadius1.south);
				$("#west").val(xRadius1.west);
				$("#north").val(xRadius1.north);
				$("#east").val(xRadius1.east);
				$("#radius").val(parseFloat(cityCircle.radius.toFixed(2)));
			});

			cityCircle.addListener('drag', function() {
				var newLat = JSON.parse(JSON.stringify(cityCircle.getBounds()));
				$("#south").val(newLat.south);
				$("#west").val(newLat.west);
				$("#north").val(newLat.north);
				$("#east").val(newLat.east);

				var clickCircle1 = JSON.parse(JSON.stringify(cityCircle.center));
				$("#lat").val(clickCircle1.lat);
				$("#lng").val(clickCircle1.lng);
			});
		});
		map.setOptions({ scrollwheel: true, draggable: true });
		map.setZoom(14);
	});
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDfVHt_u3hEO6R5Gavi8XWcCYQkA3nNhik&libraries=places&callback=initAutocomplete" async defer></script>