<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">Management Banner</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<div class="box">
			<div class="box-body">
				<div class="table-responsive">
					<div id="toolbarIklan">
						<a href="<?php echo base_url('iklan/form'); ?>" class="btn btn-block btn-success">
							<i class="glyphicon glyphicon-plus"></i> New Banner
						</a>
					</div>
					<table class="table table-bordered" id="listIklan"></table>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?= base_url() ?>scripts/apps/iklan.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>libs/jquery/lightbox/css/lightbox.min.css">
<script src="<?php echo base_url(); ?>libs/jquery/lightbox/js/lightbox-plus-jquery.min.js"></script>
