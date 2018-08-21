<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">New Brand</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<form action="<?php echo base_url('brand/save'); ?>" method="post">
			<div class="box">
				<div class="box-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group row">
								<label class="col-sm-2 form-control-label"><b>Name</b></label>
								<div class="col-sm-4">
									<input type="text" class="form-control" placeholder="Enter Brand" name="brand_name" id="brand_name" onkeyup="cekBrand();" required>
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
					<button type="submit" class="btn btn-fw info">Submit</button>
					<a href="<?php echo base_url('brand'); ?>" class="btn btn-fw danger">Cancel</a>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
$(function() {
	$("#brand_name").focus();
});

function cekBrand() {
	var brand_name = $("#brand_name").val();
	$.ajax({
		type: 'get',
		url: BASE_URL+'brand/getbrand/'+brand_name,
		dataType: 'json',
		success: function(data) {
			if (data == 1) {
				swal({ title: "Error", text: "Brand <b>" + brand_name + "</b> sudah tersedia", html: true, timer: 2000, type: "error", showConfirmButton: false });
			}
		}
	});
}
</script>