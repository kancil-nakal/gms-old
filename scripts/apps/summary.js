 'use strict';
$(function() {
	listReport();
	$('#report_detail').on('hide.bs.modal', function (event) {
		$("#reportDetail").bootstrapTable('destroy');
	});
});

var maps;
var routeCoordinates;
var neighborhoods 	= [];
var markers 		= [];
var $table = $('#listReport');
function listReport() {
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'summary/getReport',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		sortOrder: 'desc',
		columns: [
			{ field: 'report_date', title: 'Date', align: 'left' },
			{ field: 'client_name', title: 'Client Name', align: 'left' },
			{ field: 'total_saldo', title: 'Total Dana', align: 'right', width: '130px' },
			{ field: 'total_km', title: 'Total KM', align: 'center', width: '130px' },
			{ field: 'total_viewer', title: 'Viewer', align: 'center', width: '130px' },
			{ field: null, title: 'Action', align: 'center', width: '250px', formatter: operateFormatter, events: operateEvents }
		]
	});
}

function operateFormatter(value, row, index) {
	return [
		'<div class="btn-groupss">',
			'<button class="btn btn-primary detail" style="margin-right:5px;"><i class="fa fa-search"></i> Detail</button>',
			'<button type="button" class="btn btn-success excel" title="Excel"><i class="fa fa fa-file-excel-o"></i> Export to Excel</button>',
		'</div>'
	].join('');
}

window.operateEvents = {
	'click .detail': function (e, value, row, index) {
		$("#report_detail").modal("show");
		reportDetail(row.tanggal, row.client_id);
	},
	'click .excel': function (e, value, row, index) {
		window.location = BASE_URL+'summary/export/'+row.client_id+'/'+row.tanggal;
	}	
};

function reportDetail(report_date, client_id) {
	var $table = $('#reportDetail');
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'summary/reportDetail/'+report_date+'/'+client_id,
		cache: false,
		search : true,
		pagination : true,
		striped: false,
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		rowStyle: 'rowStyle',
		columns: [
			{ field: 'member_name', title: 'Driver', align: 'left' },
			{ field: 'mobil', title: 'Car', align: 'left' },
			{ field: 'city', title: 'Area', align: 'left' },
			{ field: 'total_rit', title: 'Total Rit', align: 'right' },
			{ field: 'total_km', title: 'Total KM', align: 'center' },
			{ field: 'total_saldo', title: 'Total Dana', align: 'center' },
			{ field: 'dana_driver', title: 'Dana Driver', align: 'center' },
			{ field: 'dana_tempelad', title: 'Dana Tempel`AD', align: 'center' },
			{ field: 'total_viewer', title: 'Viewer', align: 'center' },
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
		'<button class="btn btn-primary details"><i class="fa fa-map-marker"></i> View Route</button>'
	].join('');
}

window.operateEventss = {
	'click .details': function (e, value, row, index) {
		$("#route_detail").modal("show");
		$.ajax({
			type: "GET",
			url: BASE_URL+'log/getmarker',
			data: 'phone='+row.phone+'&date='+row.report_date,
			dataType: 'json',
			success: function(data) {
				getMap(data.lat, data.lng, row.phone, row.report_date);
			}
		});
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
				getRoute(data[i].phone, data[i].id_start, data[i].id_end, data[i].trip_to);
			}
		}
	});
}

function getRoute(phone, id_start, id_end, trip_to) {
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

	$.ajax({
		type: "GET",
		url: BASE_URL+'dashboard/getroute',
		data: 'phone='+phone+'&start='+id_start+'&end='+id_end,
		dataType: 'json',
		success: function(data) {
			var loc = [];
			$.each(data, function (index, personne) {
				loc.push(personne);
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