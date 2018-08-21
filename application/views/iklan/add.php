<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">New Banner</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<form action="<?php echo base_url('iklan/save'); ?>" method="post" enctype="multipart/form-data">
			<div class="box">
				<div class="box-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group row">
								<div class="col-sm-2" style="font-size: 11px !important;"">
									<input type="file" name="file" id="file" class="inputfile" accept="image/*" onchange="showMyImage(this)" />
									<label for="file">
										<svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
										<span>Take photo</span>
									</label>
								</div>
								<label class="col-sm-10"><img id="thumbnil" style="width:200px;height:200px;" src="<?php echo base_url(); ?>assets/images/no-image.jpg" alt="image"/></label>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label"><b>Client</b></label>
								<div class="col-sm-10">
									<div class="input-group">
										<input type="text" class="form-control" id="client_name" value="" disabled>
										<span class="input-group-btn">
											<button class="btn info btnClientNew" type="button" style="height:38px;"><i class="fa fa-search"></i></button>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label"><b>Keterangan</b></label>
								<div class="col-sm-10">
									<textarea name="message"></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="dker p-a text-left">
					<input type="hidden" name="id" value="0"></input>
					<input type="hidden" class="form-control" id="client_id" name="client_id" value="0">
					<button type="submit" class="btn btn-fw info">Submit</button>
					<a href="<?php echo base_url('iklan'); ?>" class="btn btn-fw danger">Cancel</a>
				</div>
			</div>
		</form>
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
<script type="text/javascript" src="<?php echo base_url(); ?>libs/jquery/tinymce/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
$(function() {
	$(".btnClientNew").on('click', function(e) {
		$('#ClientName').modal('show');
		listClientName();
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
			{ field: null, title: 'Action', align: 'center', width: '200px', formatter: operateFormatter, events: operateEvents }
		]
	});
}

function operateFormatter(value, row, index) {
	return [
		'<div class="btn-groupss">',
			'<button type="button" class="btn btn-success select" title="Select" style="margin-right:5px;"><i class="fa fa-edit"></i> Select</button>',
		'</div>'
	].join('');
}

window.operateEvents = {
	'click .select': function (e, value, row, index) {
		$('#client_name').val(row.ta_name);
		$('#client_id').val(row.id);
		$('#ClientName').modal('hide');
	}
};

function showMyImage(fileInput) {
	var files = fileInput.files;
		for (var i = 0; i < files.length; i++) {
			var file = files[i];
			var imageType = /image.*/;
			if (!file.type.match(imageType)) {
				continue;
			}
			var img=document.getElementById("thumbnil");
			img.file = file;
			var reader = new FileReader();
			reader.onload = (function(aImg) {
				return function(e) {
				aImg.src = e.target.result;
			};
		})(img);
		reader.readAsDataURL(file);
	}
}
</script>