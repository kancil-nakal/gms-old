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
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstraptable/bootstrap-table.min.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>libs/jquery/bootstrap-datepicker/css/bootstrap-datepicker.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>libs/jquery/parsleyjs/dist/parsley.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>libs/jquery/sweetalert/sweetalert.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/styles/app.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/styles/font.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/styles/custome.css" type="text/css" />

		<script src="<?php echo base_url(); ?>libs/jquery/jquery/dist/jquery.js"></script>
		<script src="<?php echo base_url(); ?>libs/jquery/jquery.googleMap/jquery.googlemap.js"></script>
		<script src="<?php echo base_url(); ?>libs/jquery/bootstrap/dist/js/bootstrap.js"></script>
		<script src="<?php echo base_url(); ?>libs/jquery/bootstraptable/bootstrap-table.js"></script>
		<script src="<?php echo base_url(); ?>libs/jquery/tether/dist/js/tether.min.js"></script>
		<script src="<?php echo base_url(); ?>libs/jquery/parsleyjs/dist/parsley.min.js"></script>
		<script src="<?php echo base_url(); ?>libs/jquery/sweetalert/sweetalert.js"></script>
		<script src="<?php echo base_url(); ?>libs/js/moment/moment.js"></script>
		<script src="<?php echo base_url(); ?>libs/jquery/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
		<script src="<?php echo base_url(); ?>scripts/plugins/autoNumeric.min.js"></script>
		<script type="text/javascript">
			var BASE_URL = '<?php echo base_url(); ?>';
		</script>
<?php /* ?>
		<?php if($_SESSION['login']['group_user'] == 1 || $_SESSION['login']['group_user'] == 5) {?>
			<script src="<?php echo base_url(); ?>scripts/plugins/tempelad.js"></script>
		<?php } ?>
<?php */ ?>
	</head>
	<body>
		<div class="app" id="app">
			<div id="aside" class="app-aside modal fade nav-dropdown">
				<div class="left navside dark dk" layout="column">
					<div class="navbar no-radius">
						<a class="navbar-brand">
							<img src="<?php echo base_url(); ?>assets/images/logo_sidemenu.png?1" alt=".">
						</a>
					</div>

					<div flex class="hide-scroll">
						<nav class="scroll nav-light">
							<ul class="nav" ui-nav>
								<li class="nav-header hidden-folded">
									<small class="text-muted">Main</small>
								</li>
								<li>
									<a href="<?php echo base_url(); ?>">
										<span class="nav-icon">
											<i class="material-icons">&#xe88a;</i>
										</span>
										<span class="nav-text">Dashboard</span>
									</a>
								</li>
								<?php echo $this->rolemenu->build_menu($_SESSION['login']['group_user']); ?>
							</ul>
						</nav>
					</div>
					<div flex-no-shrink class="b-t">
						<nav ui-nav="">
							<ul class="nav">
								<li><div class="b-b b m-v-sm"></div></li>
								<li class="no-bg">
									<a href="<?php echo base_url('auth/logout'); ?>">
										<span class="nav-icon">
											<i class="material-icons">&#xe8ac;</i>
										</span>
										<span class="nav-text">Logout</span>
									</a>
								</li>
							</ul>
						</nav>
					</div>
				</div>
			</div>

			<div id="content" class="app-content box-shadow-z0" role="main">
				<div class="app-header white box-shadow">
					<div class="navbar">
						<a data-toggle="modal" data-target="#aside" class="navbar-item pull-left hidden-lg-up">
							<i class="material-icons">&#xe5d2;</i>
						</a>
						<div class="navbar-item pull-left h5" ng-bind="$state.current.data.title" id="pageTitle"></div>
						<ul class="nav navbar-nav pull-right">
							<li class="nav-item dropdown">
								<a class="nav-link" href="" data-toggle="dropdown" aria-expanded="false">
									<span class="avatar w-32">
										<?php
										if($_SESSION['login']['ta_image'] == '') {
											?>
											<img src="<?php echo base_url(); ?>assets/images/photo/no-image.png" style="width: 32px; height: 32px;" alt="...">
											<?php
										} else {
											?>
											<img src="<?php echo base_url().$_SESSION['login']['ta_image']; ?>" style="width: 32px; height: 32px;" alt="...">
											<?php
										}
										?>
										<i class="on b-white bottom"></i>
									</span>
									<span class="hidden-md-down nav-text m-l-sm text-left">
										<span class="_500"><?php echo $_SESSION['login']['ta_name']; ?></span>
									</span>
								</a>
								<div class="dropdown-menu pull-right dropdown-menu-scale">
									<a class="dropdown-item" href="<?php echo base_url('profile'); ?>">
										<span>Profile</span>
									</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="<?php echo base_url('auth/logout'); ?>">Sign out</a>
								</div>
							</li>
							<li class="nav-item hidden-md-up">
								<a class="nav-link" data-toggle="collapse" data-target="#collapse">
									<i class="material-icons">&#xe5d4;</i>
								</a>
							</li>
						</ul>
					</div>
				</div>

				<div ui-view class="app-body" id="view">