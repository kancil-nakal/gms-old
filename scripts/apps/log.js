 'use strict';
$(function() {
	listLog();
	$('#report_detail').on('hide.bs.modal', function (event) {
		$("#reportDetail").bootstrapTable('destroy');
	});
});

var maps;
var routeCoordinates;
var eventArea;
var cityCircle;
var neighborhoods 	= [];
var markers 		= [];
var $table 	= $('#logDriver');
var $tables = $('#logDetailDriver');
var pathSnap;
var pathSnapCounter;
var apiKey = 'AIzaSyDfVHt_u3hEO6R5Gavi8XWcCYQkA3nNhik';
var placeIdArray = [];
var polylines = [];
var snappedCoordinates = [];
function listLog() {
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'log/getLog',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{ field: 'member_name', title: 'Date', align: 'left' },
			{ field: 'phone', title: 'Phone', align: 'left', width: '200px' },
			{ field: 'company_name', title: 'Perusahaan', align: 'left' },
			{ field: null, title: 'Action', align: 'center', width: '250px', formatter: operateFormatter, events: operateEvents }
		]
	});
}
/*
			{ field: 'client_name', title: 'Client', align: 'left' },
*/

function operateFormatter(value, row, index) {
	return [
		'<div class="btn-groupss">',
			'<button class="btn btn-primary detail" style="margin-right:5px;"><i class="fa fa-search"></i> View Log</button>',
			'<button type="button" class="btn btn-success excel" title="Excel"><i class="fa fa fa-file-excel-o"></i> Export to Excel</button>',
		'</div>'
	].join('');
}

window.operateEvents = {
	'click .detail': function (e, value, row, index) {
		$tables.bootstrapTable('destroy');
		$("#report_detail").modal("show");
		$('.driverName').html(row.member_name);
		listLogDetail(row.phone);
	},
	'click .excel': function (e, value, row, index) {
		window.location = BASE_URL+'log/export/'+row.phone;
	}
};

function listLogDetail(phone) {
	$tables.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'log/getLogDetail/'+phone,
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		pageSize: 7,
		sortOrder: 'desc',
		rowStyle: 'rowStyle',
		columns: [
			{ field: 'report_date', title: 'Date', align: 'left' },
			{ field: 'total_rit', title: 'Total Rit', align: 'right' },
			{ field: 'total_saldo', title: 'Total Dana', align: 'right' },
			{ field: 'dana_driver', title: 'Dana Driver', align: 'right' },
			{ field: 'dana_tempelad', title: 'Dana Tempel`AD', align: 'right' },
			{ field: 'total_km', title: 'Total KM', align: 'center' },
			{ field: 'total_viewer', title: 'Total Viewer', align: 'center' },
			{ field: null, title: 'Action', align: 'center', width: '150px', formatter: operateFormatters, events: operateEventss }
		]
	});
}

function rowStyle(row, index) {
	var km = row.total_km;
	var total_km = km.replace(',','');
	if(total_km > 250) {
		return {
			classes: 'tl-danger'
		};
	} else if(total_km > 150 && total_km <= 249) {
		return {
			classes: 'tl-warning'
		};
	}
	return {};
}

function operateFormatters(value, row, index) {
	return [
		'<div class="btn-groupss">',
			'<button class="btn btn-primary detail"><i class="fa fa-map-marker"></i></button>&nbsp;',
			'<button type="button" class="btn btn-success exceldetail" title="Excel"><i class="fa fa fa-file-excel-o"></i></button>',
		'</div>'
	].join('');
}

window.operateEventss = {
	'click .detail': function (e, value, row, index) {
		$("#route_detail").modal("show");
		pathSnap = '';
		pathSnapCounter = 0;
		$.ajax({
			type: "GET",
			url: BASE_URL+'log/getmarker',
			data: 'phone='+row.phone+'&date='+row.tanggal,
			dataType: 'json',
			success: function(data) {
				getMap(data.lat, data.lng, row.phone, row.tanggal);
			}
		});
	},
	'click .exceldetail': function (e, value, row, index) {
		window.location = BASE_URL+'log/exportDetail/'+row.phone+'/'+row.tanggal;
	}
};

