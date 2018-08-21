<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">Dashboard</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
        <div class="col-md-6 cols-sm-12">
            <div class="box p-a">
                <div class="pull-left m-r">
                    <span class="w-48 rounded info ta-pad">
                        <i class="fa fa-users text-2x m-y-sm"></i>
                    </span>
                </div>
                <div class="clear" style="height: 43px; margin-bottom: 20px;">
                    <div class="text-muted text-md _600">Total of all security guards today</div>
                </div>
                <div class="clear">
                    <?php foreach ($allsecday as $row) { ?>
                    <div class="row">
                        <div class="col-sm-5 text-md _600"><?php echo $row->att_type;?></div>
                        <div class="col-sm-7 text-md _600">: <?php echo $row->jumlah;?></div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 cols-sm-12">
            <div class="box p-a">
                <div class="pull-left m-r">
                    <span class="w-48 rounded info ta-pad">
                        <i class="fa fa-users text-2x m-y-sm"></i>
                    </span>
                </div>
                <div class="clear" style="height: 43px; margin-bottom: 20px;">
                    <div class="text-muted text-md _600">Total security guards are present today</div>
                </div>
                <div class="clear">
                    <?php foreach ($preclisecday as $row) { ?>
                    <div class="row">
                        <div class="col-sm-5 text-md _600"><?php echo $row->client_name;?></div>
                        <div class="col-sm-7 text-md _600">: <?php echo $row->jumlah;?></div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-6 cols-sm-12">
            <div class="box p-a">
                <div class="pull-left m-r">
                    <span class="w-48 rounded info ta-pad">
                        <i class="fa fa-user-plus text-2x m-y-sm"></i>
                    </span>
                </div>
                <div class="clear" style="height: 43px; margin-bottom: 20px;">
                    <div class="text-muted text-md _600">Total visitor today</div>
                </div>
                <div class="clear">
                    <div class="row">
                        <div class="col-sm-12"><hr style="margin: 5px 0;"/></div>
                    </div>
                    <div class="col-sm-12">
                        <div class="col-sm-5 text-md _600">Client</div>
                        <div class="col-sm-2 text-md _600">Pagi</div>
                        <div class="col-sm-2 text-md _600">Siang</div>
                        <div class="col-sm-2 text-md _600">Malam</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12"><hr style="margin: 5px 0;"/></div>
                    </div>
                    <?php foreach ($allclient as $row1) { ?>
                    <div class="row">
                        <div class="col-sm-6 text-md _600"><?php echo (strlen($row1->client_name) > 20 ? substr($row1->client_name, 0, 20) . '..' : $row1->client_name) ;?></div>
                    <?php 
                        $jumlah1 = '0';
                        $jumlah2 = '0';
                        $jumlah3 = '0';
                        foreach ($allvisday as $row2) { 
                            if($row1->id == $row2->client_id) {
                                if($row2->att_shift == 1) $jumlah1 = $row2->jumlah;
                                if($row2->att_shift == 2) $jumlah2 = $row2->jumlah;
                                if($row2->att_shift == 3) $jumlah3 = $row2->jumlah;
                            } 
                        }
                    ?>
                        <div class="col-sm-2 text-md _600"><?php echo $jumlah1;?></div>
                        <div class="col-sm-2 text-md _600"><?php echo $jumlah2;?></div>
                        <div class="col-sm-2 text-md _600"><?php echo $jumlah3;?></div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 cols-sm-12">
            <div class="box p-a">
                <div class="pull-left m-r">
                    <span class="w-48 rounded info ta-pad">
                        <i class="fa fa-map-marker text-2x m-y-sm"></i>
                    </span>
                </div>
                <div class="clear" style="height: 43px; margin-bottom: 20px;">
                    <div class="text-muted text-md _600">Total checkpoint today</div>
                </div>
                <div class="clear">
                    <div class="row">
                        <div class="col-sm-12"><hr style="margin: 5px 0;"/></div>
                    </div>
                    <div class="col-sm-12">
                        <div class="col-sm-5 text-md _600">Client</div>
                        <div class="col-sm-2 text-md _600">Pagi</div>
                        <div class="col-sm-2 text-md _600">Siang</div>
                        <div class="col-sm-2 text-md _600">Malam</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12"><hr style="margin: 5px 0;"/></div>
                    </div>
                    <?php foreach ($allclient as $row1) { ?>
                    <div class="row">
                        <div class="col-sm-6 text-md _600"><?php echo (strlen($row1->client_name) > 20 ? substr($row1->client_name, 0, 20) . '..' : $row1->client_name) ;?></div>
                    <?php 
                        $jumlah1 = '0';
                        $jumlah2 = '0';
                        $jumlah3 = '0';
                        foreach ($allchpoday as $row2) { 
                            if($row1->id == $row2->client_id) {
                                if($row2->att_shift == 1) $jumlah1 = $row2->jumlah;
                                if($row2->att_shift == 2) $jumlah2 = $row2->jumlah;
                                if($row2->att_shift == 3) $jumlah3 = $row2->jumlah;
                            } 
                        }
                        foreach ($allmaschpo as $row3) { 
                            if($row1->id == $row3->client_id) {
                                $jumlah1 .= '/' . $row3->jumlah;
                                $jumlah2 .= '/' . $row3->jumlah;
                                $jumlah3 .= '/' . $row3->jumlah;
                            } 
                        }
                    ?>
                        <div class="col-sm-2 text-md _600"><?php echo $jumlah1;?></div>
                        <div class="col-sm-2 text-md _600"><?php echo $jumlah2;?></div>
                        <div class="col-sm-2 text-md _600"><?php echo $jumlah3;?></div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-6 cols-sm-12">
            <div class="box p-a">
                <div class="pull-left m-r">
                    <span class="w-48 rounded info ta-pad">
                        <i class="fa fa-file-text-o text-2x m-y-sm"></i>
                    </span>
                </div>
                <div class="clear" style="height: 43px; margin-bottom: 20px;">
                    <div class="text-muted text-md _600">Total incident this month</div>
                </div>
                <div class="clear">
                    <?php foreach ($cliincmon as $row) { ?>
                    <div class="row">
                        <div class="col-sm-5 text-md _600"><?php echo $row->client_name;?></div>
                        <div class="col-sm-7 text-md _600">: <?php echo $row->jumlah;?></div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
	</div>
</div>