'use strict';
$(function() {
	listOperator();
});

var $table = $('#listOperator');
function listOperator() {
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'api/manageData/1',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		toolbar: '#toolbarOperator',
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		sortOrder: 'desc',
		columns: [
			{ field: 'ta_name', title: 'Name', align: 'left', sortable: true },
			{ field: 'ta_email', title: 'Email', align: 'left' },
			{ field: 'ta_phone', title: 'Phone', align: 'left' },
			{ field: 'user_status', title: 'Enabled', align: 'center', width: '90px', formatter: statusFormat, events: operateStatus },
			{ field: null, title: 'Action', align: 'center', width: '225px', formatter: operateFormatter, events: operateEvents }
		]
	});
}

function statusFormat(value, row, index) {
	var disabled;
	if(row.group_user == 1) { disabled = 'disabled'; }
	else { disabled = ''; }
	if(row.ta_status == 0 ) {
		return [
			'<button class="btn btn-primary flagActive" '+disabled+'><i class="glyphicon glyphicon-ok"></i></button>'
		].join('');
	} else {
		return [
			'<button class="btn btn-danger flagActive" '+disabled+'><i class="glyphicon glyphicon-remove"></i></button>'
		].join('');		
	}
}

window.operateStatus = {
	'click .flagActive': function (e, value, row, index) {
		$.ajax({
			type		: 'get',
			url			: BASE_URL+'api/status/'+row.id+'/'+row.ta_status,
			dataType	: 'json',
			success		: function(data) {
				if (data.msg == 'success') {
					$("#listOperator").bootstrapTable('refresh', true);
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
	var disabled;
	var title;
	var textBtn;
	if(row.group_user == 1) {
		disabled	= 'disabled';
		title		= 'View';
		textBtn		= 'View';
	} else {
		disabled	= '';
		title		= 'Edit';
		textBtn		= 'Edit';
	}
	return [
		'<div class="btn-groupss">',
			'<button type="button" class="btn btn-success edit" title="'+title+'" style="margin-right:5px;width:80px;"><i class="fa fa-edit"></i> '+textBtn+'</button>',
			'<button type="button" class="btn btn-danger remove" title="Delete" '+disabled+' style="width:80px;"><i class="fa fa-trash-o"></i> Delete</button>',
		'</div>'
	].join('');
}

window.operateEvents = {
	'click .edit': function (e, value, row, index) {
		window.location = BASE_URL+'operator/form/'+row.id;
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
					url			: BASE_URL+'api/delete/'+row.id,
					dataType	: 'json',
					success: function (data) {
						if (data.success == true) {
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
					$("#listOperator").bootstrapTable('refresh');
				});
			}
		});
	}
};
