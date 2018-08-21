<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">Message List</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<div class="box">
			<div class="box-body">
				<div class="nav-active-border b-info">
					<ul class="nav nav-md">
						<li class="nav-item inline">
							<a class="nav-link active" href="" data-toggle="tab" data-target="#tab_1">
								<span class="text-md">Public Message List</span>
							</a>
						</li>
						<li class="nav-item inline">
							<a class="nav-link" href="" data-toggle="tab" data-target="#tab_2">
								<span class="text-md">Log Message</span>
							</a>
						</li>
					</ul>
				</div>

				<div class="tab-content clear b-t">
						<div class="tab-pane active" id="tab_1">
							<div id="toolbarTeamAll">
                                <a href="<?php echo base_url('message/form'); ?>" class="btn btn-block btn-success">
                                    <i class="glyphicon glyphicon-plus"></i> New Message
                                </a>
							</div>
							<table class="table table-bordered" id="listMessageAll"></table>
						</div>
						<div class="tab-pane" id="tab_2">
							<div id="toolbarTeamPersonal">
							</div>
							<table class="table table-bordered" id="listMessagePersonal"></table>
						</div>
				</div>

			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?= base_url() ?>scripts/apps/message.js"></script> 