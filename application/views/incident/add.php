<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">New Incident</h4>
		</div>
	</div>
</div>

<div class="padding">
<div class="row">
        <form action="<?php echo base_url('incident/save'); ?>" method="post">
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label"><b>Site</b></label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="site_name" value="">
                                        <span class="input-group-btn">
                                            <button class="btn info btnSiteEdit" type="button" style="height:38px;"><i class="fa fa-search"></i></button>
                                            <button class="btn danger btnSiteRemove" type="button" style="height:38px;"><i class="fa fa-trash"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <label class="col-sm-1 form-control-label"><b>Date</b></label>
                                <div class="col-sm-5" id="sandbox-container">
                                    <div class="input-group date datepicker">
                                        <input type="text" class="form-control" placeholder="Enter Join Date" name="att_date" value="">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label"><b>Team</b></label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="team_name" value="">
                                        <span class="input-group-btn">
                                            <button class="btn info btnTeamEdit" type="button" style="height:38px;"><i class="fa fa-search"></i></button>
                                            <button class="btn danger btnTeamRemove" type="button" style="height:38px;"><i class="fa fa-trash"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <label class="col-sm-1 form-control-label"><b>Shift</b></label>
                                <div class="col-sm-5">
                                    <select class="form-control" name="att_shift" id="att_shift">
                                        <option value="">--- Select Shift ---</option>
                                        <?php
                                        for($i=0;$i<count($getShift);$i++) {
                                            ?>
                                            <option value="<?php echo $getShift[$i]['id']; ?>"><?php echo $getShift[$i]['name']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <hr/>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label"><b>Incident No</b></label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" placeholder="Enter Incident No" name="incident_no" value="">
                                </div>
                                <label class="col-sm-1 form-control-label"><b>Status</b></label>
                                <div class="col-sm-5">
                                    <select class="form-control" name="status" id="status">
                                        <option value="">--- Select Status ---</option>
                                        <?php
                                        for($i=0;$i<count($getStatus);$i++) {
                                            ?>
                                            <option value="<?php echo $getStatus[$i]['id']; ?>"><?php echo $getStatus[$i]['name']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label"><b>Subject</b></label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" placeholder="Enter Subject" name="subject" value="">
                                </div>
                                <label class="col-sm-1 form-control-label"><b>Date</b></label>
                                <div class="col-sm-2" id="sandbox-container">
                                    <div class="input-group date datepicker">
                                        <input type="text" class="form-control" placeholder="Enter Incident Date" name="incident_date" value="">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                                <label class="col-sm-1 form-control-label"><b>Time</b></label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" placeholder="Enter Incident Time" name="incident_time" value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label"><b>Location</b></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" placeholder="Enter Location" name="location" value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label"><b>Parties</b></label>
                                <div class="col-sm-10">
                                    <textarea name="parties"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label"><b>Description</b></label>
                                <div class="col-sm-10">
                                    <textarea name="description"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label"><b>Action</b></label>
                                <div class="col-sm-10">
                                    <textarea name="action"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label"><b>Analysis</b></label>
                                <div class="col-sm-10">
                                    <textarea name="analysis"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label"><b>Advice</b></label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="advice"></textarea>
                                </div>
                            </div>
                        </div>
                        <!--
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label"><b>Photo</b></label>
                                <div class="col-sm-10">
                                    <textarea name="photo"></textarea>
                                </div>
                            </div>
                        </div>
                        -->
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <hr/>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label"><b>Modified By</b></label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" placeholder="Enter Modified Alias" name="modified_by" value="">
                                </div>
                                <label class="col-sm-2 form-control-label"><b>Modified Position</b></label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" placeholder="Enter Modified Position" name="modified_position" value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label"><b>Modified Alias</b></label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" placeholder="Enter Modified Alias" name="modified_alias" value="">
                                </div>
                                <label class="col-sm-2 form-control-label"><b>Modified Department</b></label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" placeholder="Enter Modified Department" name="modified_department" value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label"><b>Knowing By</b></label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" placeholder="Enter Knowing By" name="knowing_by" value="">
                                </div>
                                <label class="col-sm-2 form-control-label"><b>Knowing Position</b></label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" placeholder="Enter Knowing Position" name="knowing_position" value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label"><b>Accepted By</b></label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" placeholder="Enter Accepted By" name="accepted_by" value="">
                                </div>
                                <label class="col-sm-2 form-control-label"><b>Accepted Position</b></label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" placeholder="Enter Accepted Position" name="accepted_position" value="">
                                </div>
                            </div>
                        </div>
                    </div>			
                </div>

                <div class="dker p-a text-left">
                    <input type="hidden" name="id" value="0"></input>
                    <input type="hidden" id="site_id" name="site_id" value="0">
                    <input type="hidden" id="team_id" name="team_id" value="0">
                    <button id="btn-submit" type="submit" class="btn btn-fw info">Submit</button>
                    <a href="<?php echo base_url('incident'); ?>" class="btn btn-fw danger">Cancel</a>
                </div>
            </div>
        </form>
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
<div id="TeamName" class="modal fade black-overlay" data-backdrop="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body p-lg">
				<table class="table table-bordered" id="listTeamName"></table>
			</div>

			<div class="dker modal-footer">
				<button type="button" class="btn btn-fw danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>libs/jquery/tinymce/js/tinymce/tinymce.min.js"></script>
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

	$(".btnSiteRemove").on('click', function(e) {
		$('#site_name').val('');
		$('#site_id').val(0);
	});

	$(".btnTeamEdit").on('click', function(e) {
		$('#TeamName').modal('show');
		listTeamName();
	});

	$(".btnTeamRemove").on('click', function(e) {
		$('#team_name').val('');
		$('#team_id').val(0);
	});
    
	tinymce.init({
		selector:'textarea',
		height: 250,
		theme: 'modern',
		menubar: false,
		plugins: [
			'advlist autolink lists link image charmap print preview hr anchor pagebreak',
			'searchreplace wordcount visualblocks visualchars code fullscreen',
			'insertdatetime media nonbreaking save table contextmenu directionality',
			'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
		],
		toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | fontselect fontsizeselect",
		toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | insertdatetime preview | forecolor backcolor",
		content_css: [
			'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
			'//www.tinymce.com/css/codepen.min.css'
		],
		toolbar_items_size: 'small',
		templates: [
			{ title: 'Test template 1', content: 'Test 1' },
			{ title: 'Test template 2', content: 'Test 2' }
		]
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
			{ field: null, title: 'Action', align: 'center', width: '200px', formatter: siteFormatter, events: siteEvents }
		]
	});
}

