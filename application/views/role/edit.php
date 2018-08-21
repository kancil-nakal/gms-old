<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">View Role Menu</h4>
		</div>
	</div>
</div>

<?php foreach ($getData as $row) { ?>
	<div class="padding">
		<div class="row">
			<form action="<?php echo base_url('role/save'); ?>" method="post">
				<div class="box">
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group row">
									<label class="col-sm-2 form-control-label"><b>Group User</b></label>
									<div class="col-sm-10">
										<input type="text" class="form-control" placeholder="Enter Group" id="group_name" value="<?php echo $row->group_name; ?>" disabled>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-sm-2 form-control-label"><b>Module Name</b></label>
									<div class="col-sm-10 roles">
										<div class="row">
 											<?php $split = explode(',', $row->menu_id); ?>
											<?php for($i = 0 ; $i< count($parent); $i++) { ?>
												<?php
												if(in_array($parent[$i]->id,$split)){$sign='checked="checked"';}
												else {$sign='';}
												?>
												<div class="col-md-3">
													<table class="table table-striped b-t">
													<thead><tr>
														<th style="width:20px;">
															<label class="md-check">
																<input type="checkbox" name="module_id[]" <?php echo $sign;?> id="parent<?php echo $parent[$i]->id;?>" onclick="getParent(this,'<?php echo $parent[$i]->id;?>');" class="has-value" value="<?php echo $parent[$i]->id;?>"><i class="blue"></i>
															</label>
														</th>
														<th><?php echo $parent[$i]->menu_name; ?></th>
													</tr></thead>
													<tbody>
														<?php for($a = 0 ; $a< count($child); $a++) { ?>
															<?php if($child[$a]->reference_id == $parent[$i]->id) { ?>
																<?php
																if(in_array($child[$a]->id,$split)){$signs='checked="checked"';}
																else {$signs='';}
																?>
																<tr>
																	<td>
																		<label class="md-check">
																			<input type="checkbox" name="module_id[]" <?php echo $signs;?> class="has-value child<?php echo $child[$a]->reference_id;?>" onclick="getChild(this,'<?php echo $child[$a]->reference_id;?>');" value="<?php echo $child[$a]->id;?>"><i class="blue"></i>
																		</label>
																	</td>
																	<td><?php echo $child[$a]->menu_name; ?></td>
																</tr>
															<?php } ?>
														<?php } ?>
													</tbody>
													</table>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="dker p-a text-left">
						<input type="hidden" name="id" value="<?php echo $row->group_id; ?>"></input>
						<button type="submit" class="btn btn-fw info">Submit</button>
						<a href="<?php echo base_url('role'); ?>" class="btn btn-fw danger">Cancel</a>
					</div>
				</div>
			</form>
		</div>
	</div>
<?php } ?>
<script type="text/javascript">
function getParent(check,id) {
	if(check.checked == true) {
		 $(".child"+id).prop('checked', true);
	} else {
		$(".child"+id).prop('checked', false);
	}
}

function getChild(check, id) {
	if ( $(".child"+id+":checked").length == 1 ) {
		$("#parent"+id).prop('checked', true);
	}
	if ( $(".child"+id+":checked").length == 0 ) {
		$("#parent"+id).prop('checked', false);
	}
}
</script>