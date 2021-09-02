"use strict";
$(function () {
	listClient();
});

var $table = $("#listClient");
function listClient() {
	$table.bootstrapTable({
		method: "GET",
		url: BASE_URL + "api/manageData/2",
		cache: false,
		search: true,
		pagination: true,
		striped: true,
		toolbar: "#toolbarMember",
		sidePagination: "server",
		rowStyle: "rowStyle",
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{ field: "ta_name", title: "Name", align: "left", sortable: true },
			{ field: "ta_email", title: "Email", align: "left" },
			{ field: "ta_phone", title: "Phone", align: "right" },
			{
				field: "ta_status",
				title: "Enabled",
				align: "center",
				width: "90px",
				formatter: statusFormat,
				events: operateStatus,
			},
			{
				field: null,
				title: "Action",
				align: "center",
				width: "225px",
				formatter: operateFormatter,
				events: operateEvents,
			},
		],
	});
}

function rowStyle(row, index) {
	if (row.status == "C") {
		return {
			classes: "tl-danger",
		};
	} else if (row.status == "W") {
		return {
			classes: "tl-warning",
		};
	}
	return {};
}

function selectFormatter(value, row, index) {
	return ['<a class="getClientName">' + row.ta_name + "</a>"].join("");
}

window.selectEvents = {
	"click .getClientName": function (e, value, row, index) {
		$("#idClient").val(row.id);
		$("#frm_detailReport").modal("show");
		getArea(row.id);
		getDashboard("all", row.id);
		$.ajax({
			type: "GET",
			url: BASE_URL + "dashboard/getSaldo/" + $("#idClient").val(),
			dataType: "json",
			success: function (data) {
				$("#jarak").html(data[0].total_km + " KM");
				$("#viewer").html(data[0].total_viewer);
				$("#akumulasi").html(data[0].total_saldo + " IDR");
				$("#sisa").html(data[0].saldo + " IDR");
			},
		});
		initMap();
		getMap();
	},
};

function getArea(id) {
	var $table = $("#getArea");
	$table.bootstrapTable({
		method: "GET",
		//		url: BASE_URL+'dashboard/getArea/'+id,
		url: BASE_URL + "dashboard/getArea",
		cache: false,
		search: false,
		pagination: true,
		striped: true,
		sortOrder: "desc",
		sidePagination: "server",
		smartDisplay: false,
		onlyInfoPagination: false,
		pageSize: 5,
		columns: [
			{
				field: "city",
				title: "AREA",
				align: "left",
				formatter: selectFormatterArea,
				events: selectEventsArea,
			},
			{ field: "total", title: "JUMLAH UNIT", align: "center" },
		],
	});
}

function selectFormatterArea(value, row, index) {
	return ['<a class="getDataCity">' + row.city + "</a>"].join("");
}

window.selectEventsArea = {
	"click .getDataCity": function (e, value, row, index) {
		$("#getDetail").bootstrapTable("destroy");
		$("#area").val(row.city);
		getDashboard(row.city, row.id);
	},
};

function getDashboard(area, id) {
	var $table = $("#getDetail");
	$table.bootstrapTable({
		method: "GET",
		//		url: BASE_URL+'dashboard/getDetail/'+id+'/'+area,
		url: BASE_URL + "dashboard/getDetail/" + area,
		cache: false,
		search: false,
		pagination: true,
		striped: true,
		sortOrder: "asc",
		pageSize: 5,
		sidePagination: "server",
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{ field: "member_name", title: "Driver", align: "left" },
			{ field: null, title: "Mobil", align: "left", formatter: statusFormats },
			{
				field: "driverStatus",
				title: "Status",
				align: "center",
				formatter: statusDriver,
			},
			{
				field: null,
				title: "Detail",
				align: "center",
				formatter: operateFormatters,
			},
		],
	});
}

function statusFormats(value, row, index) {
	return row.merk_mobil + " " + row.type_mobil;
}

function statusDriver(value, row, index) {
	if (row.driverStatus == 1) {
		return [
			'<span class="label label-lg primary"><i class="fa fa-automobile"></i></span>',
		].join("");
	} else {
		return [
			'<span class="label label-lg danger"><i class="fa fa-automobile"></i></span>',
		].join("");
	}
}

function operateFormatters(value, row, index) {
	return [
		'<button class="btn btn-info driverDetail" onclick="tesDidi(' +
			row.mobile_phone +
			');" title="Detail">Detail</button>',
	].join("");
}

function tesDidi(phone) {
	$("#frm_detailDriver").modal("show");
	$.ajax({
		type: "GET",
		url: BASE_URL + "dashboard/getmarker",
		dataType: "json",
		data: "id=0" + phone,
		success: function (data) {
			var lat;
			var lon;

			$("#map_canvas").googleMap({
				zoom: 13,
				coords: [-6.2664721, 106.8440418],
			});

			if (data != null) {
				lat = data.lat;
				lon = data.long;

				$("#map_canvas").addMarker({
					coords: [lat, lon],
				});
			}
		},
	});
}

function statusFormat(value, row, index) {
	if (row.ta_status == 0) {
		return [
			'<button class="btn btn-primary flagActive"><i class="glyphicon glyphicon-ok"></i></button>',
		].join("");
	} else {
		return [
			'<button class="btn btn-danger flagActive"><i class="glyphicon glyphicon-remove"></i></button>',
		].join("");
	}
}

window.operateStatus = {
	"click .flagActive": function (e, value, row, index) {
		$.ajax({
			type: "get",
			url: BASE_URL + "api/status/" + row.id + "/" + row.ta_status,
			dataType: "json",
			success: function (data) {
				if (data.msg == "success") {
					$table.bootstrapTable("refresh", true);
				} else {
					swal({
						title: "Error",
						text: "Failed",
						timer: 2000,
						type: "error",
						showConfirmButton: false,
					});
				}
			},
		});
	},
};

function operateFormatter(value, row, index) {
	return [
		'<div class="btn-groupss">',
		'<button type="button" class="btn btn-success edit" title="Edit" style="margin-right:5px;"><i class="fa fa-edit"></i> Edit</button>',
		'<button type="button" class="btn btn-danger remove" title="Delete"><i class="fa fa-trash-o"></i> Delete</button>',
		"</div>",
	].join("");
}

window.operateEvents = {
	"click .edit": function (e, value, row, index) {
		window.location = BASE_URL + "client/form/" + row.id;
	},
	"click .remove": function (e, value, row, index) {
		swal(
			{
				title: "Are you sure want to delete this data?",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#F56954",
				confirmButtonText: "Yes",
				cancelButtonText: "No",
				closeOnConfirm: false,
				closeOnCancel: true,
			},
			function (isConfirm) {
				if (isConfirm) {
					$.ajax({
						type: "GET",
						url: BASE_URL + "api/delete/" + row.id,
						dataType: "json",
						success: function (data) {
							if (data.success == true) {
								swal({
									title: "Success",
									text: "User successfully deleted",
									timer: 2000,
									type: "success",
									showConfirmButton: false,
								});
							} else {
								swal({
									title: "Failed",
									text: "User failed to deleted",
									timer: 2000,
									type: "error",
									showConfirmButton: false,
								});
							}
						},
						error: function (jqXHR, textStatus, errorThrown) {
							swal({
								title: "Error",
								text: jqXHR.status,
								timer: 2000,
								type: "error",
								showConfirmButton: false,
							});
						},
					}).then(function () {
						$table.bootstrapTable("refresh");
					});
				}
			}
		);
	},
};
