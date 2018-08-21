(function ($) {
	'use strict';

	function listRequestCar() {
		var $table = $('#listRequestCar');
		$table.bootstrapTable({
			method: 'GET',
			url: BASE_URL+'requestcar/listRequest',
			cache: false,
			search : true,
			pagination : true,
			striped: true,
			toolbar: '#toolbarMember',
			sidePagination: 'server',
			smartDisplay: false,
			onlyInfoPagination: false,
			columns: [{
				field: 'name',
				title: 'Client',
				align: 'left',
				sortable: true
			}, {
				field: 'request_date',
				title: 'Request Date',
				align: 'center'
			}, {
				field: 'total',
				title: 'Total Unit',
				align: 'center'
			}, {
				field: null,
				title: 'Action',
				align: 'center',
				width: '100px',
				formatter: operateFormatter,
				events: operateEvents
			}]
		});
	}

	function operateFormatter(value, row, index) {
		return [
			'<button class="btn btn-primary detail"><i class="fa fa-search"></i></button>'
		].join('');
	}

	window.operateEvents = {
		'click .detail': function (e, value, row, index) {
			$('#frm_detail1').modal('show');
			detailRequestCar(row.client_id);
		}
	};

	$('#frm_detail1').on('show.bs.modal', function (e) {

	});

	function detailRequestCar(client_id) {
		var $table = $('#detailRequestCar');
		$table.bootstrapTable({
			method: 'GET',
			url: BASE_URL+'requestcar/detailRequest/'+client_id,
			cache: false,
			search : true,
			pagination : true,
			striped: true,
			toolbar: '#toolbarMember',
			sidePagination: 'server',
			smartDisplay: false,
			onlyInfoPagination: false,
			columns: [{
				field: 'car',
				title: 'Car',
				align: 'left',
				formatter: formatCar
			}, {
				field: 'city',
				title: 'City',
				align: 'left'
			}, {
				field: 'request_car',
				title: 'Total Request',
				align: 'center'
			}, {
				field: null,
				title: 'Action',
				align: 'center',
				width: '100px',
				formatter: operateFormatters,
				events: operateEventss
			}]
		});
	}

	function formatCar(value, row, index) {
		return row.merk_mobil + ' ' + row.type_mobil;
	}

	function operateFormatters(value, row, index) {
		return [
			'<button class="btn btn-primary details"><i class="fa fa-search"></i></button>'
		].join('');
	}

	window.operateEventss = {
		'click .details': function (e, value, row, index) {
			$('#frm_detail2').modal('show');
			detailRequestCar1(row.client_id);
		}
	};

	function detailRequestCar1(client_id) {
		var $table = $('#detailRequestCar2');
		$table.bootstrapTable({
			method: 'GET',
			// url: BASE_URL+'requestcar/detailRequest/'+client_id,
			cache: false,
			search : true,
			pagination : true,
			striped: true,
			toolbar: '#toolbarMember',
			sidePagination: 'server',
			smartDisplay: false,
			onlyInfoPagination: false,
			columns: [{
				field: 'car',
				title: 'Car',
				align: 'left',
				formatter: formatCar
			}, {
				field: 'city',
				title: 'City',
				align: 'left'
			}, {
				field: 'request_car',
				title: 'Total Request',
				align: 'center'
			}, {
				field: null,
				title: 'Action',
				align: 'center',
				width: '100px',
				formatter: operateFormatters,
				events: operateEventss
			}]
		});
	}

	listRequestCar();
})(jQuery);