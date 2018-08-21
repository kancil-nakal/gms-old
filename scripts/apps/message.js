 'use strict';
$(function() {
	listMessageAll();
	listMessagePersonal();
});

var $table1 = $('#listMessageAll');
var $table2 = $('#listMessagePersonal');

function listMessageAll() {
	$table1.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'message/listMessageAll',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		toolbar: '#toolbarTeamAll',
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		sortOrder: 'desc',
		columns: [
			{ field: 'id', title: 'ID', align: 'center' },
			{ field: 'message_name', title: 'Title', align: 'left' },
			{ field: 'recipient_to', title: 'Recipient', align: 'left' },
			{ field: 'active_date', title: 'Modified Date', align: 'left' },
			{ field: 'status', title: 'Publish', align: 'center', width: '80px', formatter: statusFormat, events: statusEvents, sortable: true },
			{ field: null, title: 'Action', align: 'center', width: '200px', formatter: operateFormatter, events: operateEvents }
		]
	});
}

function listMessagePersonal() {
	$table2.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'message/listMessagePersonal',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		toolbar: '#toolbarTeamPersonal',
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		sortOrder: 'desc',
		columns: [
			{ field: 'id', title: 'ID', align: 'center' },
			{ field: 'message_name', title: 'Title', align: 'left' },
			{ field: 'active_date', title: 'Sent Date', align: 'left' },
			{ field: 'team_name', title: 'Driver', align: 'left' },
			{ field: 'notif_status', title: 'Status', align: 'center', width: '80px', formatter: notifFormat, sortable: true },
			{ field: null, title: 'Action', align: 'center', width: '100px', formatter: operate2Formatter, events: operate2Events }
		]
	});
}

function notifFormat(value, row, index) {
	if(row.notif_status == 0 ) {
		return [
			'Unread'
		].join('');
	} else if(row.notif_status == 1 ) {
		return [
			'Read'
		].join('');	
	} else if(row.notif_status == 2 ) {
		return [
			'Delete'
		].join('');		
	} else {
		return [
			'-'
		].join('');		
	}
}
function statusFormat(value, row, index) {
	if(row.status == 0 ) {
		return [
			'<button class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i></button>'
		].join('');
	} else {
		return [
			'<button class="btn btn-danger flagActive"><i class="glyphicon glyphicon-remove"></i></button>'
		].join('');		
	}
}

window.statusEvents = {
	'click .flagActive': function (e, value, row, index) {
		$.ajax({
			type: 'get',
			url: BASE_URL+'message/publish/'+row.id+'/'+row.status,
			dataType: 'json',
			success: function(data) {
				if (data.msg == 'success') {
					$table1.bootstrapTable('refresh', true);
					$table2.bootstrapTable('refresh', true);
				} else {
					swal({ title: "Error", text: "Failed", timer: 2000, type: "error", showConfirmButton:false });
				}
			}
		});
	}
};

function operateFormatter(value, row, index) {
	if(row.status == 0 ) {
		return [
			'<div class="btn-groupss">',
				'<button type="button" class="btn btn-success view" title="View" style="margin-right:5px;"><i class="fa fa-eye"></i> View</button>',
			'</div>'
		].join('');
	} else {
		return [
			'<div class="btn-groupss">',
				'<button type="button" class="btn btn-success edit" title="Edit" style="margin-right:5px;"><i class="fa fa-edit"></i> Edit</button>',
				'<button type="button" class="btn btn-danger remove" title="Delete"><i class="fa fa-trash-o"></i> Delete</button>',
			'</div>'
		].join('');
	}
}

window.operateEvents = {
	'click .edit': function (e, value, row, index) {
		window.location = BASE_URL+'message/form/'+row.id;
	},
	'click .view': function (e, value, row, index) {
		window.location = BASE_URL+'message/view/'+row.id;
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
					url			: BASE_URL+'message/delete/'+row.id,
					dataType	: 'json',
					success: function (data) {
						if (data.success == true) {
							swal({ title: "Success", text: "Message successfully deleted", timer: 2000, type: "success", showConfirmButton:false });
						} else {
							swal({ title: "Failed", text: "Message failed to deleted", timer: 2000, type: "error", showConfirmButton:false });
						}
					},
					error: function (jqXHR, textStatus, errorThrown) {
						swal({ title: "Error", text: jqXHR.status, timer: 2000, type: "error", showConfirmButton:false });
					}
				}).then(function () {
					$table1.bootstrapTable('refresh');
					$table2.bootstrapTable('refresh'); 
				});
			}
		});
	}
};

function operate2Formatter(value, row, index) {
	return [
		'<div class="btn-groupss">',
			'<button type="button" class="btn btn-success view" title="View" style="margin-right:5px;"><i class="fa fa-eye"></i> View</button>',
		'</div>'
	].join('');
}

window.operate2Events = {
	'click .view': function (e, value, row, index) {
		window.location = BASE_URL+'message/view/'+row.id;
	}
};
