<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">Master Team</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<div class="box">
			<div class="box-body">
				<div class="table-responsive">
					<?php
					if($_SESSION['login']['group_user'] == 1 || $_SESSION['login']['group_user'] == 5) {
						?>
						<div id="toolbarTeam">
							<a href="<?php echo base_url('team/form'); ?>" class="btn btn-block btn-success">
								<i class="glyphicon glyphicon-plus"></i> New Team
							</a>
						</div>
						<?php
					}
					?>
					<table class="table table-bordered" id="listTeam"></table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
if($_SESSION['login']['group_user'] == 1 || $_SESSION['login']['group_user'] == 5) {
	?>
	<script type="text/javascript" src="<?= base_url() ?>scripts/apps/team.js?3"></script>
	<?php
} else {
	?>
	<script type="text/javascript" src="<?= base_url() ?>scripts/apps/teamApti.js"></script>
	<?php
}
