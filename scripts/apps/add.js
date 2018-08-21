'use strict';
$(function() {
	getArea();
});

var $table 	= $('#getArea');
var $tables = $('#getDetail');
function getArea() {
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'manage/getArea',
		cache: false,
		search : false,
		pagination : true,
		striped: true,
		sortOrder: 'desc',
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{ field: 'city', title: 'AREA', align: 'left', formatter: selectFormatter, events: selectEvents },
			{ field: 'total', title: 'TOTAL UNIT', align: 'center' }
		]
	});
}

function selectFormatter(value, row, index) {
	return [
		'<a class="getDataCity">'+row.city+'</a>'
	].join('');
}

window.selectEvents = {
	'click .getDataCity': function (e, value, row, index) {
		$("#getDetail").bootstrapTable('destroy');
		$("#selectArea").val( row.city );
		getDashboard( row.city );
	}
};

function getDashboard(area) {
	$tables.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'manage/getDetail/'+area,
		cache: false,
		search : false,
		pagination : true,
		striped: true,
		sortOrder: 'desc',
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{ field: 'merk_mobil', title: 'CAR', align: 'left', formatter: statusFormat },
			{ field: 'totals', title: 'TOTAL UNIT', align: 'center' },
			{ title: 'UNIT', align: 'center', width: '80px', formatter: inputUnit },
			{ field: null, title: 'ACTION', align: 'center', formatter: operateFormatter, events: operateEvents }
		]
	});
}

function statusFormat(value, row, index) {
	return row.merk_mobil + ' ' + row.type_mobil;
}

function inputUnit(value, row, index) {
	var unit;
	if(row.request_car == null) {
		unit = 0;
	} else {
		unit = row.request_car;
	}
	return '<input type="text" id="jmlUnit'+index+'" class="form-control text-center" value="'+unit+'" readonly="readonly">';
}

function operateFormatter(value, row, index) {
	var disabled;
	var disableds;
	if(row.request_car == row.totals) {
		disabled = 'disabled';
	} else {
		disabled = '';
	}
	if(row.request_car == null) {
		disableds = 'disabled';
	} else {
		disableds = '';
	}
	return [
		'<div class="btn-groupss">',
			'<button type="button" class="btn btn-info plus" id="plus'+index+'" title="Plus" '+disabled+' style="margin-right:5px;"> + </button>',
			'<button type="button" class="btn btn-danger min" id="min'+index+'" title="Min" '+disableds+'> - </button>',
		'</div>'
	].join('');
}

window.operateEvents = {
	'click .plus': function (e, value, row, index) {
		var total 	= row.totals;
		var area 	= $("#selectArea").val();
		var unit 	= $("#jmlUnit"+index).val();
		$("#jmlUnit"+index).val( parseInt(unit) + 1 );
		var newUnit = parseInt(unit) + 1;

		$.ajax({
			type: "GET",
			url: BASE_URL+'manage/requet/',
			dataType: 'json',
			data: 'area='+area+'&unit='+newUnit+'&merk='+row.merk_mobil+'&type='+row.type_mobil,
			success: function(data) {
				socket.emit('send-request', { } );
			}
		});
		if(unit >= (parseInt(total) - 1)) {
			$("#plus"+index).attr('disabled','disabled');
		}
		if(newUnit >= 1 || newUnit <= unit) {
			$("#min"+index).removeAttr('disabled');			
		}
	},
	'click .min': function (e, value, row, index) {
		var total 	= row.totals;
		var area 	= $("#selectArea").val();
		var unit 	= $("#jmlUnit"+index).val();
		$("#jmlUnit"+index).val( parseInt(unit) - 1 );
		var newUnit = parseInt(unit) - 1;
		$.ajax({
			type: "GET",
			url: BASE_URL+'manage/requet/',
			dataType: 'json',
			data: 'area='+area+'&unit='+newUnit+'&merk='+row.merk_mobil+'&type='+row.type_mobil,
			success: function(data) {
				socket.emit('send-request', { } );
			}
		});
		if(newUnit == 0) {
			$("#min"+index).attr('disabled','disabled');
			$("#plus"+index).removeAttr('disabled');
		}
		if(unit > newUnit) {
			$("#plus"+index).removeAttr('disabled');				
		}
	}
};

