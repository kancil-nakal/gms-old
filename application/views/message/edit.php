<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">Edit Message</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<?php foreach ($getData as $message) { ?>
			<?php
				$split 			= explode('-', $message->active_date);
				$active_date 	= $split[1].'/'.$split[2].'/'.$split[0];
			?>
			<form action="<?php echo base_url('message/save'); ?>" method="post">
				<div class="box">
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group row">
									<label class="col-sm-2 form-control-label"><b>Message Name</b></label>
									<div class="col-sm-10">
										<input type="text" class="form-control" placeholder="Enter Message Name" name="message_name" id="message_name" required value="<?php echo $message->message_name; ?>">
									</div>
								</div>
							</div>

							<div class="col-sm-12">
								<textarea name="message"><?php echo $message->message ;?></textarea>
							</div>
							
							<div class="col-sm-12">
								<hr/>
							</div>
							
							<div class="col-sm-12">
								<div class="form-group row">
									<label class="col-sm-1 form-control-label"><b>Recipient</b></label>
									<div class="col-sm-1">
										<input type="radio" class="recipient" name="recipient" value="all" <?php echo ($message->recipient == 'all' ? 'checked': ''); ?>> All
									</div>
									<div class="col-sm-1">	
										<input type="radio" class="recipient" name="recipient" value="client" <?php echo ($message->recipient == 'client' ? 'checked': ''); ?>> Client: 
									</div>
									<div class="col-sm-4">	
										<div class="input-group">
											<input type="text" class="form-control" id="client_name" value="<?php echo $client['client_name']; ?>" disabled>
											<span class="input-group-btn">
												<button class="btn info btnClientEdit" type="button" style="height:38px;"><i class="fa fa-search"></i></button>
												<button class="btn danger btnClientRemove" type="button" style="height:38px;"><i class="fa fa-trash"></i></button>
											</span>
										</div>
									</div>
									<div class="col-sm-1">	
										<input type="radio" class="recipient" name="recipient" value="team" <?php echo ($message->recipient == 'team' ? 'checked': ''); ?>> Team: 
									</div>
									<div class="col-sm-4">	
										<div class="input-group">
											<input type="text" class="form-control" id="team_name" value="<?php echo $team['team_name']; ?>" disabled>
											<span class="input-group-btn">
												<button class="btn info btnTeamEdit" type="button" style="height:38px;"><i class="fa fa-search"></i></button>
												<button class="btn danger btnTeamRemove" type="button" style="height:38px;"><i class="fa fa-trash"></i></button>
											</span>
										</div>
									</div>
								</div>
							</div>
							
						</div>
					</div>

					<div class="dker p-a text-left">
						<input type="hidden" name="id" value="<?php echo $message->id;?>"></input>
						<input type="hidden" id="client_id" name="client_id" value="<?php echo $client['client_id']; ?>">
						<input type="hidden" id="team_id" name="team_id" value="<?php echo $team['team_id']; ?>">
						<button type="submit" class="btn btn-fw info">Submit</button>
						<a href="<?php echo base_url('message'); ?>" class="btn btn-fw danger">Cancel</a>
					</div>
				</div>
			</form>
		<?php } ?>
	</div>
</div>
<div id="ClientName" class="modal fade black-overlay" data-backdrop="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body p-lg">
				<table class="table table-bordered" id="listClientName"></table>
			</div>

			<div class="dker modal-footer">
				<button type="button" class="btn btn-fw danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div id="TeamName" class="modal fade black-overlay" data-backdrop="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body p-lg">
				<table class="table table-bordered" id="listTeamName"></table>
			</div>

			<div class="dker modal-footer">
				<button type="button" class="btn btn-fw danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>libs/jquery/tinymce/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
$(function() {
	$("#sandbox-container .input-group.date").datepicker({
		autoclose: true,
		todayHighlight: true
	});

	tinymce.init({
		selector:'textarea',
		height: 250,
		theme: 'modern',
		menubar: false,
		plugins: [
			'advlist autolink lists link image charmap print preview hr anchor pagebreak',
			'searchreplace wordcount visualblocks visualchars code fullscreen',
			'insertdatetime media nonbreaking save table contextmenu directionality',
			'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
		],
		toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | fontselect fontsizeselect",
		toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | insertdatetime preview | forecolor backcolor",
		content_css: [
			'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
			'//www.tinymce.com/css/codepen.min.css'
		],
		toolbar_items_size: 'small',
		templates: [
			{ title: 'Test template 1', content: 'Test 1' },
			{ title: 'Test template 2', content: 'Test 2' }
		]
	});
	
	$(".btnClientEdit").on('click', function(e) {
		$('#ClientName').modal('show');
		$("input[name=recipient][value='client']").prop("checked",true);
		$('#team_id').val(0);
		$('#team_name').val('');
		listClientName();
	});

	$(".btnClientRemove").on('click', function(e) {
		$('#client_name').val('');
		$('#client_id').val(0);
	});

	$(".btnTeamEdit").on('click', function(e) {
		$('#TeamName').modal('show');
		$("input[name=recipient][value='team']").prop("checked",true);
		$('#client_id').val(0);
		$('#client_name').val('');
		listTeamName();
	});

	$(".btnTeamRemove").on('click', function(e) {
		$('#team_name').val('');
		$('#team_id').val(0);
	});
	
	$(".recipient").click(function() {
		var recipient = $('input[name=recipient]:checked').val();
		if(recipient == 'client') {
			$('#team_id').val(0);
			$('#team_name').val('');
		} else if(recipient == 'team') {
			$('#client_id').val(0);
			$('#client_name').val('');
		} else {
			$('#client_id').val(0);
			$('#client_name').val('');
			$('#team_id').val(0);
			$('#team_name').val('');
		}
	});
	
});

function listClientName() {
	var $table = $('#listClientName');
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'api/manageData/2',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{ field: 'ta_name', title: 'Name', align: 'left', sortable: true },
			{ field: 'ta_email', title: 'Email', align: 'left' },
			{ field: 'ta_phone', title: 'Phone', align: 'left' },
			{ field: null, title: 'Action', align: 'center', width: '200px', formatter: clientFormatter, events: clientEvents }
		]
	});
}

function clientFormatter(value, row, index) {
	return [
		'<div class="btn-groupss">',
			'<button type="button" class="btn btn-success select" title="Select" style="margin-right:5px;"><i class="fa fa-edit"></i> Select</button>',
		'</div>'
	].join('');
}

window.clientEvents = {
	'click .select': function (e, value, row, index) {
		$('#client_name').val(row.ta_name);
		$('#client_id').val(row.id);
		$('#ClientName').modal('hide');
	}
};

function listTeamName() {
	var $table = $('#listTeamName');
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'team/listTeam',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{ field: 'team_name', title: 'Name', align: 'left', sortable: true },
			{ field: 'mobile_phone', title: 'Phone', align: 'left' },
			{ field: 'no_pol', title: 'No Polisi', align: 'left' },
			{ field: null, title: 'Action', align: 'center', width: '200px', formatter: teamFormatter, events: teamEvents }
		]
	});
}

function teamFormatter(value, row, index) {
	return [
		'<div class="btn-groupss">',
			'<button type="button" class="btn btn-success select" title="Select" style="margin-right:5px;"><i class="fa fa-edit"></i> Select</button>',
		'</div>'
	].join('');
}

window.teamEvents = {
	'click .select': function (e, value, row, index) {
		$('#team_name').val(row.team_name);
		$('#team_id').val(row.id);
		$('#TeamName').modal('hide');
	}
};
</script>