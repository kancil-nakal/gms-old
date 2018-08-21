(function ($) {
	'use strict';

	function listDriver() {
		var $table = $('#listMember');
		$table.bootstrapTable({
			method: 'GET',
			url: BASE_URL+'master/listAdmin/0',
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
				field: 'client_name',
				title: 'Client',
				align: 'left'
			// }, {
			// 	field: 'user_status',
			// 	title: 'Enabled',
			// 	align: 'center',
			// 	width: '80px',
			// 	formatter: statusFormat,
			// 	events: operateStatus
			// }, {
			// 	field: null,
			// 	title: 'Action',
			// 	align: 'center',
			// 	width: '190px',
			// 	formatter: operateFormatter,
			// 	events: operateEvents
			}]
		});
	}

	function statusFormat(value, row, index) {
		if(row.user_status == 0 ) {
			return [
				'<button class="btn btn-primary flagActive"><i class="glyphicon glyphicon-ok"></i></button>'
			].join('');
		} else {
			return [
				'<button class="btn btn-danger flagActive"><i class="glyphicon glyphicon-remove"></i></button>'
			].join('');		
		}
	}

	window.operateStatus = {
		'click .flagActive': function (e, value, row, index) {
			$.ajax({
				type		: 'get',
				url			: BASE_URL+'driver/user_status/'+row.id+'/'+row.user_status,
				dataType	: 'json',
				success		: function(data) {
					if (data.msg == 'success') {
						$("#listMember").bootstrapTable('refresh', true);
					} else {
						swal({
							title	: "Error",
							text	: "Failed",
							timer	: 2000,
							type	: "error",
							showConfirmButton:false
						});
					}
				}
			});
		}
	};

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
			window.location = BASE_URL+'driver/form/'+row.id;
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
						url			: BASE_URL+'driver/delete/'+row.id,
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
						$("#listMember").bootstrapTable('refresh');
					});
				}
			});
		}
	};

	listDriver();
})(jQuery);