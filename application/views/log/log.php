<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">Log Driver</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<div class="box">
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-bordered" id="logDriver"></table>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="report_detail" class="modal fade black-overlay" data-backdrop="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><span class="driverName"></span></h5>
			</div>
			<div class="modal-body p-lg">
				<div class="table-responsive">
					<table class="table table-bordered" id="logDetailDriver"></table>
				</div>
			</div>

			<div class="dker modal-footer">
				<button type="button" class="btn btn-fw danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div id="route_detail" class="modal fade black-overlay" data-backdrop="false">
	<div class="modal-dialog modal-lg" style="width: 90%;">
		<div class="modal-content">
			<div class="modal-body p-lg" style="height: 550px;">
				<div id="map_canvas" style="width: 100% !important; height: 100% !important"></div>
			</div>

			<div class="dker modal-footer">
				<button type="button" class="btn btn-fw danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<?php if( $_SESSION['login']['group_user'] == 1 || $_SESSION['login']['group_user'] == 5 ) { ?>
	<script type="text/javascript" src="<?= base_url() ?>scripts/apps/log.js?23"></script>
<?php } else { ?>
	<script type="text/javascript" src="<?= base_url() ?>scripts/apps/logClient.js?1"></script>
<?php } ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDfVHt_u3hEO6R5Gavi8XWcCYQkA3nNhik&callback=initMap" async defer></script>
