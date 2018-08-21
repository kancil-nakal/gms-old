'use strict';
$(function() {
	listTeam();
});

var $table = $('#listTeam'); 
function listTeam() {
    console.log('test');
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'team/listTeam',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		toolbar: '#toolbarTeam',
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{ field: 'team_name', title: 'Name', align: 'left', sortable: true },
			{ field: 'position', title: 'Position', align: 'left', sortable: true },
			{ field: 'mobile_phone', title: 'Phone', align: 'left' },
			{ field: 'site_name', title: 'Site Name', align: 'left', sortable: true },
			{ field: 'shift_name', title: 'Shift', align: 'left', sortable: true },
			{ field: 'team_status', title: 'Team Status', align: 'center', width: '80px', formatter: teamFormat, events: teamEvents, sortable: true },
			{ field: 'app_status', title: 'App Status', align: 'center', width: '80px', formatter: appFormat, events: appEvents, sortable: true },
			{ field: 'uuid_status', title: 'Token', align: 'center', width: '80px', formatter: tokenFormat, events: tokenEvents, sortable: true },
			{ field: null, title: 'Action', align: 'center', width: '190px', formatter: actionFormat, events: actionEvents }
		]
	});
}

function tokenFormat(value, row, index) {
	if(row.uuid_status == 0 ) {
		return [
			'<button class="btn btn-primary flagActive"><i class="glyphicon glyphicon-ok"></i></button>'
		].join('');
	} else {
		return [
			'<button class="btn btn-danger flagActive"><i class="glyphicon glyphicon-remove"></i></button>'
		].join('');		
	}
}

window.tokenEvents = {
	'click .flagActive': function (e, value, row, index) {
		$.ajax({
			type: 'get',
			url: BASE_URL+'team/uuid_status/'+row.id+'/'+row.uuid_status,
			dataType: 'json',
			success: function(data) {
				if (data.msg == 'success') {
					$table.bootstrapTable('refresh', true);
				} else {
					swal({ title: "Error", text: "Failed", timer: 2000, type: "error", showConfirmButton:false });
				}
			}
		});
	}
};

function teamFormat(value, row, index) {
	if(row.team_status == 0 ) {
		return [
			'<button class="btn btn-primary flagActive"><i class="glyphicon glyphicon-ok"></i></button>'
		].join('');
	} else {
		return [
			'<button class="btn btn-danger flagActive"><i class="glyphicon glyphicon-remove"></i></button>'
		].join('');		
	}
}

window.teamEvents = {
	'click .flagActive': function (e, value, row, index) {
		$.ajax({
			type: 'get',
			url: BASE_URL+'team/team_status/'+row.id+'/'+row.team_status,
			dataType: 'json',
			success: function(data) {
				if (data.msg == 'success') {
					$table.bootstrapTable('refresh', true);
				} else {
					swal({ title: "Error", text: "Failed", timer: 2000, type: "error", showConfirmButton:false });
				}
			}
		});
	}
};

function appFormat(value, row, index) {
	if(row.app_status == 0 ) {
		return [
			'<button class="btn btn-primary flagActive"><i class="glyphicon glyphicon-ok"></i></button>'
		].join('');
	} else {
		return [
			'<button class="btn btn-danger flagActive"><i class="glyphicon glyphicon-remove"></i></button>'
		].join('');		
	}
}

window.appEvents = {
	'click .flagActive': function (e, value, row, index) {
		$.ajax({
			type: 'get',
			url: BASE_URL+'team/app_status/'+row.id+'/'+row.app_status,
			dataType: 'json',
			success: function(data) {
				if (data.msg == 'success') {
					$table.bootstrapTable('refresh', true);
				} else {
					swal({ title: "Error", text: "Failed", timer: 2000, type: "error", showConfirmButton:false });
				}
			}
		});
	}
};

function actionFormat(value, row, index) {
	return [
		'<div class="btn-groupss">',
			'<button type="button" class="btn btn-success edit" title="Edit" style="margin-right:5px;"><i class="fa fa-edit"></i> Edit</button>',
			'<button type="button" class="btn btn-danger remove" title="Delete"><i class="fa fa-trash-o"></i> Delete</button>',
		'</div>'
	].join('');
}

window.actionEvents = {
	'click .edit': function (e, value, row, index) {
		window.location = BASE_URL+'team/form/'+row.id;
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
					url			: BASE_URL+'team/delete/'+row.id,
					dataType	: 'json',
					success: function (data) {
						if (data.success == true) {
							swal({title: "Success", text: "User successfully deleted", timer: 2000, type: "success", showConfirmButton:false });
						} else {
							swal({ title: "Failed", text: "User failed to deleted", timer: 2000, type: "error", showConfirmButton:false });
						}
					},
					error: function (jqXHR, textStatus, errorThrown) {
						swal({ title: "Error", text: jqXHR.status, timer: 2000, type: "error", showConfirmButton:false });
					}
				}).then(function () {
					$table.bootstrapTable('refresh');
				});
			}
		});
	}
};
