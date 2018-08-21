<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">New Position</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<form action="<?php echo base_url('master_position/save'); ?>" method="post">
			<div class="box">
				<div class="box-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group row">
								<label class="col-sm-2 form-control-label"><b>Position</b></label>
								<div class="col-sm-4">
									<input type="text" class="form-control" placeholder="Enter Position" name="name" id="name" onkeyup="cekPosition();" required disabled>
								</div>
							</div>
						</div>
					</div>			
				</div>

				<div class="dker p-a text-left">
					<input type="hidden" name="id" value="0"></input>
					<button id="btn-submit" type="submit" class="btn btn-fw info" disabled>Submit</button>
					<a href="<?php echo base_url('master_position'); ?>" class="btn btn-fw danger">Cancel</a>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
function cekPosition() {
	var name	= $("#name").val();
	$.ajax({
		type: 'get',
		url: BASE_URL+'master_position/getposition/'+name,
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