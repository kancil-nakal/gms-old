<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">New Merk Mobil</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<form action="<?php echo base_url('merk/save'); ?>" method="post">
			<div class="box">
				<div class="box-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group row">
								<label class="col-sm-2 form-control-label"><b>Brand</b></label>
								<div class="col-sm-4">
									<div class="input-group">
									<input type="text" class="form-control" placeholder="Enter Brand" name="brand_name" id="brand_name" readonly required>
										<span class="input-group-btn">
											<button class="btn info btnBrand" type="button" style="height:38px;"><i class="fa fa-search"></i></button>
										</span>
									</div>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-2 form-control-label"><b>Merk</b></label>
								<div class="col-sm-4">
									<input type="text" class="form-control" placeholder="Enter Merk" name="merk_name" id="merk_name" onkeyup="cekMerk();" required disabled>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-2 form-control-label"><b>Active</b></label>
								<div class="col-sm-4">
									<div class="switch-toggle switch-2 well" style="margin-bottom:0px !important">
										<input id="Y" name="status" type="radio" checked value="0">
										<label for="Y" onclick="">Yes</label>
										<input id="N" name="status" type="radio" value="1">
										<label for="N" onclick="">No</label>
										<a class="btn btn-primary"></a>
									</div>
								</div>
							</div>
						</div>
					</div>			
				</div>

				<div class="dker p-a text-left">
					<input type="hidden" name="id" value="0"></input>
					<button type="submit" class="btn btn-fw info" disabled>Submit</button>
					<a href="<?php echo base_url('merk'); ?>" class="btn btn-fw danger">Cancel</a>
				</div>
			</div>
		</form>
	</div>
</div>

<div id="brandName" class="modal fade black-overlay" data-backdrop="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body p-lg">
				<table class="table table-bordered" id="listBrand"></table>
			</div>

			<div class="dker modal-footer">
				<button type="button" class="btn btn-fw danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function() {
	$("#brand_name").focus();
	$(".btnBrand").on('click', function(e) {
		$('#brandName').modal('show');
		listBrand();
		$("#merk_name").prop("disabled", false);
		$("button[type='submit']").prop("disabled", false);
		$("#merk_name").focus();
	});
});

var $table = $('#listBrand');
function listBrand() {
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'brand/listbrand',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{ field: 'brand_name', title: 'Brand Mobil', align: 'left', sortable: true },
			{ field: null, title: 'Action', align: 'center', width: '225px', formatter: operateFormatter, events: operateEvents }
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
		$('#brand_name').val(row.brand_name);
		$('#brandName').modal('hide');
	}
};

function cekMerk() {
	var brand_name	= $("#brand_name").val();
	var merk_name	= $("#merk_name").val();
	$.ajax({
		type: 'get',
		url: BASE_URL+'merk/getmerk/'+brand_name+'/'+merk_name,
		dataType: 'json',
		success: function(data) {
			if (data == 1) {
				swal({ title: "Error", text: "<b>" + brand_name + ' ' + merk_name + "</b> sudah tersedia", html: true, timer: 2000, type: "error", showConfirmButton: false });
			}
		}
	});
}
</script>