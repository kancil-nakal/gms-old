(function ($) {
	'use strict';

	function listUser() {
		var $table = $('#listUser');
		$table.bootstrapTable({
			method: 'GET',
			url: BASE_URL+'master/listAdmin/99',
			cache: false,
			search : true,
			pagination : true,
			striped: true,
			toolbar: '#toolbarUser',
			sidePagination: 'server',
			smartDisplay: false,
			onlyInfoPagination: false,
			columns: [{
				field: 'member_name',
				title: 'Name',
				align: 'left',
				sortable: true,
			}, {
				field: 'email_address',
				title: 'Email',
				align: 'left',
			}, {
				field: 'mobile_phone',
				title: 'Phone',
				align: 'left',
			}, {
				field: 'user_status',
				title: 'Enabled',
				align: 'center',
				width: '80px',
				formatter: statusFormat,
				events: operateStatus
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
		var disabled;
		if(row.id == '1') {
			disabled = 'disabled';
		} else {
			disabled = '';
		}
		if(row.user_status == 0 ) {
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
				url			: BASE_URL+'master/user_status/'+row.id+'/'+row.user_status,
				dataType	: 'json',
				success		: function(data) {
					if (data.msg == 'success') {
						$("#listUser").bootstrapTable('refresh', true);
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
		if(row.id == '1') {
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

	$("#submitUser").on('submit', function(e) {
		e.preventDefault();
		var form = $('#submitUser').serialize();

		$.ajax({
			type		: 'POST',
			url			: BASE_URL+'master/submit_admin',
			data		: form,
			dataType	: 'json',
			success		: function (data) {
				if (data.msg == 'success') {
					swal({title:"Success", text:"User " + data.display_name + " successfully " + data.msg, timer:2000, type:"success", showConfirmButton:false});
				} else {
					swal({title:"Failed", text:"User " + data.display_name + "  failed to " + data.msg, timer:2000, type:"error", showConfirmButton:false});
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
			$('#frm_user').modal('hide');
			$("#listUser").bootstrapTable('refresh', true);
		});
	});

	listUser();

	$(".show-modal").on('click', function(e) {
		$('#frm_user').modal('show');
		$('#title').html('New');
		$('#Y').prop('checked', true);
	});

	$('#frm_user').on('show.bs.modal', function (e) {

	});

	$('#frm_user').on('hide.bs.modal', function (e) {
		$("ul.parsley-errors-list").remove();
		$("input").removeClass("parsley-error");
	});
})(jQuery);