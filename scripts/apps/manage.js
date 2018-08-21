'use strict';
$(function() {
	listCar();
});

var $table = $('#listCar');
function listCar() {
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'manage/listCar',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		toolbar: '#toolbarDriver',
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
//		pageSize: 8,
		columns: [
			{ field: 'city', title: 'City', align: 'left' },
			{ field: 'merk_mobil', field: 'member_name', title: 'Car', align: 'left', formatter: statusFormat },
			{ field: 'total', title: 'Total', align: 'center' }
		]
	});
}

function statusFormat(value, row, index) {
	return row.merk_mobil + ' ' + row.type_mobil;
}
