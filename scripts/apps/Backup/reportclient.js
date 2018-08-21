(function ($) {
	'use strict';

	function listReport() {
		var $table = $('#listReport');
		$table.bootstrapTable({
			method: 'GET',
			url: BASE_URL+'report/getReport',
			cache: false,
			search : true,
			pagination : true,
			striped: true,
			sidePagination: 'client',
			smartDisplay: false,
			onlyInfoPagination: false,
			columns: [{
				field: 'report_date',
				title: 'Date',
				align: 'left'
			}, {
				field: 'total_saldo',
				title: 'Saldo',
				align: 'right'
			}, {
				field: 'total_km',
				title: 'Total KM',
				align: 'center'
			}, {
				field: 'total_viewer',
				title: 'Viewer',
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

	function reportDetail(report_date) {
		var tanggal = report_date;
		var $table = $('#reportDetail');
		$table.bootstrapTable({
			method: 'GET',
			url: BASE_URL+'report/reportDetail/'+tanggal,
			cache: false,
			search : true,
			pagination : true,
			striped: true,
			sidePagination: 'client',
			smartDisplay: false,
			onlyInfoPagination: false,
			columns: [{
				field: 'member_name',
				title: 'Driver',
				align: 'left'
			}, {
				field: 'mobil',
				title: 'Car',
				align: 'left'
			}, {
				field: 'city',
				title: 'Area',
				align: 'left'
			}, {
				field: 'total_km',
				title: 'Total KM',
				align: 'center'
			// }, {
			// 	field: 'total_saldo',
			// 	title: 'Total Saldo',
			// 	align: 'center'
			}, {
				field: 'total_viewer',
				title: 'Viewer',
				align: 'center'
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
			$("#report_detail").modal("show");
			reportDetail(row.report_date);
		}
	};

	$('#report_detail').on('hide.bs.modal', function (event) {
		$("#reportDetail").bootstrapTable('destroy');
	});

	listReport();
})(jQuery);