<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">Edit Tag</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<?php foreach ($getData as $row) { ?>
			<form action="<?php echo base_url('master_checkpoint/save'); ?>" method="post">
				<div class="box">
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group row">
									<label class="col-sm-1 form-control-label"><b>Name</b></label>
									<div class="col-sm-5">
										<input type="text" class="form-control" placeholder="Enter Checkpoint" name="name" id="name" onkeyup="cekCheckpoint();" required value="<?php echo $row->name;?>">
									</div>
                                
									<label class="col-sm-1 form-control-label"><b>Site</b></label>
									<div class="col-sm-5">
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
									<label class="col-sm-1 form-control-label"><b>Sort</b></label>
									<div class="col-sm-1">
										<input type="number" class="form-control" name="ordering" id="ordering" required value="<?php echo $row->ordering;?>">
									</div>
									<label class="col-sm-1 form-control-label"><b>Tag</b></label>
									<div class="col-sm-3">
										<select class="form-control" name="beacon_id" id="beacon_id">
											<option value="">--- Select Tag ---</option>
											<?php
											for($i=0;$i<count($getBeacon);$i++) {
												if ($getBeacon[$i]['id'] == $row->beacon_id) {
													$select = 'selected="selected"';
												} else {
													$select = '';
												}
												?>
												<option <?php echo $select;?> value="<?php echo $getBeacon[$i]['id']; ?>"><?php echo $getBeacon[$i]['notes']; ?></option>
												<?php
											}
											?>
										</select>
									</div>
                                    <label class="col-sm-1 form-control-label"><b>Status</b></label>
                                    <div class="input-group col-sm-5">
										<div class="switch-toggle switch-2 well" style="margin-bottom:0px !important">
											<?php if($row->status == 0) { ?>
												<input id="status_y" name="status" type="radio" checked value="0">
												<label for="status_y" onclick="">Enable</label>
												<input id="status_n" name="status" type="radio" value="1">
												<label for="status_n" onclick="">Disable</label>
												<a class="btn btn-primary"></a>
											<?php } else { ?>
												<input id="status_y" name="status" type="radio" value="0">
												<label for="status_y" onclick="">Enable</label>
												<input id="status_n" name="status" type="radio" checked value="1">
												<label for="status_n" onclick="">Disable</label>
												<a class="btn btn-primary"></a>
											<?php } ?>
										</div>
                                    </div>
								</div>
							</div>
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label class="col-sm-1 form-control-label"><b>Location</b></label>
                                <div class="col-sm-11">
                                    <input type="text" class="form-control" placeholder="Enter Location" name="location" id="location" value="<?php echo $row->location;?>">
                                </div>
                            </div>
                        </div>
						</div>			
					</div>

					<div class="dker p-a text-left">
						<input type="hidden" name="id" value="<?php echo $row->id;?>"></input>
						<input type="hidden" id="site_id" name="site_id" value="<?php echo $row->site_id; ?>">
						<button id="btn-submit" type="submit" class="btn btn-fw info">Submit</button>
						<a href="<?php echo base_url('master_checkpoint'); ?>" class="btn btn-fw danger">Cancel</a>
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
<script type="text/javascript">
$(function() {
	$(".btnSiteEdit").on('click', function(e) {
		$('#SiteName').modal('show');
		listSiteName();
	});

	$(".btnSiteRemove").on('click', function(e) {
		$('#site_name').val('');
		$('#site_id').val(0);
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

function cekCheckpoint() {
	var name	= $("#name").val();
	$.ajax({
		type: 'get',
		url: BASE_URL+'master_checkpoint/getcheckpoint/'+name,
		dataType: 'json',
		success: function(data) {
			if (data == 1) {
				swal({ title: "Error", text: "<b>" + name + "</b> sudah tersedia", html: true, timer: 2000, type: "error", showConfirmButton: false });
                $('#btn-submit').prop('disabled', true);
			} else {
                $('#btn-submit').prop('disabled', false);
            }
		}
	});
}
</script>