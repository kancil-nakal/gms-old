'use strict';
$(function() {
	$(".member").on('click', function(e) {
		$("#listClient").bootstrapTable('destroy');
		$('#frm_member').modal('show');
		listClient();
	});
});

var $table = $('#listClient');
function listClient() {
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'api/manageData/2',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{ field: 'ta_name', title: 'Name', align: 'left', sortable: true },
			{ field: 'ta_email', title: 'Email', align: 'left' },
			{ field: 'ta_phone', title: 'Phone', align: 'left' },
			{ field: null, title: 'Action', align: 'center', width: '200px', formatter: operateFormatter, events: operateEvents }
		]
	});
}

function operateFormatter(value, row, index) {
	return [
		'<div class="btn-groupss">',
			'<button type="button" class="btn btn-success select" title="Select" style="margin-right:5px;"><i class="fa fa-edit"></i> Select</button>',
		'</div>'
	].join('');
}

window.operateEvents = {
	'click .select': function (e, value, row, index) {
		$('#member_name').val(row.ta_name);
		$('#mobile_phone').val(row.ta_phone);
		$('#email_address').val(row.ta_email);
		$('#saldo').val(row.saldo);
		$('#id').val(row.id);
		$('#topup').focus();
		$('#frm_member').modal('hide');
	}
};
