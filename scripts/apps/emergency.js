'use strict';
$(function() {
	listEmergency()
});

var $table = $('#listEmergency');
function listEmergency() {
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'emergency/listemergency',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		toolbar: '#toolbarEmergency',
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{ field: 'contacttype_name', title: 'Type', align: 'left', sortable: true },
			{ field: 'name', title: 'Name', align: 'left', sortable: true },
			{ field: 'phone', title: 'Phone', align: 'left', sortable: false },
			{ field: 'city_name', title: 'City', align: 'left', sortable: true },
			{ field: 'status', title: 'Status', align: 'center', width: '80px', formatter: statusFormat, events: statusEvents, sortable: true },
			{ field: null, title: 'Action', align: 'center', width: '225px', formatter: operateFormatter, events: operateEvents }
		]
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
		window.location = BASE_URL+'emergency/form/'+row.id;
	},
	'click .remove': function (e, value, row, index) {
		swal({
			title: "Are you sure want to delete this data?",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#F56954",
			confirmButtonText: "Yes",
			cancelButtonText: "No",
			closeOnConfirm: false,
			closeOnCancel: true
		}, function (isConfirm) {
			if (isConfirm) {
				$.ajax({
					type: 'GET',
					url: BASE_URL+'emergency/delete/'+row.id,
					dataType: 'json',
					success: function (data) {
						if (data.success == true) { swal({ title: "Success", text: "<b>" +row.name+ "</b> successfully deleted", timer: 2000, type: "success", showConfirmButton: false, html: true }); }
						else { swal({ title: "Failed", text: "<b>" +row.name+ "</b> failed to deleted", timer: 2000, type: "error", showConfirmButton: false, html: true }); }
					},
					error: function (jqXHR, textStatus, errorThrown) {
						swal({ title: "Error", text: jqXHR.status, timer: 2000, type: "error", showConfirmButton: false });
					}
				}).then(function () {
					$table.bootstrapTable('refresh');
				});
			}
		});
	}
};

function statusFormat(value, row, index) {
	if(row.status == 0 ) {
		return [
			'<button class="btn btn-primary flagActive"><i class="glyphicon glyphicon-ok"></i></button>'
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
			url: BASE_URL+'emergency/status/'+row.id+'/'+row.status,
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