<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">City</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<div class="box">
			<div class="box-body">
				<div class="table-responsive">
					<div id="toolbarCity">
						<a href="<?php echo base_url('master_city/form'); ?>" class="btn btn-block btn-success">
							<i class="glyphicon glyphicon-plus"></i> New City
						</a>
					</div>
					<table class="table table-bordered" id="listCity"></table>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?= base_url() ?>scripts/apps/master_city.js"></script>