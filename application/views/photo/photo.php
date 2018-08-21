<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?php echo base_url(); ?>libs/jquery/lightbox/css/lightbox.min.css">
<script src="<?php echo base_url(); ?>libs/jquery/lightbox/js/lightbox-plus-jquery.min.js"></script>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">Photo</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<div class="box">
			<div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<div class="row" id="itemContainer">
							<?php
							for($i=0; $i<count($getPhoto); $i++) {
								?>
								<div class="col-xs-6 col-sm-4 col-md-2">
									<div class="box p-a-xs"> 
										<a class="example-image-link" href="http://tempeladapi.mesinrusak.com/uploads/<?php echo $getPhoto[$i]['images'] ;?>" data-lightbox="<?php echo $getPhoto[$i]['member_id'];?>" data-title="<?php echo $getPhoto[$i]['member_name'];?>">
											<img src="http://tempeladapi.mesinrusak.com/uploads/<?php echo $getPhoto[$i]['images'] ;?>" alt="" class="img-responsive">
										</a>
										<div class="p-a-sm">
											<div class="text-ellipsis text-center"><?php echo $getPhoto[$i]['no_pol'] . '<br />' . $getPhoto[$i]['member_name'] . '<br />' . $getPhoto[$i]['date'] ;?></div>
										</div>
									</div>
								</div>
								<?php
							}
							?>
							<div class="holder"></div>
						</div>
<!-- 						<div>
							<ul class="pagination pagination-sm m-a-0">
								<?php echo $pagination_helper->create_links(); ?>
							</ul>
						</div> -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
