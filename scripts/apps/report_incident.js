 'use strict';
$(function() {
	listReport();
	$('#report_detail').on('hide.bs.modal', function (event) {
		$("#reportDetail").bootstrapTable('destroy');
	});
});

var maps;
var routeCoordinates;
var neighborhoods 	= [];
var markers 		= [];
var $table = $('#listReport');
function listReport() {
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'report_incident/getReport',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		sortOrder: 'desc',
		columns: [
			{ field: 'incident_no', title: 'Incident No', align: 'left' },
			{ field: 'subject', title: 'Subject', align: 'left' },
			{ field: 'incident_date', title: 'Date', align: 'left' },
			{ field: 'incident_time', title: 'Time', align: 'left' },
			{ field: 'site_name', title: 'Site Name', align: 'left' },
			{ field: null, title: 'Action', align: 'center', width: '250px', formatter: operateFormatter, events: operateEvents }
		]
	});
}

function operateFormatter(value, row, index) {
	return [
		'<div class="btn-groupss">',
			'<button type="button" class="btn btn-success excel" title="Excel"><i class="fa fa fa-file-excel-o"></i> Export to PDF</button>',
		'</div>'
	].join('');
}

window.operateEvents = {
	'click .excel': function (e, value, row, index) {
        $('<a href="'+BASE_URL+'report_incident/export/'+row.id+'" target="blank"></a>')[0].click();  
	}	
};