<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">Edit Emergency</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<?php foreach ($getData as $row) { ?>
			<form action="<?php echo base_url('emergency/save'); ?>" method="post">
				<div class="box">
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group row">
									<label class="col-sm-2 form-control-label"><b>Type</b></label>
									<div class="col-sm-4">
										<select class="form-control" name="contact_type" id="contact_type">
											<option value="">--- Select Type ---</option>
											<?php
											for($i=0;$i<count($getContactType);$i++) {
												if ($getContactType[$i]['id'] == $row->contact_type) {
													$select = 'selected="selected"';
												} else {
													$select = '';
												}
												?>
												<option <?php echo $select;?> value="<?php echo $getContactType[$i]['id']; ?>"><?php echo $getContactType[$i]['name']; ?></option>
												<?php
											}
											?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group row">
									<label class="col-sm-2 form-control-label"><b>Name</b></label>
                                    <div class="col-sm-4">
										<input type="text" class="form-control" placeholder="Enter Name" name="name" value="<?php echo $row->name; ?>">
                                    </div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group row">
									<label class="col-sm-2 form-control-label"><b>Phone</b></label>
                                    <div class="col-sm-4">
										<input type="text" class="form-control" placeholder="Enter Phone" name="phone" value="<?php echo $row->phone; ?>">
                                    </div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group row">
									<label class="col-sm-2 form-control-label"><b>Address</b></label>
                                    <div class="col-sm-4">
										<textarea class="form-control" name="address"><?php echo $row->address; ?></textarea>
                                    </div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group row">
									<label class="col-sm-2 form-control-label"><b>City</b></label>
									<div class="col-sm-4">
										<select class="form-control" name="city_id" id="city_id">
											<option value="">--- Select City ---</option>
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
							<div class="col-sm-12">
								<div class="form-group row">
									<label class="col-sm-2 form-control-label"><b>Status</b></label>
                                    <div class="col-sm-4">
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
						</div>			
					</div>

					<div class="dker p-a text-left">
						<input type="hidden" name="id" value="<?php echo $row->id;?>"></input>
						<button id="btn-submit" type="submit" class="btn btn-fw info">Submit</button>
						<a href="<?php echo base_url('emergency'); ?>" class="btn btn-fw danger">Cancel</a>
					</div>
				</div>
			</form>
		<?php } ?>
	</div>
</div>
<script type="text/javascript">
$(function() {
    
});
</script>