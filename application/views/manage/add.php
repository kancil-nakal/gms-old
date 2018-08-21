<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">Add Car</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<div class="box">
			<div class="box-body">
				<div class="row">
					<div class="col-sm-6">
						<div class="table-responsive">
							<table class="table table-bordered" id="getArea"></table>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="table-responsive">
							<table class="table table-bordered" id="getDetail"></table>
						</div>
					</div>
				</div>
			</div>

			<div class="dker p-a text-left">
				<a href="<?php echo base_url('manage'); ?>" class="btn btn-fw danger">Back</a>
			</div>
		</div>
	</div>
</div>
<input type="hidden" id="selectArea" value="all"></input>
<script type="text/javascript" src="<?= base_url() ?>scripts/apps/add.js"></script>
