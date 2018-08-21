<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">Edit Visitor</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<?php foreach ($getData as $row) { ?>
			<form action="<?php echo base_url('visitor/save'); ?>" method="post">
				<div class="box">
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group row">
									<label class="col-sm-2 form-control-label"><b>Site</b></label>
									<div class="col-sm-4">
										<div class="input-group">
											<input type="text" class="form-control" id="site_name" value="<?php echo $row->site_name;?>">
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
									<label class="col-sm-2 form-control-label"><b>Team</b></label>
									<div class="col-sm-4">
										<div class="input-group">
											<input type="text" class="form-control" id="team_name" value="<?php echo $row->team_name;?>">
											<span class="input-group-btn">
												<button class="btn info btnTeamEdit" type="button" style="height:38px;"><i class="fa fa-search"></i></button>
												<button class="btn danger btnTeamRemove" type="button" style="height:38px;"><i class="fa fa-trash"></i></button>
											</span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group row">
									<label class="col-sm-2 form-control-label"><b>Date</b></label>
                                    <div class="col-sm-4" id="sandbox-container">
                                        <div class="input-group date datepicker">
                                            <input type="text" class="form-control" placeholder="Enter Join Date" name="att_date" value="<?php echo $row->att_date; ?>">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group row">
									<label class="col-sm-2 form-control-label"><b>Shift</b></label>
                                    <div class="col-sm-4">
										<select class="form-control" name="att_shift" id="att_shift">
											<option value="">--- Select Shift ---</option>
											<?php
											for($i=0;$i<count($getShift);$i++) {
												if ($getShift[$i]['id'] == $row->att_shift) {
													$select = 'selected="selected"';
												} else {
													$select = '';
												}
												?>
												<option <?php echo $select;?> value="<?php echo $getShift[$i]['id']; ?>"><?php echo $getShift[$i]['name']; ?></option>
												<?php
											}
											?>
										</select>
                                    </div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group row">
									<label class="col-sm-2 form-control-label"><b>Total Visitor</b></label>
                                    <div class="col-sm-4">
										<input type="number" class="form-control" placeholder="Enter Total Visitor" name="total" value="<?php echo $row->total; ?>">
                                    </div>
								</div>
							</div>
						</div>			
					</div>

					<div class="dker p-a text-left">
						<input type="hidden" name="id" value="<?php echo $row->id;?>"></input>
						<input type="hidden" id="site_id" name="site_id" value="<?php echo $row->site_id; ?>">
						<input type="hidden" id="team_id" name="team_id" value="<?php echo $row->team_id; ?>">
						<button id="btn-submit" type="submit" class="btn btn-fw info">Submit</button>
						<a href="<?php echo base_url('visitor'); ?>" class="btn btn-fw danger">Cancel</a>
					</div>
				</div>
			</form>
		<?php } ?>
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