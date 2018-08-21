<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">Top Up</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<form action="<?php echo base_url('topup/save'); ?>" method="post">
			<div class="box">
				<div class="box-body">
					<div class="row">
						<div class="col-md-6 col-xs-12">
							<div class="form-group row">
								<label class="col-md-3 form-control-label">Name</label>
								<div class="col-md-9">
									<div class="input-group">
										<input type="text" class="form-control" name="member_name" id="member_name" readonly>
										<span class="input-group-btn">
											<button class="btn info member" type="button" style="height:38px;"><i class="fa fa-search"></i></button>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-3 form-control-label">Phone</label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="mobile_phone" id="mobile_phone" readonly>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-3 form-control-label">Email</label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="email_address" id="email_address" readonly>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-3 form-control-label">Saldo</label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="saldo" id="saldo" readonly>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-xs-12">
							<div class="form-group row">
								<label class="text-center col-md-12" style="font-weight: bold;font-size: 20px;">TOP UP</label>
								<input type="text" class="form-control" style="text-align: right; font-weight: bold; font-size: 20px" placeholder="0" name="topup" id="topup">
							</div>
							<div class="p-a text-right">
								<input type="hidden" class="form-control" name="id" id="id">
								<button type="submit" class="btn info"><i class="fa fa-money"></i> Top Up</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<div id="frm_member" class="modal fade black-overlay" data-backdrop="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body p-lg">
				<table class="table table-bordered" id="listClient"></table>
			</div>

			<div class="dker modal-footer">
				<button type="button" class="btn btn-fw danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?= base_url() ?>scripts/apps/topup.js"></script>
<script type="text/javascript">
$(function() {
	$('#topup').autoNumeric('init');
});
</script>