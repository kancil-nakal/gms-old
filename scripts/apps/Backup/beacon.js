(function ($) {
	'use strict';

	function listUser() {
		var $table = $('#listBeacon');
		$table.bootstrapTable({
			method: 'GET',
			url: BASE_URL+'master/listBeacon',
			cache: false,
			search : true,
			pagination : true,
			striped: true,
			sortOrder: 'desc',
			toolbar: '#toolbarBeacon',
			sidePagination: 'server',
			smartDisplay: false,
			onlyInfoPagination: false,
			columns: [{
				field: 'beacon_id',
				title: 'Beacon ID',
				align: 'left',
				sortable: true,
				// width: '233px'
			}, {
				field: 'beacon_status',
				title: 'Status',
				align: 'center',
				width: '140px',
				formatter: statusFormat
			}, {
				field: 'created_date',
				title: 'Created Date',
				align: 'center',
				width: '180px'
			}, {
				field: null,
				title: 'Action',
				align: 'center',
				width: '250px',
				formatter: operateFormatter,
				events: operateEvents
			}]
		});
	}

	function statusFormat(value, row, index) {
		if(row.beacon_status == 0 ) {
			return [
				'<a class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Ready </a>'
			].join('');
		} else {
			return [
				'<a class="btn btn-success"><i class="glyphicon glyphicon-ok"></i> Used</a>'
			].join('');		
		}
	}

	function operateFormatter(value, row, index) {
		var disabled;
		if(row.beacon_status == 1) {
			disabled = 'disabled';
		} else {
			disabled = '';
		}
		return [
			'<div class="btn-groupss">',
				'<button type="button" class="btn btn-success edit" title="Edit" '+disabled+' style="margin-right:5px;"><i class="fa fa-edit"></i> Edit</button>',
				'<button type="button" class="btn btn-danger remove" title="Delete" '+disabled+'><i class="fa fa-trash-o"></i> Delete</button>',
			'</div>'
		].join('');
	}

	window.operateEvents = {
		'click .edit': function (e, value, row, index) {
			$('#frm_user').modal('show');
			$('#title').html('Edit');
			$("input[name=display_name]").val(row.display_name);
			$("input[name=user_email]").val(row.user_email);
			$("input[name=user_login]").val(row.user_login);
			$('input[name=user_login]').prop('readonly', true);
			if (row.user_status == 0) {
				$('#Y').prop('checked', true);
			} else {
				$('#N').prop('checked', true);
			}
			$("input[name=id]").val(row.id);

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
						url			: BASE_URL+'user/delete/'+row.id,
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
						$("#listUser").bootstrapTable('refresh');
					});
				}
			});
		}
	};

	$("#submitBeacon").on('submit', function(e) {
		e.preventDefault();
		var form = $('#submitBeacon').serialize();
		$.ajax({
			type		: 'POST',
			url			: BASE_URL+'master/submit_beacon',
			data		: form,
			dataType	: 'json',
			success		: function (data) {
				if (data.success == true) {
					swal({title:"Success", text:"Beacon ID successfully " + data.msg, timer:2000, type:"success", showConfirmButton:false});
				} else {
					swal({title:"Failed", text:"Beacon ID failed to " + data.msg, timer:2000, type:"error", showConfirmButton:false});
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				swal({
					title	: "Error",
					text	: "Field is mandatory",
					type	: "error",
					showConfirmButton: true
				});
			}
		}).then(function () {
			$('#frm_beacon').modal('hide');
			$("#listBeacon").bootstrapTable('refresh', true);
		});
	});

	listUser();

	$(".show-modal").on('click', function(e) {
		$('.beaconStatus').hide();
		$('#frm_beacon').modal('show');
		$('#title').html('New');
	});

	$('#frm_user').on('show.bs.modal', function (e) {

	});

	$('#frm_user').on('hide.bs.modal', function (e) {
		$("ul.parsley-errors-list").remove();
		$("input").removeClass("parsley-error");
	});
})(jQuery);