<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$session	= $this->session->userdata('login');
?>

				</div>
			</div>
		</div>
		<div id="loader-wrapper">
			<div id="loader"></div>
			<div class="loader-section section-left"></div>
			<div class="loader-section section-right"></div>
		</div>

		<script src="<?php echo base_url(); ?>libs/jquery/underscore/underscore-min.js"></script>
		<script src="<?php echo base_url(); ?>libs/jquery/jQuery-Storage-API/jquery.storageapi.min.js"></script>
		<script src="<?php echo base_url(); ?>libs/jquery/PACE/pace.min.js"></script>
		<script src="<?php echo base_url(); ?>scripts/ui-include.js"></script>
		<script src="<?php echo base_url(); ?>scripts/palette.js"></script>
		<script src="<?php echo base_url(); ?>scripts/ui-device.js"></script>
		<script src="<?php echo base_url(); ?>scripts/ui-form.js"></script>
		<script src="<?php echo base_url(); ?>scripts/ui-nav.js"></script>
		<script src="<?php echo base_url(); ?>scripts/ui-scroll-to.js"></script>
		<script src="<?php echo base_url(); ?>scripts/ui-toggle-class.js"></script>
		<script src="<?php echo base_url(); ?>scripts/app.js"></script>
	</body>
</html>
