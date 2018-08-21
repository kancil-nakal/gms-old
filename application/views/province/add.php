<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">New Province</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<form action="<?php echo base_url('province/save'); ?>" method="post">
			<div class="box">
				<div class="box-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group row">
								<label class="col-sm-2 form-control-label"><b>Name</b></label>
								<div class="col-sm-4">
									<input type="text" class="form-control" placeholder="Enter Brand" name="propinsi_name" id="propinsi_name" onkeyup="cekProvince();" required>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label"><b>Active</b></label>
								<div class="col-sm-4">
									<div class="switch-toggle switch-2 well" style="margin-bottom:0px !important">
										<input id="Y" name="status" type="radio" checked value="0">
										<label for="Y" onclick="">Yes</label>
										<input id="N" name="status" type="radio" value="1">
										<label for="N" onclick="">No</label>
										<a class="btn btn-primary"></a>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-12">
									<input id="pac-inputs1" class="controls form-control" type="text" placeholder="Search Box" style="width: 50%;">
									<div id="map_canvas" style="width: 100% !important; height: 400px !important"></div>
								</div>
							</div>
						</div>
					</div>			
				</div>

				<div class="dker p-a text-left">
					<input type="hidden" name="lat" id="lat"></input>
					<input type="hidden" name="lng" id="lng"></input>
					<input type="hidden" name="zoom" id="zoom"></input>
					<input type="hidden" name="id" value="0"></input>
					<button type="submit" class="btn btn-fw info">Submit</button>
					<a href="<?php echo base_url('province'); ?>" class="btn btn-fw danger">Cancel</a>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
$(function() {
	$("#propinsi_name").focus();
});

function cekProvince() {
	var propinsi_name = $("#propinsi_name").val();
	$.ajax({
		type: 'get',
		url: BASE_URL+'province/getprovince/'+propinsi_name,
		dataType: 'json',
		success: function(data) {
			if (data == 1) {
				swal({ title: "Error", text: "Propinsi <b>" + propinsi_name.toUpperCase() + "</b> sudah tersedia", html: true, timer: 2000, type: "error", showConfirmButton: false });
			}
		}
	});
}

function initAutocomplete() {
	var map = new google.maps.Map(document.getElementById('map_canvas'), {
		center: {lat: -6.7814026, lng: 107.5719325},
		zoom: 8,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		fullscreenControl: true
	});

	var input = document.getElementById('pac-inputs1');
	var searchBox = new google.maps.places.SearchBox(input);
	map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

	map.addListener('bounds_changed', function() {
		searchBox.setBounds(map.getBounds());
	});

	map.addListener('zoom_changed', function() {
		$("#zoom").val(map.zoom);
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
		places.forEach(function(place) {
			var obj = JSON.parse(JSON.stringify(place.geometry.location));
			$("#lat").val(obj.lat);
			$("#lng").val(obj.lng);
			if (!place.geometry) {
				console.log("Returned place contains no geometry");
				return;
			}

			markers.push(new google.maps.Marker({
				map: map,
				title: place.name,
				position: place.geometry.location
			}));

			if (place.geometry.viewport) {
				bounds.union(place.geometry.viewport);
			} else {
				bounds.extend(place.geometry.location);
			}

			map.addListener('drag', function() {
				var objChange = JSON.parse(JSON.stringify(map.center));
				$("#lat").val(objChange.lat);
				$("#lng").val(objChange.lng);
			});
		});
		map.fitBounds(bounds);
	});
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDfVHt_u3hEO6R5Gavi8XWcCYQkA3nNhik&libraries=places&callback=initAutocomplete" async defer></script>