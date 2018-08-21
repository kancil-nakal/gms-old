'use strict';
$(function() {
	listActivity()
});

var $table = $('#listTeamComplaint');
function listActivity() {
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'team_complaint/listteamcomplaint',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{ field: 'team_name', title: 'Team Name', align: 'left', sortable: true },
			{ field: 'problem', title: 'Problem', align: 'left', sortable: false },
			{ field: 'created_date', title: 'Created Date', align: 'left', sortable: false },
			{ field: 'status_name', title: 'Status', align: 'left', sortable: true },
			{ field: null, title: 'Action', align: 'center', width: '225px', formatter: operateFormatter, events: operateEvents }
		]
	});
}

function operateFormatter(value, row, index) {
	return [
		'<div class="btn-groupss">',
			'<button type="button" class="btn btn-success edit" title="Edit" style="margin-right:5px;"><i class="fa fa-edit"></i> Reply</button>',
		'</div>'
	].join('');
}

window.operateEvents = {
	'click .edit': function (e, value, row, index) {
		window.location = BASE_URL+'team_complaint/form/'+row.id;
	},
};




