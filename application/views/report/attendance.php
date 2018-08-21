<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">Attendance Report</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<div class="box">
			<div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <label class="col-sm-1 form-control-label"><b>Site</b></label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="site_name">
                                    <span class="input-group-btn">
                                        <button class="btn info btnSiteEdit" type="button" style="height:38px;"><i class="fa fa-search"></i></button>
                                        <button class="btn danger btnSiteRemove" type="button" style="height:38px;"><i class="fa fa-trash"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group row">
                            <label class="col-sm-1 form-control-label"><b>Date From</b></label>
                            <div class="col-sm-2" id="sandbox-container">
                                <div class="input-group date datepicker">
                                    <input type="text" class="form-control" placeholder="Enter Date From" name="date_from" id="date_from">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                </div>
                            </div>
                            <label class="col-sm-1 form-control-label"><b>Date Thru</b></label>
                            <div class="col-sm-2" id="sandbox-container">
                                <div class="input-group date datepicker">
                                    <input type="text" class="form-control" placeholder="Enter Date Thru" name="date_thru" id="date_thru">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
            
            <div class="dker p-a text-left">
                <input type="hidden" id="site_id" name="site_id">
                <button type="button" class="btn btn-fw success" id="export_excel">Export to Excel</button>
            </div>
		</div>
	</div>
</div>

<div id="SiteName" class="modal fade black-overlay" data-backdrop="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body p-lg">
				<table class="table table-bordered" id="listSiteName"></table>
			</div>

			<div class="dker modal-footer">
				<button type="button" class="btn btn-fw danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function() {
	$(".datepicker").datepicker({
		autoclose: true,
		todayHighlight: true,
        format: 'yyyy-mm-dd'
	});
    
	$(".btnSiteEdit").on('click', function(e) {
		$('#SiteName').modal('show');
		listSiteName();
	});
    
	$("#export_pdf").on('click', function(e) {
		var site_id = $('#site_id').val();
		var date_from = $('#date_from').val();
		var date_thru = $('#date_thru').val();
        var url = BASE_URL+'report_attendance/export_pdf/'+site_id+'/'+date_from+'/'+date_thru;
		window.open(url, '_blank');
	});
    
	$("#export_excel").on('click', function(e) {
		var site_id = $('#site_id').val();
		var date_from = $('#date_from').val();
		var date_thru = $('#date_thru').val();
        var url = BASE_URL+'report_attendance/export_excel/'+site_id+'/'+date_from+'/'+date_thru;
		window.open(url, '_blank');
	});
});



function listSiteName() {
	var $table = $('#listSiteName');
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'site/listSite',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{ field: 'site_name', title: 'Site Name', align: 'left', sortable: true },
			{ field: 'client_name', title: 'Client Name', align: 'left', sortable: true },
			{ field: null, title: 'Action', align: 'center', width: '200px', formatter: operateFormatter, events: operateEvents }
		]
	});
}

function operateFormatter(value, row, index) {
	return [
		'<div class="btn-groupss">',
			'<button type="button" class="btn btn-success select" title="Select" style="margin-right:5px;"><i class="fa fa-edit"></i> Select</button>',
		'</div>'
	].join('');
}

window.operateEvents = {
	'click .select': function (e, value, row, index) {
		$('#site_name').val(row.site_name);
		$('#site_id').val(row.id);
		$('#SiteName').modal('hide');
	}
};
</script>