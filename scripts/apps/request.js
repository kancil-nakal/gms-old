'use strict';
$(function() {
	listRequestCar();
});

var $table = $('#listRequestCar');
function listRequestCar() {
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'request/listRequest',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		toolbar: '#toolbarMember',
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{ field: 'name', title: 'Client', align: 'left', sortable: true },
			{ field: 'request_date', title: 'Request Date', align: 'right', formatter: changeDate },
			{ field: 'total', title: 'Total Unit', align: 'center' },
			{ field: null, title: 'Action', align: 'center', width: '225px', formatter: operateFormatter, events: operateEvents }
		]
	});
}

function changeDate(value, row, index) {
	var date = row.request_date.split('-');
	return date[2]+'-'+date[1]+'-'+date[0];
}

function operateFormatter(value, row, index) {
	return [
		'<div class="btn-groupss">',
			'<button class="btn btn-primary detail" style="margin-right:5px;"><i class="fa fa-search"></i> View</button>',
			'<button class="btn btn-success export"><i class="fa fa-file-excel-o"></i> Export</button>',
		'</div>'
	].join('');
}

window.operateEvents = {
	'click .detail': function (e, value, row, index) {
		$("#detailRequestCar").bootstrapTable('destroy');
		$('#frm_detail1').modal('show');
		detailRequestCar(row.client_id);
	},
	'click .export': function (e, value, row, index) {
		window.location = BASE_URL+'request/exportExcel/'+row.name+'/'+row.client_id;
	}
};

function detailRequestCar(client_id) {
	var $table = $('#detailRequestCar');
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'request/detailRequest/'+client_id,
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{
				field: 'car',
				title: 'Car',
				align: 'left',
				formatter: formatCar
			},
			{
				field: 'city',
				title: 'City',
				align: 'left'
			},
			{
				field: 'request_car',
				title: 'Total Request',
				align: 'center'
			}
		]
	});
}

function formatCar(value, row, index) {
	return row.merk_mobil + ' ' + row.type_mobil;
}
