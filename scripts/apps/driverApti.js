'use strict';
$(function() {
	listDriver();
});

var $table = $('#listMember');
function listDriver() {
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'driver/listDriver',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{ field: 'member_name', title: 'Name', align: 'left', sortable: true },
			{ field: 'email_address', title: 'Email', align: 'left' },
			{ field: 'mobile_phone', title: 'Phone', align: 'left' },
			{ field: 'client_name', title: 'Client Name', align: 'left' }
		]
	});
}