function getMap(lat, lng, phone, tanggal) {
	maps = new google.maps.Map(document.getElementById('map_canvas'), {
		zoom: 14,
		center: {lat: parseFloat(lat), lng: parseFloat(lng)},
		scrollwheel: true,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		fullscreenControl: true
	});

	$.ajax({
		type: "GET",
		url: BASE_URL+'dashboard/countroute',
		data: 'id='+phone+'&date='+tanggal,
		dataType: 'json',
		success: function(data) {
			for(var i=0; i<data.length; i++) {
				getRoute(data[i].phone, data[i].report_date, data[i].id_start, data[i].id_end, data[i].trip_to) ;
			}
		}
	})
	.done(function() {
			getSnapToRoad();
	});
}

function getRoute(phone, date, id_start, id_end, trip_to) {
	$.ajax({
		type: 'get',
		url: BASE_URL+'dashboard/location',
		data: 'phone='+phone+'&start='+id_start+'&end='+id_end,
		dataType: 'json',
		success: function(result) {
			var loc = [];
			$.each(result, function (index, personne) {
				loc.push(personne);
			});
			neighborhoods = loc ;
		},
		async: false
	});
	showMarker(neighborhoods);
	addEventArea(phone,date);

	$.ajax({
		type: "GET",
		url: BASE_URL+'dashboard/getroute',
		data: 'phone='+phone+'&start='+id_start+'&end='+id_end,
		dataType: 'json',
		success: function(data) {
			var loc = [];
			$.each(data, function (index, personne) {
				loc.push(personne);
				if(pathSnapCounter < 99) {
					if (typeof pathSnap == 'undefined' || pathSnap == '') pathSnap = personne.lat + ',' + personne.lng;
					else pathSnap = pathSnap + '|' + personne.lat + ',' + personne.lng;
				}
				pathSnapCounter++;
			});
			routeCoordinates = loc ;
		},
		async: false
	});

	var strokeColor;
	if(trip_to == 1) {
		strokeColor = '#4472f4';
	} else if(trip_to == 2) {
		strokeColor = '#fed614';
	} else if(trip_to == 3) {
		strokeColor = '#029221';
	} else if(trip_to == 4) {
		strokeColor = '#ff0000';
	} else if(trip_to == 5) {
		strokeColor = '#ff00ff';
	} else if(trip_to == 6) {
		strokeColor = '#00ffff';
	} else if(trip_to == 7) {
		strokeColor = '#430a0a';
	} else if(trip_to == 8) {
		strokeColor = '#3cd0bd';
	} else if(trip_to == 9) {
		strokeColor = '#849a75';
	} else {
		strokeColor = '#757272';
	}

	var flightPath = new google.maps.Polyline({
		path: routeCoordinates,
		geodesic: true,
		strokeColor: strokeColor,
		strokeOpacity: 1.0,
		strokeWeight: 4
	});

	flightPath.setMap(maps);
}

function showMarker(data) {
	for (var i = 0; i < data.length; i++) {
		addMarker(data[i], i * 200);
	}
}

function addMarker(position, timeout) {
	var urlImg;
	if(position.position == 'start') {
		urlImg = 'assets/images/start.png';
	} else if(position.position == 'finish') {
		urlImg = 'assets/images/finish.png';
	}
	var image = {
		url 	: BASE_URL+urlImg,
		size 	: new google.maps.Size(25, 47),
		origin 	: new google.maps.Point(0, 0),
		anchor 	: new google.maps.Point(15, 47)
	};

	markers.push(new google.maps.Marker({
		position	: {lat: parseFloat(position.lat), lng: parseFloat(position.lng)},
		icon 		: image,
		map 		: maps,
		title 		: position.total_km + ' KM'
	}));
}

