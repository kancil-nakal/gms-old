<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">Manage Car</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<div class="box">
			<div class="box-body">
				<div class="table-responsive">
					<div id="toolbarDriver">
						<a href="<?php echo base_url('manage/add'); ?>" class="btn btn-block btn-success">
							<i class="glyphicon glyphicon-plus"></i> Add Car
						</a>
					</div>
					<table class="table table-bordered" id="listCar"></table>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?= base_url() ?>scripts/apps/manage.js"></script>