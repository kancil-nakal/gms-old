'use strict';
$(function() {
	listCity()
});

var $table = $('#listCity');
function listCity() {
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'master_city/listcity',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		toolbar: '#toolbarCity',
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{ field: 'city_name', title: 'City', align: 'left', sortable: true },
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
		window.location = BASE_URL+'master_city/form/'+row.id;
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
					url: BASE_URL+'master_city/delete/'+row.id,
					dataType: 'json',
					success: function (data) {
						if (data.success == true) { swal({ title: "Success", text: "<b>" +row.city_name+ "</b> successfully deleted", timer: 2000, type: "success", showConfirmButton: false, html: true }); }
						else { swal({ title: "Failed", text: "<b>" +row.city_name+ "</b> failed to deleted", timer: 2000, type: "error", showConfirmButton: false, html: true }); }
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




