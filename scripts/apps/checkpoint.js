"use strict";
$(function () {
	listCheckpoint();
});

var $table = $("#listCheckpoint");
function listCheckpoint() {
	$table.bootstrapTable({
		method: "GET",
		url: BASE_URL + "checkpoint/listcheckpoint",
		cache: false,
		search: true,
		pagination: true,
		striped: true,
		sidePagination: "server",
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{ field: "att_date", title: "Date", align: "left", sortable: false },
			{ field: "shift_name", title: "Shift", align: "left", sortable: false },
			{
				field: "site_name",
				title: "Site Name",
				align: "left",
				sortable: false,
			},
			{
				field: "checkpoint_name",
				title: "Checkpoint",
				align: "left",
				sortable: false,
			},
			{
				field: "team_name",
				title: "Team Name",
				align: "left",
				sortable: false,
			},
			{
				field: "created_date",
				title: "Log Date",
				align: "left",
				sortable: false,
			},
		],
	});
}
