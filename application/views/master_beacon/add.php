<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">New Tag</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<form action="<?php echo base_url('master_beacon/save'); ?>" method="post">
			<div class="box">
				<div class="box-body">
					<div class="row">
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label"><b>Name</b></label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" placeholder="Enter Beacon" name="notes" id="notes" onkeyup="cekBeacon();" required >
                                </div>
                            </div>
                        </div>
                        <!--
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label"><b>Type</b></label>
                                <div class="col-sm-4">
                                    <select class="form-control" name="type" id="type">
                                        <option value="">--- Select Type ---</option>
                                        <?php
                                        for($i=0;$i<count($getType);$i++) {
                                            ?>
                                            <option value="<?php echo $getType[$i]['name']; ?>"><?php echo $getType[$i]['name']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        -->
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label"><b>Status</b></label>
                                <div class="input-group col-sm-4">
                                    <div class="switch-toggle switch-2 well" style="margin-bottom:0px !important">
                                            <input id="status_y" name="status" type="radio" checked value="0">
                                            <label for="status_y" onclick="">Enable</label>
                                            <input id="status_n" name="status" type="radio" value="1">
                                            <label for="status_n" onclick="">Disable</label>
                                            <a class="btn btn-primary"></a>
                                    </div>
                                </div>
                            </div>
                        </div>	
					</div>			
				</div>

				<div class="dker p-a text-left">
					<input type="hidden" name="id" value="0"></input>
					<button id="btn-submit" type="submit" class="btn btn-fw info">Submit</button>
					<a href="<?php echo base_url('master_beacon'); ?>" class="btn btn-fw danger">Cancel</a>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
function cekBeacon() {
	var notes	= $("#notes").val();
	$.ajax({
		type: 'get',
		url: BASE_URL+'master_beacon/getbeacon/'+notes,
		dataType: 'json',
		success: function(data) {
			if (data == 1) {
				swal({ title: "Error", text: "<b>" + notes + "</b> sudah tersedia", html: true, timer: 2000, type: "error", showConfirmButton: false });
                $('#btn-submit').prop('disabled', true);
			} else {
                $('#btn-submit').prop('disabled', false);
            }
		}
	});
}
</script>