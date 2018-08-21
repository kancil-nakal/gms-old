<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">Tes Node</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<form method="post" id="submitPosisi">
			<div class="box">
				<div class="box-body">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group row">
								<label class="col-sm-3 form-control-label"><b>Mobile Phone</b></label>
								<div class="col-sm-9">
									<input type="text" class="form-control" placeholder="Enter Mobile Phone" name="mobile_phone" value="081510097501" readonly>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 form-control-label"><b>Lat</b></label>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="lat">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 form-control-label"><b>Long</b></label>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="long">
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="dker p-a text-left">
					<input type="hidden" name="tracking_id" value="40"></input>
					<input type="hidden" name="is_moving" value="1"></input>
					<input type="hidden" name="accuracy" value="9"></input>
					<input type="hidden" name="altitude" value="77.8"></input>
					<input type="hidden" name="heading" value="268"></input>
					<input type="hidden" name="odometer" value="895386.8"></input>
					<input type="hidden" name="speed" value="1.42"></input>
					<button type="submit" class="btn btn-fw info">Submit</button>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
$(function() {
	$("#submitPosisi").submit(function() {
		var form		= $('#submitPosisi').serialize();
		$.ajax({
			type		: 'POST',
			url			: BASE_URL+'node/save',
			data		: form,
			dataType	: 'json',
			success		: function (data) {
				if (data.success == true) {
					swal({title:"Success", text:"Module successfully " + data.msg, timer:2000, type:"success", showConfirmButton:false});
				} else {
					swal({title:"Failed", text:"Module failed to " + data.msg, timer:2000, type:"error", showConfirmButton:false});
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				swal({title:"Error", text:jqXHR.status + " " + jqXHR.statusText, type:"error", closeOnConfirm:false});
			}
		}).then(function () {
			socket.emit('send-region', { } );
//			window.location = BASE_URL+'user';
		});
		return false;
	});
});

// socket.on('broadcast-region', function(data) {
// 	console.log(data);
// });
</script>