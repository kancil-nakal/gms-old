<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">View Message</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<?php foreach ($getData as $message) { ?>
			<?php
				$split 			= explode('-', $message->active_date);
				$active_date 	= $split[1].'/'.$split[2].'/'.$split[0];
			?>
				<div class="box">
					<div class="box-body">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group row">
									<label class="col-sm-3 form-control-label"><b>Message Name</b></label>
									<div class="col-sm-9">
										<input type="text" class="form-control" placeholder="Enter Message Name" name="message_name" id="message_name" required value="<?php echo $message->message_name; ?>" readonly>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group row">
									<label class="col-sm-3 form-control-label"><b>Active Date</b></label>
									<div class="col-sm-9" id="sandbox-container">
										<div class="input-group date">
											<input type="text" class="form-control" placeholder="Enter Active Date" name="active_date" value="<?php echo $active_date ;?>" readonly>
											<span class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</span>
										</div>
									</div>
								</div>
							</div>

							<div class="col-sm-12">
								<textarea name="message" readonly><?php echo $message->message ;?></textarea>
							</div>
						</div>
					</div>

					<div class="dker p-a text-left">
						<a href="<?php echo base_url('message'); ?>" class="btn btn-fw danger">Back</a>
					</div>
				</div>
		<?php } ?>
	</div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>libs/jquery/tinymce/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
$(function() {
	$("#sandbox-container .input-group.date").datepicker({
		autoclose: true,
		todayHighlight: true
	});

	tinymce.init({
		selector:'textarea',
		height: 250,
		theme: 'modern',
		readonly : 1,
		menubar: false,
		plugins: [],
		toolbar1: "",
		toolbar2: "",
	});
});
</script>