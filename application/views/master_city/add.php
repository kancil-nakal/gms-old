<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">New City</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<form action="<?php echo base_url('master_city/save'); ?>" method="post">
			<div class="box">
				<div class="box-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group row">
								<label class="col-sm-2 form-control-label"><b>City</b></label>
								<div class="col-sm-4">
									<input type="text" class="form-control" placeholder="Enter City" name="city_name" id="city_name" onkeyup="cekCity();" required>
								</div>
							</div>
						</div>
					</div>			
				</div>

				<div class="dker p-a text-left">
					<input type="hidden" name="id" value="0"></input>
					<button type="submit" class="btn btn-fw info" >Submit</button>
					<a href="<?php echo base_url('master_city'); ?>" class="btn btn-fw danger">Cancel</a>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
function cekCity() {
	var city_name	= $("#city_name").val();
	$.ajax({
		type: 'get',
		url: BASE_URL+'master_city/getcity/'+city_name,
		dataType: 'json',
		success: function(data) {
			if (data == 1) {
				swal({ title: "Error", text: "<b>" + city_name + "</b> sudah tersedia", html: true, timer: 2000, type: "error", showConfirmButton: false });
			}
		}
	});
}
</script>