<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">Summary Report</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<div class="box">
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-bordered" id="listReport"></table>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="report_detail" class="modal fade black-overlay" data-backdrop="false">
	<div class="modal-dialog modal-lg" style="width: 80%;">
		<div class="modal-content">
			<div class="modal-body p-lg">
				<table class="table table-bordered" id="reportDetail"></table>
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
	<script type="text/javascript" src="<?= base_url() ?>scripts/apps/summary.js?2"></script>
<?php } else { ?>
	<script type="text/javascript" src="<?= base_url() ?>scripts/apps/summaryClient.js?2"></script>
<?php } ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDfVHt_u3hEO6R5Gavi8XWcCYQkA3nNhik&callback=initMap" async defer></script>
