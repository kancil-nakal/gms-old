<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">Photo</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<div class="box">
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-bordered" id="listPhoto"></table>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?= base_url() ?>scripts/apps/photo.js?4"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>libs/jquery/lightbox/css/lightbox.min.css">
<script src="<?php echo base_url(); ?>libs/jquery/lightbox/js/lightbox-plus-jquery.min.js"></script>
