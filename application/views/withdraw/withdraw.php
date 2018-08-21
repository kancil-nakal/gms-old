<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">Withdraw</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<div class="box">
			<div class="box-body">
				<div class="nav-active-border b-info">
					<ul class="nav nav-md">
						<?php
						if($_SESSION['login']['group_user'] != '3') {
							?>
							<li class="nav-item inline">
								<a class="nav-link active" href="" data-toggle="tab" data-target="#tab_1">
									<span class="text-md">Withdraw</span>
								</a>
							</li>
							<li class="nav-item inline">
								<a class="nav-link" href="" data-toggle="tab" data-target="#tab_2">
									<span class="text-md">List Withdraw APTI</span>
								</a>
							</li>
							<li class="nav-item inline">
								<a class="nav-link" href="" data-toggle="tab" data-target="#tab_3">
									<span class="text-md">Approve APTI</span>
								</a>
							</li>
							<?php
						} else {
							?>
							<li class="nav-item inline">
								<a class="nav-link active" href="" data-toggle="tab" data-target="#tab_1">
									<span class="text-md">Withdraw</span>
								</a>
							</li>
							<li class="nav-item inline">
								<a class="nav-link" href="" data-toggle="tab" data-target="#tab_2">
									<span class="text-md">Approve Withdraw</span>
								</a>
							</li>
							<?php
						}
						?>
					</ul>
				</div>

				<div class="tab-content clear b-t">
					<?php
						if($_SESSION['login']['group_user'] != '3') {
						?>
						<div class="tab-pane active" id="tab_1">
							<div id="toolbarWithdraw">
								<a href="<?php echo base_url('withdraw/export/0'); ?>" class="btn btn-block btn-success">
									<i class="fa fa fa-file-excel-o"></i> Export to Excel
								</a>
							</div>
							<table class="table table-bordered" id="listWithdraw"></table>
						</div>
						<div class="tab-pane" id="tab_2">
							<div id="toolbarFinance">
								<a href="<?php echo base_url('withdraw/export/1'); ?>" class="btn btn-block btn-success">
									<i class="fa fa fa-file-excel-o"></i> Export to Excel
								</a>
							</div>
							<table class="table table-bordered" id="listFinance"></table>
						</div>
						<div class="tab-pane" id="tab_3">
							<div id="toolbarAPTI">
								<a href="<?php echo base_url('withdraw/export/2'); ?>" class="btn btn-block btn-success">
									<i class="fa fa fa-file-excel-o"></i> Export to Excel
								</a>
							</div>
							<table class="table table-bordered" id="listAPTI"></table>
						</div>
						<?php
					} else {
						?>
						<div class="tab-pane active" id="tab_1">
							<div id="toolbarWithdraw">
								<a href="<?php echo base_url('withdraw/export/1'); ?>" class="btn btn-block btn-success">
									<i class="fa fa fa-file-excel-o"></i> Export to Excel
								</a>
							</div>
							<table class="table table-bordered" id="listWithdraw"></table>
						</div>
						<div class="tab-pane" id="tab_2">
							<div id="toolbarFinance">
								<a href="<?php echo base_url('withdraw/export/2'); ?>" class="btn btn-block btn-success">
									<i class="fa fa fa-file-excel-o"></i> Export to Excel
								</a>
							</div>
							<table class="table table-bordered" id="listFinance"></table>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div> 
<?php if($_SESSION['login']['group_user'] != '3') { ?>
	<script type="text/javascript" src="<?= base_url() ?>scripts/apps/withdraw.js?3"></script>
<?php } else { ?>
	<script type="text/javascript" src="<?= base_url() ?>scripts/apps/withdraws.js?1"></script>
<?php } ?>