function siteFormatter(value, row, index) {
	return [
		'<div class="btn-groupss">',
			'<button type="button" class="btn btn-success select" title="Select" style="margin-right:5px;"><i class="fa fa-edit"></i> Select</button>',
		'</div>'
	].join('');
}

window.siteEvents = {
	'click .select': function (e, value, row, index) {
		$('#site_name').val(row.site_name);
		$('#site_id').val(row.id);
		$('#SiteName').modal('hide');
	}
};

function listTeamName() {
	var $table = $('#listTeamName');
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'team/listTeam',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{ field: 'team_name', title: 'Name', align: 'left', sortable: true },
			{ field: 'position', title: 'Position', align: 'left' },
			{ field: 'site_name', title: 'Site Name', align: 'left' },
			{ field: null, title: 'Action', align: 'center', width: '200px', formatter: teamFormatter, events: teamEvents }
		]
	});
}

function teamFormatter(value, row, index) {
	return [
		'<div class="btn-groupss">',
			'<button type="button" class="btn btn-success select" title="Select" style="margin-right:5px;"><i class="fa fa-edit"></i> Select</button>',
		'</div>'
	].join('');
}

window.teamEvents = {
	'click .select': function (e, value, row, index) {
		$('#team_name').val(row.team_name);
		$('#team_id').val(row.id);
		$('#TeamName').modal('hide');
	}
};
</script>