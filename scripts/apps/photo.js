'use strict';
$(function() {
	listPhoto();
});

var $table = $('#listPhoto');
function listPhoto() {
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'photo/listPhoto',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		pageSize: 5,
		columns: [
			{ field: 'member_name', title: 'Driver', align: 'left' },
			{ field: 'no_pol', title: 'No Polisi', align: 'left' },
			{ field: 'ta_name', title: 'Client', align: 'left' },
			{ field: 'rit', title: 'RIT', align: 'left' },
			{ field: 'pic_start', title: 'Photo Awal', align: 'center', formatter: imgStart },
			{ field: 'img_end', title: 'Photo Akhir', align: 'center', formatter: imgEnd }
		]
	});
}

function imgStart(value, row, index) {
	if(row.pic_start != null) {
		return [
			'<a class="example-image-link" href="http://tempeladapi.mesinrusak.com/uploads/'+row.pic_start+'.jpg" data-lightbox="'+row.member_name+'" data-title="'+row.date_start+ ' - ' +row.time_start+'">',
				'<img src="http://tempeladapi.mesinrusak.com/uploads/'+row.pic_start+'_thumbs.jpg" alt="" class="img-responsive" style="width:100px;"><br/>',
				'<span><b>'+row.date_start+ ' - ' +row.time_start+'</b></span>',
			'</a>'
		].join('');
	} else {
		return '';
	}
}

function imgEnd(value, row, index) {
	if(row.pic_end != null) {
		return [
			'<a class="example-image-link" href="http://tempeladapi.mesinrusak.com/uploads/'+row.pic_end+'.jpg" data-lightbox="'+row.member_name+'" data-title="'+row.date_end+ ' - ' +row.time_end+'">',
				'<img src="http://tempeladapi.mesinrusak.com/uploads/'+row.pic_end+'_thumbs.jpg" alt="" class="img-responsive" style="width:100px;"><br/>',
				'<span><b>'+row.date_end+ ' - ' +row.time_end+'</b></span>',
			'</a>'
		].join('');
	} else {
		return '';
	}
}
