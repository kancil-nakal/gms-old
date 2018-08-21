(function ($) {
	'use strict';

	function listWithdraw() {
		var $table = $('#listWithdraw');
		$table.bootstrapTable({
			method: 'GET',
			// url: BASE_URL+'master/listAdmin/1',
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
				title: 'Nama Driver',
				align: 'left',
				sortable: true
			}, {
				field: 'request_date',
				title: 'Tanggal',
				align: 'left'
			}, {
				field: 'request_date',
				title: 'Nama Bank',
				align: 'left'
			}, {
				field: 'request_date',
				title: 'Nama Akun',
				align: 'left'
			}, {
				field: 'request_date',
				title: 'No Rekening',
				align: 'left'
			}, {
				field: 'total_withdraw',
				title: 'Total',
				align: 'center'
			}, {
				field: null,
				title: 'Action',
				align: 'center',
				width: '250px',
				formatter: operateFormatter,
				events: operateEvents
			}]
		});
	}

	function operateFormatter(value, row, index) {
		return [
			'<div class="btn-groupss">',
				'<button type="button" class="btn btn-success edit" title="Edit" style="margin-right:5px;"><i class="fa fa-edit"></i> Edit</button>',
				'<button type="button" class="btn btn-danger remove" title="Delete"><i class="fa fa-trash-o"></i> Delete</button>',
			'</div>'
		].join('');
	}

	window.operateEvents = {
		'click .edit': function (e, value, row, index) {
			window.location = BASE_URL+'client/form/'+row.id;
		},
		'click .remove': function (e, value, row, index) {
			swal({
				title				: "Are you sure want to delete this data?",
				type				: "warning",
				showCancelButton	: true,
				confirmButtonColor	: "#F56954",
				confirmButtonText	: "Yes",
				cancelButtonText	: "No",
				closeOnConfirm		: false,
				closeOnCancel		: true
			}, function (isConfirm) {
				if (isConfirm) {
					$.ajax({
						type		: 'GET',
						url			: BASE_URL+'user/delete/'+row.id,
						dataType	: 'json',
						success: function (data) {
							if (data.result == 'success') {
								swal({
									title	: "Success",
									text	: "User successfully deleted",
									timer	: 2000,
									type	: "success",
									showConfirmButton:false
								});
							} else {
								swal({
									title	: "Failed",
									text	: "User failed to deleted",
									timer	: 2000,
									type	: "error",
									showConfirmButton:false
								});
							}
						},
						error: function (jqXHR, textStatus, errorThrown) {
							swal({
								title	: "Error",
								text	: jqXHR.status,
								timer	: 2000,
								type	: "error",
								showConfirmButton:false
							});
						}
					}).then(function () {
						$("#listClient").bootstrapTable('refresh');
					});
				}
			});
		}
	};

	function listFinance() {
		var $table = $('#listFinance');
		$table.bootstrapTable({
			method: 'GET',
			// url: BASE_URL+'master/listAdmin/1',
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
				title: 'Nama Driver',
				align: 'left',
				sortable: true
			}, {
				field: 'request_date',
				title: 'Tanggal',
				align: 'left'
			}, {
				field: 'request_date',
				title: 'Nama Bank',
				align: 'left'
			}, {
				field: 'request_date',
				title: 'Nama Akun',
				align: 'left'
			}, {
				field: 'request_date',
				title: 'No Rekening',
				align: 'left'
			}, {
				field: 'total_withdraw',
				title: 'Total',
				align: 'center'
			}, {
				field: null,
				title: 'Action',
				align: 'center',
				width: '250px',
				formatter: operateFormatters,
				events: operateEventss
			}]
		});
	}

	function operateFormatters(value, row, index) {
		return [
			'<div class="btn-groupss">',
				'<button type="button" class="btn btn-success edit" title="Edit" style="margin-right:5px;"><i class="fa fa-edit"></i> Edit</button>',
				'<button type="button" class="btn btn-danger remove" title="Delete"><i class="fa fa-trash-o"></i> Delete</button>',
			'</div>'
		].join('');
	}

	window.operateEventss = {
		'click .edit': function (e, value, row, index) {
			window.location = BASE_URL+'client/form/'+row.id;
		},
		'click .remove': function (e, value, row, index) {
			swal({
				title				: "Are you sure want to delete this data?",
				type				: "warning",
				showCancelButton	: true,
				confirmButtonColor	: "#F56954",
				confirmButtonText	: "Yes",
				cancelButtonText	: "No",
				closeOnConfirm		: false,
				closeOnCancel		: true
			}, function (isConfirm) {
				if (isConfirm) {
					$.ajax({
						type		: 'GET',
						url			: BASE_URL+'user/delete/'+row.id,
						dataType	: 'json',
						success: function (data) {
							if (data.result == 'success') {
								swal({
									title	: "Success",
									text	: "User successfully deleted",
									timer	: 2000,
									type	: "success",
									showConfirmButton:false
								});
							} else {
								swal({
									title	: "Failed",
									text	: "User failed to deleted",
									timer	: 2000,
									type	: "error",
									showConfirmButton:false
								});
							}
						},
						error: function (jqXHR, textStatus, errorThrown) {
							swal({
								title	: "Error",
								text	: jqXHR.status,
								timer	: 2000,
								type	: "error",
								showConfirmButton:false
							});
						}
					}).then(function () {
						$("#listFinance").bootstrapTable('refresh');
					});
				}
			});
		}
	};

	listWithdraw();
	listFinance();
})(jQuery);