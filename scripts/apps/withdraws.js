'use strict';
$(function() {
	listWithdraw();
	listFinance();
//	listApti();
});

var $table = $('#listWithdraw');
var $tables = $('#listFinance');
//var $tabless = $('#listAPTI');
function listWithdraw() {
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'withdraw/finance',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		sidePagination: 'server',
		toolbar: '#toolbarWithdraw',
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{ field: 'member_name', title: 'Nama Driver', align: 'left', sortable: true },
			{ field: 'nama_bank', title: 'Nama Bank', align: 'left' },
			{ field: 'nama_akun', title: 'Nama Akun', align: 'left' },
			{ field: 'no_rekening', title: 'No Rekening', align: 'left' },
			{ field: 'ta_name', title: 'Nama Client', align: 'left', sortable: true },
			{ field: 'withdraw_date', title: 'Tanggal', align: 'right', sortable: true },
			{ field: 'withdraw_balance', title: 'Total', align: 'right' },
			{ field: null, title: 'Action', align: 'center', width: '150px', formatter: operateFormatter, events: operateEvents }
		]
	});
}

function operateFormatter(value, row, index) {
	return [
		'<div class="btn-groupss">',
			'<button type="button" class="btn btn-primary pay" title="Pay" style="width:120px;"><i class="fa fa-money"></i> Pay Driver</button>',
		'</div>'
	].join('');
}

window.operateEvents = {
	'click .pay': function (e, value, row, index) {
		swal({
			title				: "Anda yakin WITHDRAW akan ditransfer?",
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
					url			: BASE_URL+'withdraw/transfer/'+row.id,
					dataType	: 'json',
					success: function (data) {
						if (data.success == true) {
							swal({ title: "Success", text: "Withdraw sukses di bayar", timer: 2000, type: "success", showConfirmButton: false });
						} else {
							swal({ title: "Failed", text: "Withdraw gagal di bayar", timer: 2000, type: "error", showConfirmButton: false });
						}
					},
					error: function (jqXHR, textStatus, errorThrown) {
						swal({ title: "Error", text: jqXHR.status, timer: 2000, type: "error", showConfirmButton: false });
					}
				}).then(function () {
					$table.bootstrapTable('refresh');
					$tables.bootstrapTable('refresh');
				});
			}
		});
	}
};

function listFinance() {
	$tables.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'withdraw/apti',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		sidePagination: 'server',
		toolbar: '#toolbarFinance',
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{ field: 'member_name', title: 'Nama Driver', align: 'left', sortable: true },
			{ field: 'nama_bank', title: 'Nama Bank', align: 'left' },
			{ field: 'nama_akun', title: 'Nama Akun', align: 'left' },
			{ field: 'no_rekening', title: 'No Rekening', align: 'left' },
			{ field: 'ta_name', title: 'Nama Client', align: 'left', sortable: true },
			{ field: 'withdraw_date', title: 'Tanggal Request', align: 'right', width: '180px', sortable: true },
			{ field: 'tanggal_eksekusi', title: 'Tanggal Transfer', align: 'right', width: '180px', sortable: true },
			{ field: 'withdraw_balance', title: 'Total', align: 'right' }
		]
	});
}

// function listApti() {
// 	$tabless.bootstrapTable({
// 		method: 'GET',
// 		url: BASE_URL+'withdraw/apti',
// 		cache: false,
// 		search : true,
// 		pagination : true,
// 		striped: true,
// 		sidePagination: 'server',
// 		toolbar: '#toolbarAPTI',
// 		smartDisplay: false,
// 		onlyInfoPagination: false,
// 		columns: [
// 			{ field: 'member_name', title: 'Nama Driver', align: 'left', sortable: true },
// 			{ field: 'withdraw_date', title: 'Tanggal', align: 'right', sortable: true },
// 			{ field: 'withdraw_balance', title: 'Total', align: 'right' },
// 			{ field: null, title: 'Action', align: 'center', width: '150px', formatter: operateFormatters, events: operateEventss }
// 		]
// 	});
// }

// function operateFormatters(value, row, index) {
// 	return [
// 		'<div class="btn-groupss">',
// 			'<button type="button" class="btn btn-primary pay" title="Pay" style="width:120px;"><i class="fa fa-money"></i> Kirim ke APTI</button>',
// 		'</div>'
// 	].join('');
// }

// window.operateEventss = {
// 	'click .pay': function (e, value, row, index) {
// 		swal({
// 			title				: "Anda yakin WITHDRAW akan dikirim ke APTI?",
// 			type				: "warning",
// 			showCancelButton	: true,
// 			confirmButtonColor	: "#F56954",
// 			confirmButtonText	: "Yes",
// 			cancelButtonText	: "No",
// 			closeOnConfirm		: false,
// 			closeOnCancel		: true
// 		}, function (isConfirm) {
// 			if (isConfirm) {
// 				$.ajax({
// 					type		: 'GET',
// 					url			: BASE_URL+'withdraw/pay/'+row.id,
// 					dataType	: 'json',
// 					success: function (data) {
// 						if (data.success == true) {
// 							swal({ title: "Success", text: "Withdraw sukses di bayar", timer: 2000, type: "success", showConfirmButton: false });
// 						} else {
// 							swal({ title: "Failed", text: "Withdraw gagal di bayar", timer: 2000, type: "error", showConfirmButton: false });
// 						}
// 					},
// 					error: function (jqXHR, textStatus, errorThrown) {
// 						swal({ title: "Error", text: jqXHR.status, timer: 2000, type: "error", showConfirmButton: false });
// 					}
// 				}).then(function () {
// 					$table.bootstrapTable('refresh');
// 					$tables.bootstrapTable('refresh');
// 				});
// 			}
// 		});
// 	}
// };
