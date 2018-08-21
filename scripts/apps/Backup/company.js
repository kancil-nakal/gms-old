$(function() {
	listCompany()
});

function listCompany() {
	var $table = $('#listCompany');
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'api/manageData/4',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		toolbar: '#toolbarMember',
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{ field: 'ta_name', title: 'Name', align: 'left', sortable: true, formatter: selectFormatter, events: selectEvents },
			{ field: 'ta_email', title: 'Email', align: 'left' },
			{ field: 'ta_phone', title: 'Phone', align: 'right' },
			{ field: 'ta_status', title: 'Enabled', align: 'center', width: '90px', formatter: statusFormat, events: operateStatus },
			{ field: null, title: 'Action', align: 'center', width: '225px', formatter: operateFormatter, events: operateEvents }
		]
	});
}

function selectFormatter(value, row, index) {
	return [
		'<a class="getClientName">'+row.ta_name+'</a>'
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

function statusFormat(value, row, index) {
	if(row.ta_status == 0 ) {
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
			url			: BASE_URL+'company/status/'+row.id+'/'+row.ta_status,
			dataType	: 'json',
			success		: function(data) {
				if (data.msg == 'success') {
					$("#listCompany").bootstrapTable('refresh', true);
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
		window.location = BASE_URL+'company/form/'+row.id;
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
					url			: BASE_URL+'company/delete/'+row.id,
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
					$("#listCompany").bootstrapTable('refresh');
				});
			}
		});
	}
};