function addEventArea(phone,date) {
	
	$.ajax({
		type: "GET",
		url: BASE_URL+'events/getone_byroute/'+phone+'/'+date,
		dataType: 'json',
		success: function(data) {
			if(data !== false) {
				var arr = [];
				$.each(data, function (index, row) {
					arr.push(row);
				});
				eventArea = arr ;
			} else {
				eventArea = false;
			}
		},
		async: false
	});
	
	if(eventArea === false) {
		if(cityCircle) cityCircle.setMap(null);
	} else {
		for (var area in eventArea) {
			cityCircle = new google.maps.Circle({
				strokeColor: '#FF0000',
				strokeOpacity: 0.8,
				strokeWeight: 2,
				fillColor: '#FF0000',
				fillOpacity: 0.1,
				map: maps,
				center: {lat: parseFloat(eventArea[area].lat), lng: parseFloat(eventArea[area].lng)},
				radius: parseInt(eventArea[area].radius_meter)
			});
		}
	}
}



function getSnapToRoad(){
	
	console.log('snap:'+pathSnap);
	$.ajax({
		type: "GET",
		url: 'https://roads.googleapis.com/v1/snapToRoads?key='+apiKey+'&interpolate=true&path='+pathSnap,
		dataType: 'json',
		success: function(data) {
			console.log(data);
			
			processSnapToRoadResponse(data);
			drawSnappedPolyline();
			//getAndDrawSpeedLimits();
			/*
			for(var i=0; i<data.length; i++) {
				//
			}
			*/
		}
	});
}

// Store snapped polyline returned by the snap-to-road service.
function processSnapToRoadResponse(data) {
  snappedCoordinates = [];
  placeIdArray = [];
  for (var i = 0; i < data.snappedPoints.length; i++) {
    var latlng = new google.maps.LatLng(
        data.snappedPoints[i].location.latitude,
        data.snappedPoints[i].location.longitude);
    snappedCoordinates.push(latlng);
    placeIdArray.push(data.snappedPoints[i].placeId);
  }
}

// Draws the snapped polyline (after processing snap-to-road response).
function drawSnappedPolyline() {
  var snappedPolyline = new google.maps.Polyline({
    path: snappedCoordinates,
    strokeColor: 'gray',
    strokeWeight: 1
  });

  snappedPolyline.setMap(maps);
  polylines.push(snappedPolyline);
}

// Gets speed limits (for 100 segments at a time) and draws a polyline
// color-coded by speed limit. Must be called after processing snap-to-road
// response.
function getAndDrawSpeedLimits() {
  for (var i = 0; i <= placeIdArray.length / 100; i++) {
    // Ensure that no query exceeds the max 100 placeID limit.
    var start = i * 100;
    var end = Math.min((i + 1) * 100 - 1, placeIdArray.length);

    drawSpeedLimits(start, end);
  }
}

// Gets speed limits for a 100-segment path and draws a polyline color-coded by
// speed limit. Must be called after processing snap-to-road response.
function drawSpeedLimits(start, end) {
    var placeIdQuery = '';
    for (var i = start; i < end; i++) {
      placeIdQuery += '&placeId=' + placeIdArray[i];
    }

    $.get('https://roads.googleapis.com/v1/speedLimits',
        'key=' + apiKey + placeIdQuery,
        function(speedData) {
          processSpeedLimitResponse(speedData, start);
        }
    );
}

// Draw a polyline segment (up to 100 road segments) color-coded by speed limit.
function processSpeedLimitResponse(speedData, start) {
  var end = start + speedData.speedLimits.length;
  for (var i = 0; i < speedData.speedLimits.length - 1; i++) {
    var speedLimit = speedData.speedLimits[i].speedLimit;
    var color = getColorForSpeed(speedLimit);

    // Take two points for a single-segment polyline.
    var coords = snappedCoordinates.slice(start + i, start + i + 2);

    var snappedPolyline = new google.maps.Polyline({
      path: coords,
      strokeColor: color,
      strokeWeight: 6
    });
    snappedPolyline.setMap(map);
    polylines.push(snappedPolyline);
  }
}

function getColorForSpeed(speed_kph) {
  if (speed_kph <= 40) {
    return 'purple';
  }
  if (speed_kph <= 50) {
    return 'blue';
  }
  if (speed_kph <= 60) {
    return 'green';
  }
  if (speed_kph <= 80) {
    return 'yellow';
  }
  if (speed_kph <= 100) {
    return 'orange';
  }
  return 'red';
}