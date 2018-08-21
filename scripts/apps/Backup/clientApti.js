// (function ($) {
// 	'use strict';
$(function() {
	listClient();
});


function listClient() {
	var $table = $('#listClient');
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'master/listAdmin/1',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		toolbar: '#toolbarMember',
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [{
			field: 'member_name',
			title: 'Name',
			align: 'left',
			sortable: true,
			formatter: selectFormatter,
			events: selectEvents
		}, {
			field: 'email_address',
			title: 'Email',
			align: 'left'
		}, {
			field: 'mobile_phone',
			title: 'Phone',
			align: 'left'
		}]
	});
}

function selectFormatter(value, row, index) {
	return [
		'<a class="getClientName">'+row.member_name+'</a>'
	].join('');
}

window.selectEvents = {
	'click .getClientName': function (e, value, row, index) {
		$("#idClient").val(row.id);
		$("#frm_detailReport").modal('show');
		getArea( row.id );
		getDashboard('all', row.id );
	}
};

function getArea(id) {
	var $table  = $('#getArea');
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'client/getArea/'+id,
		cache: false,
		search : false,
		pagination : true,
		striped: true,
		sortOrder: 'desc',
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		pageSize: 5,
		columns: [{
			field: 'city',
			title: 'AREA',
			align: 'left',
			formatter: selectFormatterArea,
			events: selectEventsArea
		}, {
			field: 'total',
			title: 'JUMLAH UNIT',
			align: 'center'
		}]
	});
}

function selectFormatterArea(value, row, index) {
	return [
		'<a class="getDataCity">'+row.city+'</a>'
	].join('');
}

window.selectEventsArea = {
	'click .getDataCity': function (e, value, row, index) {
		$("#getDetail").bootstrapTable('destroy');
		$("#area").val(row.city);
		getDashboard( row.city, row.id );
	}
};

function getDashboard(area, id) {
	var $table  = $('#getDetail');
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'client/getDetail/'+id+'/'+area,
		cache: false,
		search : false,
		pagination : true,
		striped: true,
		sortOrder: 'asc',
		pageSize: 5,
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [{
			field: 'member_name',
			title: 'Driver',
			align: 'left'
		}, {
			field: null,
			title: 'Mobil',
			align: 'left',
			formatter: statusFormats
		}, {
			field: 'driverStatus',
			title: 'Status',
			align: 'center',
			formatter: statusDriver
		}, {
			field: null,
			title: 'Detail',
			align: 'center',
			formatter: operateFormatters
			// events: tesDidi
		}]
	});
}

function statusFormats(value, row, index) {
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

$('#frm_detailReport').on('shown.bs.modal', function () {
	initMap();
	drop();
	$.ajax({
		type: "GET",
		url: BASE_URL+'client/getSaldo/'+$("#idClient" ).val(),
		dataType: 'json',
		success: function(data) {
			$("#jarak").html(data[0].total_km + ' KM');
			$("#viewer").html(data[0].total_viewer);
			$("#akumulasi").html(data[0].total_saldo + ' IDR');
			$("#sisa").html(data[0].saldo + ' IDR');
		}
	});
});

function operateFormatters(value, row, index) {
	return [
		'<button class="btn btn-info driverDetail" onclick="tesDidi('+row.mobile_phone+');" title="Detail">Detail</button>'
	].join('');
}

function tesDidi(phone) {
	$('#frm_detailDriver').modal('show');
	$.ajax({
		type: "GET",
		url: BASE_URL+'tracking/getmarker',
		dataType: 'json',
		data: 'id=0' + phone,
		success: function(data) {
			var lat = data.lat;
			var lon = data.long;
			$("#map_canvas").googleMap({
				zoom: 13
			});

			$("#map_canvas").addMarker({
				coords: [lat, lon]
			});
		}
	});
}

// 	listClient();
// })(jQuery);