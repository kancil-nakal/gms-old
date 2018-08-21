<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">Edit Site</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<?php foreach ($getData as $row) { ?>
			<form action="<?php echo base_url('site/edit'); ?>" method="post" enctype="multipart/form-data">
				<div class="box">
					<div class="box-body">
						
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group row">
								<label class="col-sm-2 form-control-label"><b>Site Name</b></label>
								<div class="col-sm-10">
									<input type="text" class="form-control" placeholder="Enter Site Name" name="site_name" id="site_name" value="<?php echo $row->site_name;?>" required>
								</div>
							</div>
						</div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label"><b>Client</b></label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="client_name"  value="<?php echo $row->client_name;?>">
                                        <span class="input-group-btn">
                                            <button class="btn info btnClientEdit" type="button" style="height:38px;"><i class="fa fa-search"></i></button>
                                            <button class="btn danger btnClientRemove" type="button" style="height:38px;"><i class="fa fa-trash"></i></button>
                                        </span>
                                    </div>
                                </div>
							</div>
						</div>
					</div>
					<div class="row" style="margin-top: 15px">
						<div class="col-sm-6">
							<div class="form-group row">
								<label class="col-sm-2 form-control-label"><b>City</b></label>
								<div class="col-sm-10">
									<select class="form-control" name="city_id" id="city_id">
										<option value="0">--- Select City ---</option>
											<?php
											for($i=0;$i<count($getCity);$i++) {
												if ($getCity[$i]['id'] == $row->city_id) {
													$select = 'selected="selected"';
												} else {
													$select = '';
												}
												?>
												<option <?php echo $select;?> value="<?php echo $getCity[$i]['id']; ?>"><?php echo $getCity[$i]['city_name']; ?></option>
												<?php
											}
											?>
									</select>
								</div>
							</div>
						</div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label"><b>Status</b></label>
                                <div class="input-group col-sm-10">
                                    <div class="switch-toggle switch-2 well" style="margin-bottom:0px !important">
                                            <?php if($row->status == 0) { ?>
                                                <input id="status_y" name="status" type="radio" checked value="0">
                                                <label for="status_y" onclick="">Enable</label>
                                                <input id="tstatus_n" name="status" type="radio" value="1">
                                                <label for="tstatus_n" onclick="">Disable</label>
                                                <a class="btn btn-primary"></a>
                                            <?php } else { ?>
                                                <input id="status_y" name="status" type="radio" value="0">
                                                <label for="status_y" onclick="">Enable</label>
                                                <input id="tstatus_n" name="status" type="radio" checked value="1">
                                                <label for="tstatus_n" onclick="">Disable</label>
                                                <a class="btn btn-primary"></a>
                                            <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label class="col-sm-1 form-control-label"><b>Notes</b></label>
                                <div class="col-sm-11">
                                    <textarea name="notes" class="form-control" rows="4"><?php echo $row->notes; ?></textarea>
                                </div>
                            </div> 
                        </div>
					</div>

					<div class="dker p-a text-left">
						<input type="hidden" name="id" value="<?php echo $row->id;?>"></input>
						<input type="hidden" id="client_id" name="client_id" value="<?php echo $row->client_id; ?>">
						<button type="submit" class="btn btn-fw info">Submit</button>
						<a href="<?php echo base_url('site'); ?>" class="btn btn-fw danger">Cancel</a>
					</div>
				</div>
			</form>
		<?php } ?>
	</div>
</div>
<div id="ClientName" class="modal fade black-overlay" data-backdrop="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body p-lg">
				<table class="table table-bordered" id="listClientName"></table>
			</div>

			<div class="dker modal-footer">
				<button type="button" class="btn btn-fw danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function() {
	$("#site_name").focus();
    

	$(".btnClientEdit").on('click', function(e) {
		$('#ClientName').modal('show');
		listClientName();
	});

	$(".btnClientRemove").on('click', function(e) {
		$('#client_name').val('');
		$('#client_id').val(0);
	});
});

function listClientName() {
	var $table = $('#listClientName');
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'api/manageData/2',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{ field: 'ta_name', title: 'Name', align: 'left', sortable: true },
			{ field: 'ta_email', title: 'Email', align: 'left' },
			{ field: 'ta_phone', title: 'Phone', align: 'left' },
			{ field: null, title: 'Action', align: 'center', width: '200px', formatter: clientFormatter, events: clientEvents }
		]
	});
}

function clientFormatter(value, row, index) {
	return [
		'<div class="btn-groupss">',
			'<button type="button" class="btn btn-success select" title="Select" style="margin-right:5px;"><i class="fa fa-edit"></i> Select</button>',
		'</div>'
	].join('');
}

window.clientEvents = {
	'click .select': function (e, value, row, index) {
		$('#client_name').val(row.ta_name);
		$('#client_id').val(row.id);
		$('#ClientName').modal('hide');
	}
};
</script>