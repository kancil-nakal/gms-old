<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">Reply Team Complaint</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
<?php foreach ($getData as $row) { 
    $team_name      = $row->team_name ? $row->team_name : '';
    $created_date   = $row->created_date ? $row->created_date : '';
    $problem        = $row->problem ? $row->problem : '';
    $status         = $row->status ? $row->status : '';
} ?>
			<form action="<?php echo base_url('team_complaint/save'); ?>" method="post">
				<div class="box">
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group row">
									<label class="col-sm-2 form-control-label"><b>Team</b></label>
									<div class="col-sm-5">
										<div class="input-group">
											<input type="text" class="form-control" id="team_name" value="<?php echo $team_name; ?>" readonly>
										</div>
									</div>
									<label class="col-sm-1 form-control-label"><b>Date</b></label>
                                    <div class="col-sm-4" id="sandbox-container">
                                        <div class="input-group date datepicker">
                                            <input type="text" class="form-control" id="created_date" value="<?php echo $created_date; ?>" readonly>
                                        </div>
                                    </div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group row">
									<label class="col-sm-2 form-control-label"><b>Problem</b></label>
                                    <div class="col-sm-10">
										<textarea id="problem" rows=5 class="form-control" readonly><?php echo $problem; ?></textarea>
                                    </div>
								</div>
							</div>
							<div class="col-sm-12">
                                <hr/>
							</div>
                        <?php 
                        foreach ($getData as $row) { 
                        if($row->comment_by) {
                        ?>
							<div class="col-sm-12">
								<div class="form-group row">
									<label class="col-sm-2 form-control-label"><b>Reply By</b></label>
									<div class="col-sm-5">
										<div class="input-group">
											<input type="text" class="form-control" id="comment_by" value="<?php echo $row->comment_by; ?>" readonly>
										</div>
									</div>
									<label class="col-sm-1 form-control-label"><b>Date</b></label>
                                    <div class="col-sm-4" id="sandbox-container">
                                        <div class="input-group date datepicker">
                                            <input type="text" class="form-control" id="comment_date" value="<?php echo $row->comment_date; ?>" readonly>
                                        </div>
                                    </div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group row">
									<label class="col-sm-2 form-control-label"><b>Comment</b></label>
                                    <div class="col-sm-10">
										<textarea id="comment" rows=5 class="form-control" readonly><?php echo $row->comment; ?></textarea>
                                    </div>
								</div>
							</div>
							<div class="col-sm-12">
                                <hr/>
							</div>
                        <?php } } ?>
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label class="col-sm-2 form-control-label"><b>Comment</b></label>
                                    <div class="col-sm-10">
                                        <textarea name="comment" rows=5 class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label class="col-sm-2 form-control-label"><b>Status</b></label>
                                    <div class="col-sm-2">
                                        <select class="form-control" name="status" id="status">
                                            <option value="0" <?php echo ($status == "0" ? "selected": ""); ?>>Open</option>
                                            <option value="1" <?php echo ($status == "1" ? "selected": ""); ?>>Closed</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
						</div>			
					</div>

					<div class="dker p-a text-left">
						<input type="hidden" name="id" value="<?php echo $row->id;?>"></input>
						<button id="btn-submit" type="submit" class="btn btn-fw info">Submit</button>
						<a href="<?php echo base_url('team_complaint'); ?>" class="btn btn-fw danger">Cancel</a>
					</div>
				</div>
			</form>
	</div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>libs/jquery/tinymce/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
$(function() {
/*
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
*/
});
</script>