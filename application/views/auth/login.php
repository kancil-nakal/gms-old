<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>GMS - TRIMITRA PUTRA MANDIRI</title>
		<meta name="description" content="Admin, Dashboard, Bootstrap, Bootstrap 4, Angular, AngularJS" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
		<link rel="apple-touch-icon" href="<?php echo base_url(); ?>assets/images/ico.png">
		<meta name="apple-mobile-web-app-title" content="Flatkit">
		<meta name="mobile-web-app-capable" content="yes">
		<link rel="shortcut icon" sizes="196x196" href="<?php echo base_url(); ?>assets/images/ico.png">
		  
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/animate.css/animate.min.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/glyphicons/glyphicons.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.min.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/material-design-icons/material-design-icons.css" type="text/css" />

		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/styles/app.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/styles/font.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/styles/custome.css" type="text/css" />
	</head>
	<body style="background-color: #000000 !important;">
		<div class="app" id="app">
			<div class="center-block w-xxl w-auto-xs p-y-md">
				<div class="navbar">
					<div class="pull-center">
						<a class="navbar-brand">
							<img src="<?php echo base_url(); ?>assets/images/logo_sidemenu.png" alt=".">
<!-- 							<span class="hidden-folded inline">FTrack</span> -->
						</a>
					</div>
				</div>
				<div class="p-a-md box-color r box-shadow-z1 text-color m-a">
					<div class="m-b text-sm">Sign in with your Account</div>
					<form action="<?php echo base_url('auth/validation'); ?>" method="POST" name="form">
						<div class="md-form-group float-label">
							<input type="text" name="user_email" class="md-input" required>
							<label>Username</label>
						</div>
						<div class="md-form-group float-label">
							<input type="password" name="user_pass" class="md-input" required>
							<label>Password</label>
						</div>
						<button type="submit" class="btn primary btn-block p-x-md">Sign in</button>
					</form>
				</div>

<!-- 				<div class="p-v-lg text-center">
					<div class="m-b"><a href="<?php echo base_url(); ?>auth/forgot" class="text-primary _600">Forgot password?</a></div>
				</div> -->
			</div>
		</div>
		<script src="<?php echo base_url(); ?>libs/jquery/jquery/dist/jquery.js"></script>
		<script src="<?php echo base_url(); ?>libs/jquery/tether/dist/js/tether.min.js"></script>
		<script src="<?php echo base_url(); ?>libs/jquery/bootstrap/dist/js/bootstrap.js"></script>
		<script src="<?php echo base_url(); ?>libs/jquery/underscore/underscore-min.js"></script>
		<script src="<?php echo base_url(); ?>libs/jquery/jQuery-Storage-API/jquery.storageapi.min.js"></script>
		<script src="<?php echo base_url(); ?>libs/jquery/PACE/pace.min.js"></script>
		<script src="<?php echo base_url(); ?>scripts/config.lazyload.js"></script>
		<script src="<?php echo base_url(); ?>scripts/palette.js"></script>
		<script src="<?php echo base_url(); ?>scripts/ui-load.js"></script>
		<script src="<?php echo base_url(); ?>scripts/ui-jp.js"></script>
		<script src="<?php echo base_url(); ?>scripts/ui-include.js"></script>
		<script src="<?php echo base_url(); ?>scripts/ui-device.js"></script>
		<script src="<?php echo base_url(); ?>scripts/ui-form.js"></script>
		<script src="<?php echo base_url(); ?>scripts/ui-nav.js"></script>
		<script src="<?php echo base_url(); ?>scripts/ui-scroll-to.js"></script>
		<script src="<?php echo base_url(); ?>scripts/ui-toggle-class.js"></script>
		<script src="<?php echo base_url(); ?>scripts/app.js"></script>
	</body>
</html>
