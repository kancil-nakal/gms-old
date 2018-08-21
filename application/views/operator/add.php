<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">New Operator</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<form action="<?php echo base_url('operator/save'); ?>" method="post" enctype="multipart/form-data">
			<div class="box">
				<div class="box-body">
					<div class="row">
						<div class="col-sm-5">
							<div class="form-group row">
								<label class="col-sm-4"><img id="thumbnil" style="width:100px;height:127px;" src="<?php echo base_url(); ?>assets/images/photo/no-image.png" alt="image"/></label>
								<div class="col-sm-8">
									<input type="file" name="file" id="file" class="inputfile" accept="image/*" onchange="showMyImage(this)" />
									<label for="file">
										<svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
										<span>Take photo</span>
									</label>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-6">
							<div class="form-group row">
								<label class="col-sm-3 form-control-label"><b>Name</b></label>
								<div class="col-sm-9">
									<input type="text" class="form-control" placeholder="Enter Name" name="display_name" id="display_name" required>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 form-control-label"><b>Email</b></label>
								<div class="col-sm-9">
									<input type="email" class="form-control" placeholder="Enter Email" name="user_email" required>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 form-control-label"><b>Mobile Phone</b></label>
								<div class="col-sm-9">
									<input type="text" class="form-control" placeholder="Enter Mobile Phone" name="mobile_phone" required>
								</div>
							</div>
						</div>

						<div class="col-sm-6">
							<div class="form-group row">
								<label class="col-sm-3 form-control-label"><b>Username</b></label>
								<div class="col-sm-9">
									<input type="text" class="form-control" placeholder="Enter Username" name="username" required>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 form-control-label"><b>Password</b></label>
								<div class="col-sm-9">
									<input type="password" class="form-control" placeholder="Enter Password" name="password" required>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="dker p-a text-left">
					<button type="submit" class="btn btn-fw info">Submit</button>
					<a href="<?php echo base_url('operator'); ?>" class="btn btn-fw danger">Cancel</a>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
$(function() {
	$("#display_name").focus();
});

function showMyImage(fileInput) {
	var files = fileInput.files;
		for (var i = 0; i < files.length; i++) {
			var file = files[i];
			var imageType = /image.*/;
			if (!file.type.match(imageType)) {
				continue;
			}
			var img=document.getElementById("thumbnil");
			img.file = file;
			var reader = new FileReader();
			reader.onload = (function(aImg) {
				return function(e) {
				aImg.src = e.target.result;
			};
		})(img);
		reader.readAsDataURL(file);
	}
}
</script>