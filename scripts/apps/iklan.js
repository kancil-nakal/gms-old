'use strict';
$(function() {
	listIklan()
});

var $table = $('#listIklan');
function listIklan() {
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'iklan/listIklan',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		toolbar: '#toolbarIklan',
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{ field: 'ta_name', title: 'Name', align: 'left', sortable: true },
			{ field: 'images', title: 'Iklan', align: 'center', width: '350px', formatter: imgFormat },
			{ field: null, title: 'Action', align: 'center', width: '225px', formatter: operateFormatter, events: operateEvents }
		]
	});
}

function imgFormat(value, row, index) {
	if(row.images != null) {
		return [
			'<a class="example-image-link" href="'+BASE_URL+row.images+'" data-lightbox="'+row.ta_name+'" data-title="'+row.ta_name+'">',
				'<img src="'+row.images+'" alt="" class="img-responsive" style="width:100px;height:100px;"><br/>',
			'</a>'
		].join('');
	} else {
		return '';
	}
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
		window.location = BASE_URL+'iklan/form/'+row.id;
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
					url			: BASE_URL+'iklan/delete/'+row.id,
					dataType	: 'json',
					success: function (data) {
						if (data.success == true) {
							swal({
								title	: "Success",
								text	: "Banner successfully deleted",
								timer	: 2000,
								type	: "success",
								showConfirmButton:false
							});
						} else {
							swal({
								title	: "Failed",
								text	: "Banner failed to deleted",
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
					$table.bootstrapTable('refresh');
				});
			}
		});
	}
};




