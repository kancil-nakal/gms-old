<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">New Team</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<form action="<?php echo base_url('team/save'); ?>" method="post" enctype="multipart/form-data">
			<div class="box">
				<div class="box-body">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="form-group row">
								<label class="col-sm-3"><img id="thumbnil" style="width:100px;height:100px;" src="<?php echo base_url(); ?>assets/images/photo/no-image.png" alt="image"/></label>
								<div class="col-sm-9">
									<input type="file" name="file" id="file" class="inputfile" accept="image/*" onchange="showMyImage(this)" />
									<label for="file">
										<svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
										<span>Take photo</span>
									</label>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-sm-12">
							<div class="form-group row">
								<label class="col-sm-3 form-control-label"><b>Site</b></label>
								<div class="col-sm-9">
									<div class="input-group">
										<input type="text" class="form-control" id="site_name" value="" disabled>
										<span class="input-group-btn">
											<button class="btn info btnSiteNew" type="button" style="height:38px;"><i class="fa fa-search"></i></button>
										</span>
									</div>
								</div>
                                <label class="col-sm-3 form-control-label"><b>Shift</b></label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="shift_id" id="shift_id">
                                        <option value="">--- Select Shift ---</option>
                                        <?php
                                        for($i=0;$i<count($getShift);$i++) {
                                            ?>
                                            <option value="<?php echo $getShift[$i]['id']; ?>"><?php echo $getShift[$i]['name']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
							</div>
						</div>
					</div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label"><b>Name</b></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Enter Name" name="team_name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label"><b>Mobile Phone</b></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Enter Mobile Phone" name="mobile_phone">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label"><b>Address</b></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Address" name="address">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label"><b>City</b></label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="city_id" id="city_id">
                                        <option value="">--- Select City ---</option>
                                        <?php
                                        for($i=0;$i<count($getCity);$i++) {
                                            ?>
                                            <option value="<?php echo $getCity[$i]['id']; ?>"><?php echo $getCity[$i]['city_name']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label"><b>Position</b></label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="position_id" id="position_id">
                                        <option value="">--- Select Position ---</option>
                                        <?php
                                        for($i=0;$i<count($getPosition);$i++) {
                                            ?>
                                            <option value="<?php echo $getPosition[$i]['id']; ?>"><?php echo $getPosition[$i]['name']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label"><b>Join Date</b></label>
                                <div class="col-sm-9" id="sandbox-container">
                                    <div class="input-group date datepicker">
                                        <input type="text" class="form-control" placeholder="Enter Join Date" name="join_date">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label"><b>Team Status</b></label>
                                <div class="input-group col-sm-9">
                                    <div class="switch-toggle switch-2 well" style="margin-bottom:0px !important">
                                            <input id="Y" name="team_status" type="radio" checked value="0">
                                            <label for="Y" onclick="">Enable</label>
                                            <input id="N" name="team_status" type="radio" value="1">
                                            <label for="N" onclick="">Disable</label>
                                            <a class="btn btn-primary"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label"><b>App Status</b></label>
                                <div class="input-group col-sm-9">
                                    <div class="switch-toggle switch-2 well" style="margin-bottom:0px !important">
                                            <input id="Y" name="app_status" type="radio" checked value="0">
                                            <label for="Y" onclick="">Enable</label>
                                            <input id="N" name="app_status" type="radio" value="1">
                                            <label for="N" onclick="">Disable</label>
                                            <a class="btn btn-primary"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <hr/>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <label class="col-sm-12 form-control-label"><b>Education</b></label>
                                <div class="col-sm-12">
                                    <textarea class="form-control" name="education"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-12 form-control-label"><b>Experience</b></label>
                                <div class="col-sm-12">
                                    <textarea class="form-control" name="experience"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-12 form-control-label"><b>Training</b></label>
                                <div class="col-sm-12">
                                    <textarea class="form-control" name="training"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-12 form-control-label"><b>Certificate</b></label>
                                <div class="col-sm-12">
                                    <input type="file" name="certificate[]" multiple class="form-control" />
                                </div>
                            </div>
                    
                        </div>
                    </div>

				</div>

				<div class="dker p-a text-left">
					<input type="hidden" name="id" value="0"></input>
					<input type="hidden" class="form-control" id="company_id" name="company_id" value="0">
					<input type="hidden" class="form-control" id="site_id" name="site_id" value="0">
					<button type="submit" class="btn btn-fw info">Submit</button>
					<a href="<?php echo base_url('team'); ?>" class="btn btn-fw danger">Cancel</a>
				</div>
			</div>
		</form>
	</div>
</div>
<div id="SiteName" class="modal fade black-overlay" data-backdrop="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body p-lg">
				<table class="table table-bordered" id="listSiteName"></table>
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
	$(".datepicker").datepicker({
		autoclose: true,
		todayHighlight: true,
        format: 'yyyy-mm-dd'
	});
    
	$(".btnSiteNew").on('click', function(e) {
		$('#SiteName').modal('show');
		listSiteName();
	});

	$(".btnSiteRemove").on('click', function(e) {
		$('#site_name').val('');
		$('#site_id').val(0);
	});

	$("#province").on('change', function(e) {
		$.ajax({
			type: "GET",
			url: BASE_URL+'api/getCity/' + $("#province").val(),
			dataType: 'json',
			success: function(data) {
				var opt = '';
				opt = opt + '<option value="0">--- Select City ---</option>';
				for(var i = 0; i < data.length; i++) {
					opt = opt + '<option value="'+data[i].city_name+'">'+data[i].city_name+'</option>';
				}
				$("#city").html(opt);
			}
		});
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

function listSiteName() {
	var $table = $('#listSiteName');
	$table.bootstrapTable({
		method: 'GET',
		url: BASE_URL+'site/listSite',
		cache: false,
		search : true,
		pagination : true,
		striped: true,
		sidePagination: 'server',
		smartDisplay: false,
		onlyInfoPagination: false,
		columns: [
			{ field: 'site_name', title: 'Site Name', align: 'left', sortable: true },
			{ field: 'client_name', title: 'Client Name', align: 'left', sortable: true },
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
		$('#site_name').val(row.site_name);
		$('#site_id').val(row.id);
		$('#SiteName').modal('hide');
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
    

function cekPhone() {
	var phone = $("input[name='mobile_phone']").val();
	$.ajax({
		type: "POST",
		url: BASE_URL+'api/getPhone/' + phone,
		success: function(data) {
			if(data > 0) {
				swal({ title: "Error", text: "Mobile Phone <b>" + phone + "</b> sudah terdaftar", html: true, timer: 2000, type: "error", showConfirmButton: false });
			}
		}
	});
}
}
</script>