<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">Edit Province</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<?php foreach ($getData as $row) { ?>
			<form action="<?php echo base_url('province/save'); ?>" method="post">
				<div class="box">
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group row">
									<label class="col-sm-2 form-control-label"><b>Name</b></label>
									<div class="col-sm-4">
										<input type="text" class="form-control" placeholder="Enter Brand" name="propinsi_name" id="propinsi_name" onkeyup="cekProvince();" required value="<?php echo $row->propinsi_name; ?>">
									</div>
								</div>

								<div class="form-group row">
									<label class="col-sm-2 form-control-label"><b>Active</b></label>
									<div class="col-sm-4">
										<div class="switch-toggle switch-2 well" style="margin-bottom:0px !important">
											<?php if($row->status == 0) { ?>
												<input id="Y" name="status" type="radio" checked value="0">
												<label for="Y" onclick="">Yes</label>
												<input id="N" name="status" type="radio" value="1">
												<label for="N" onclick="">No</label>
												<a class="btn btn-primary"></a>
											<?php } else { ?>
												<input id="Y" name="status" type="radio" value="0">
												<label for="Y" onclick="">Yes</label>
												<input id="N" name="status" type="radio" checked value="1">
												<label for="N" onclick="">No</label>
												<a class="btn btn-primary"></a>
											<?php } ?>
										</div>
									</div>
								</div>

								<div class="form-group row">
									<div class="col-sm-12">
										<input id="pac-inputs1" class="controls form-control" type="text" placeholder="Search Box" style="width: 50%;">
										<div id="map_canvas" style="width: 100% !important; height: 500px !important"></div>
									</div>
								</div>
							</div>
						</div>			
					</div>

					<div class="dker p-a text-left">
						<input type="hidden" name="lat" id="lat" value="<?php echo $row->lat;?>"></input>
						<input type="hidden" name="lng" id="lng" value="<?php echo $row->lng;?>"></input>
						<input type="hidden" name="zoom" id="zoom" value="<?php echo $row->zoom;?>"></input>
						<input type="hidden" name="id" value="<?php echo $row->id; ?>"></input>
						<button type="submit" class="btn btn-fw info">Submit</button>
						<a href="<?php echo base_url('province'); ?>" class="btn btn-fw danger">Cancel</a>
					</div>
				</div>
			</form>
		<?php } ?>
	</div>
</div>
<script type="text/javascript">
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
	var lat;
	var lng;
	var zoom;

	if($("#lat").val() == '') { lat = '-6.7814026';}
	else {lat = $("#lat").val()}
	if($("#lng").val() == '') { lng = '107.5719325';}
	else {lng = $("#lng").val()}

	if($("#zoom").val() == 0) { zoom = '8';}
	else {zoom = $("#zoom").val()}

	var map = new google.maps.Map(document.getElementById('map_canvas'), {
		center: {lat: parseFloat(lat), lng: parseFloat(lng)},
		zoom: parseInt(zoom),
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

	map.addListener('drag', function() {
		var objChange = JSON.parse(JSON.stringify(map.center));
		$("#lat").val(objChange.lat);
		$("#lng").val(objChange.lng);
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