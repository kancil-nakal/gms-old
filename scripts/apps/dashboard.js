'use strict';
$(function() {
	getArea();
	getDashboard( $("#area").val() );
});

var maps;
var routeCoordinates;
var neighborhoods 	= [];
var markers 		= [];
var $table  = $('#getArea');
function getArea() {
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'dashboard/getArea',
		cache: false,
		search : false,
		pagination : true,
		striped: true,
		sortOrder: 'desc',
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		pageSize: 5,
		columns: [
			{ field: 'city', title: 'AREA', align: 'left', formatter: selectFormatter, events: selectEvents },
			{ field: 'total', title: 'JUMLAH UNIT', align: 'center' }
		]
	});
}

function selectFormatter(value, row, index) {
	return [
		'<a class="getDataCity">'+row.city+'</a>'
	].join('');
}

window.selectEvents = {
	'click .getDataCity': function (e, value, row, index) {
		$("#getDetail").bootstrapTable('destroy');
		$("#area").val(row.city);
		getDashboard( row.city );
	}
};

function getDashboard(area) {
	var $table  = $('#getDetail');
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'dashboard/getDetail/'+area,
		cache: false,
		search : false,
		pagination : true,
		striped: true,
		sortOrder: 'asc',
		pageSize: 5,
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{ field: 'member_name', title: 'Driver', align: 'left' },
			{ field: null, title: 'Mobil', align: 'left', formatter: statusFormat },
			{ field: 'driverStatus', title: 'Status', align: 'center', formatter: statusDriver },
			{ field: null, title: 'Detail', align: 'center', formatter: operateFormatter }
		]
	});
}

function statusFormat(value, row, index) {
	return row.merk_mobil + ' ' + row.type_mobil;
}

function statusDriver(value, row, index) {
	if(row.driverStatus == 1) {
		return [
			'<span class="label label-lg primary"><i class="fa fa-automobile"></i></span>'
		].join('');
	} else {
		return [
			'<span class="label label-lg danger"><i class="fa fa-automobile"></i></span>'
		].join('');
	}
}

function operateFormatter(value, row, index) {
	return [
		'<button class="btn btn-info driverDetail" onclick="tesDidi('+row.mobile_phone+');" title="Detail">Detail</button>'
	].join('');
}

function getMap(lat, lng, phone) {
	maps = new google.maps.Map(document.getElementById('map_canvas'), {
		zoom: 8,
		center: {lat: parseFloat(lat), lng: parseFloat(lng)},
		scrollwheel: true,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});

	var trafficLayer = new google.maps.TrafficLayer();
	trafficLayer.setMap(maps);

	$.ajax({
		type: "GET",
		url: BASE_URL+'dashboard/countroute',
		data: 'id=0' + phone,
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
	} else {
		strokeColor = '#fed614';		
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

function tesDidi(phone) {
	$('#frm_detailDriver').modal('show');
	$.ajax({
		type: "GET",
		url: BASE_URL+'dashboard/getmarker',
		dataType: 'json',
		data: 'id=0' + phone,
		success: function(data) {
			getMap(data.lat, data.lng, phone);
		}
	});
}
