// (function ($) {
// 	'use strict';

$(function() {
	getArea();
	getDashboard( $("#area").val() );
});

	function getArea() {
		var $table  = $('#getArea');
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
			columns: [{
				field: 'city',
				title: 'AREA',
				align: 'left',
				formatter: selectFormatter,
				events: selectEvents
			}, {
				field: 'total',
				title: 'JUMLAH UNIT',
				align: 'center'
			}]
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
			columns: [{
				field: 'member_name',
				title: 'Driver',
				align: 'left'
			}, {
				field: null,
				title: 'Mobil',
				align: 'left',
				formatter: statusFormat
			}, {
				field: 'driverStatus',
				title: 'Status',
				align: 'center',
				formatter: statusDriver
			}, {
				field: null,
				title: 'Detail',
				align: 'center',
				formatter: operateFormatter
				// events: selectEventsDetail
			}]
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
	// window.selectEventsDetail = {
	// 	'click .driverDetail': function (e, value, row, index) {
	// 		alert('a');
	// 		// $('#frm_detailDriver').modal('show');
	// 		// $.ajax({
	// 		// 	type: "GET",
	// 		// 	url: BASE_URL+'tracking/getmarker',
	// 		// 	dataType: 'json',
	// 		// 	data: 'id=' + row.mobile_phone,
	// 		// 	success: function(data) {
	// 		// 		var lat = data.lat;
	// 		// 		var lon = data.long;
	// 		// 		$("#map_canvas").googleMap({
	// 		// 			zoom: 13
	// 		// 		});

	// 		// 		$("#map_canvas").addMarker({
	// 		// 			coords: [lat, lon]
	// 		// 		});
	// 		// 	}
	// 		// });
	// 	}
	// };

	
//})(jQuery);
