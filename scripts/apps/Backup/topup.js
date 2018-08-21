(function ($) {
	'use strict';

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
				sortable: true
			}, {
				field: 'email_address',
				title: 'Email',
				align: 'left'
			}, {
				field: 'mobile_phone',
				title: 'Phone',
				align: 'left'
			}, {
				field: null,
				title: 'Action',
				align: 'center',
				width: '200px',
				formatter: operateFormatter,
				events: operateEvents
			}]
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
			$('#member_name').val(row.member_name);
			$('#mobile_phone').val(row.mobile_phone);
			$('#email_address').val(row.email_address);
			$('#saldo').val(row.saldo);
			$('#id').val(row.id);
			$('#topup').focus();
			$('#frm_member').modal('hide');
		}
	};

	$(".member").on('click', function(e) {
		$('#frm_member').modal('show');
		listClient();
	});
})(jQuery);