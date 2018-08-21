<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="p-a white lt box-shadow">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="m-b-0 _300">XTOOLS</h4>
		</div>
	</div>
</div>

<div class="padding">
	<div class="row">
		<div class="box">
			<div class="box-body">
				<div class="nav-active-border b-info">
					<table width="100%" cellpadding="10" cellspacing = "5">
						<tr><td>Tanggal</td><td style="text-align:left">Telpon</td><td style="text-align:left">User</td><td style="text-align:left">KM</td><td style="text-align:right">SALDO</td>
							<td style="text-align:right">Driver</td><td style="text-align:right">TEMPELAD</td><td></td><td>new calc</td>
						</tr>
						<?php 
						$subtotaldanaclient = 0; $totaldanaclient = 0;
						$subtotaldanadriver = 0; $subtotaldanadriverbaru = 0; $totaldanadriver = 0; $totaldanadriverbaru = 0;
						$subtotaldanatmpelad = 0; $totaldanatmpelad = 0;
						$subtotalkm = 0;
						for($i=0;$i<count($transaksi);$i++)
						{ 
							$subtotaldanaclient = $subtotaldanaclient + $transaksi[$i]['totalsaldo'];
							$subtotaldanadriver = $subtotaldanadriver + $transaksi[$i]['danadriver'];
							$subtotaldanadriverbaru = $subtotaldanadriverbaru + $transaksi[$i]['danadriverbaru'];
							$subtotaldanatmpelad = $subtotaldanatmpelad + $transaksi[$i]['danatempelad'] + $transaksi[$i]['danaselisih'] ;
							$subtotalkm = $subtotalkm +  $transaksi[$i]['totalkm'];

							$totaldanaclient = $totaldanaclient + $transaksi[$i]['totalsaldo'];
							$totaldanadriver = $totaldanadriver + $transaksi[$i]['danadriver'];
							$totaldanadriverbaru = $totaldanadriverbaru + $transaksi[$i]['danadriverbaru'];
							$totaldanatmpelad = $totaldanatmpelad +  $transaksi[$i]['danatempelad'] + $transaksi[$i]['danatempelad'] ;
							
							?>
							<tr><td><?php echo $transaksi[$i]['date'];?></td>
								<td style="text-align:left"><?php echo $transaksi[$i]['phone'];?></td>
								<td style="text-align:left"><?php echo $transaksi[$i]['membername'];?></td>
								<td style="text-align:right"><?php echo $transaksi[$i]['totalkm']; ?> / <?php echo number_format($transaksi[$i]['totalkm'],0,",",".");?></td><td style="text-align:right"><?php echo number_format($transaksi[$i]['totalsaldo'],0,",",".");?></td>
								<td style="text-align:right"><?php echo number_format($transaksi[$i]['danadriver'],0,",",".");?></td>
								<td style="text-align:right"><?php echo number_format($transaksi[$i]['danatempelad'] + $transaksi[$i]['danaselisih'],0,",",".");?></td>
								<td style="text-align:right"><?php echo number_format($transaksi[$i]['danatempelad'],0,",",".");?> + <?php echo number_format($transaksi[$i]['danaselisih'],0,",",".");?></td>

								<td style="text-align:right"><?php echo number_format($transaksi[$i]['danadriverbaru'],0,",",".");?></td>
								
							</tr>
							<?php if( $i > 1 && $i < count($transaksi) - 1 && ($transaksi[$i]['date'] != $transaksi[$i+1]['date']) || $i == count($transaksi)-1 ){ ?>
								<tr><td></td><td></td><td></td><td style="text-align:right"><?php echo $subtotalkm;?></td><td style="text-align:right"><b><?php echo number_format($subtotaldanaclient,0,",",".");?></b></td>
							<td style="text-align:right;"><b><?php echo number_format($subtotaldanadriver,0,",",".");?></b></td>
							<td style="text-align:right;"><b><?php echo number_format($subtotaldanatmpelad,0,",",".");?></b></td><td></td>
							<td style="text-align:right;"><b><?php echo number_format($subtotaldanadriverbaru,0,",",".");?></b></td>
							
							</tr>

							<?php $subtotaldanaclient = 0; $subtotaldanadriver = 0; $subtotaldanadriverbaru = 0; $subtotalkm = 0; $subtotaldanatmpelad = 0;}  ?>
						
						<?php } ?>
				
							<tr><td></td><td></td><td></td><td style="text-align:right"></td><td style="text-align:right"><b><?php echo number_format($totaldanaclient,0,",",".");?></b></td>
							<td style="text-align:right;"><b><?php echo number_format($totaldanadriver,0,",",".");?></b></td><td style="text-align:right;"><b><?php echo number_format($totaldanatmpelad,0,",",".");?></b></td>
							<td></td><td style="text-align:right;"><b><?php echo number_format($totaldanadriverbaru,0,",",".");?></b></td>
							</tr>

					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="box">
			<div class="box-body">
				<div class="nav-active-border b-info">
					<table width="70%" >
						<tr>
						<td>Nama</td>
						<td>status</td>
						<td>Nominal</td>
						</tr>
						<?php 	//print_r($rupiah);
						$total = 0;
						$belumbayar = 0;
						$sudahbayar = 0;
						for($i=0;$i<count($rupiah);$i++)
						{
						
							?>
						<tr>
							<td><?php echo $rupiah[$i]['membername'];?></td>
							<td><?php 
							$total = $total + $rupiah[$i]['totalearn'];

							if($rupiah[$i]['statusexe'] == 0){echo "belum tarik dana"; $belumbayar = $belumbayar + $rupiah[$i]['totalearn'];}
							else if($rupiah[$i]['statusexe'] == 1){ echo "pending Admin"; $sudahbayar = $sudahbayar + $rupiah[$i]['totalearn'];}
							else{echo "sudah dbayar";}
							;?></td>
							<td style="text-align:right;"><?php echo  number_format($rupiah[$i]['totalearn'],0,",",".");?></td>

						</tr>
						<?php } ?>
						<tr>
							<td colspan="2">Total Belum Bayar</td><td style="text-align:right;"><?php echo number_format($total,0,",",".");?></td>
						</tr>
						<tr>
							<td colspan="2">Total Sudah Bayar</td><td style="text-align:right;"><?php echo number_format($total,0,",",".");?></td>
						</tr>
						<tr>
							<td colspan="2">Total Pengurangan CLient</td><td style="text-align:right;"><?php echo number_format($total,0,",",".");?></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	<!-- <div class="row">
		<div class="box">
			<div class="box-body">
				<div class="nav-active-border b-info">
					<ul class="nav nav-md">
						<?php
						if($_SESSION['login']['group_user'] != '3') {
							?>
							<li class="nav-item inline">
								<a class="nav-link active" href="" data-toggle="tab" data-target="#tab_1">
									<span class="text-md">Withdraw</span>
								</a>
							</li>
							<li class="nav-item inline">
								<a class="nav-link" href="" data-toggle="tab" data-target="#tab_2">
									<span class="text-md">List Withdraw APTI</span>
								</a>
							</li>
							<li class="nav-item inline">
								<a class="nav-link" href="" data-toggle="tab" data-target="#tab_3">
									<span class="text-md">Approve APTI</span>
								</a>
							</li>
							<?php
						} else {
							?>
							<li class="nav-item inline">
								<a class="nav-link active" href="" data-toggle="tab" data-target="#tab_1">
									<span class="text-md">Withdraw</span>
								</a>
							</li>
							<li class="nav-item inline">
								<a class="nav-link" href="" data-toggle="tab" data-target="#tab_2">
									<span class="text-md">Approve Withdraw</span>
								</a>
							</li>
							<?php
						}
						?>
					</ul>
				</div>

				<div class="tab-content clear b-t">
					<?php
						if($_SESSION['login']['group_user'] != '3') {
						?>
						<div class="tab-pane active" id="tab_1">
							<div id="toolbarWithdraw">
								<a href="<?php echo base_url('withdraw/export/0'); ?>" class="btn btn-block btn-success">
									<i class="fa fa fa-file-excel-o"></i> Export to Ecel
								</a>
							</div>
							<table class="table table-bordered" id="listWithdraw"></table>
						</div>
						<div class="tab-pane" id="tab_2">
							<div id="toolbarFinance">
								<a href="<?php echo base_url('withdraw/export/1'); ?>" class="btn btn-block btn-success">
									<i class="fa fa fa-file-excel-o"></i> Export to Ecel
								</a>
							</div>
							<table class="table table-bordered" id="listFinance"></table>
						</div>
						<div class="tab-pane" id="tab_3">
							<div id="toolbarAPTI">
								<a href="<?php echo base_url('withdraw/export/2'); ?>" class="btn btn-block btn-success">
									<i class="fa fa fa-file-excel-o"></i> Export to Ecel
								</a>
							</div>
							<table class="table table-bordered" id="listAPTI"></table>
						</div>
						<?php
					} else {
						?>
						<div class="tab-pane active" id="tab_1">
							<div id="toolbarWithdraw">
								<a href="<?php echo base_url('withdraw/export/1'); ?>" class="btn btn-block btn-success">
									<i class="fa fa fa-file-excel-o"></i> Export to Ecel
								</a>
							</div>
							<table class="table table-bordered" id="listWithdraw"></table>
						</div>
						<div class="tab-pane" id="tab_2">
							<div id="toolbarFinance">
								<a href="<?php echo base_url('withdraw/export/2'); ?>" class="btn btn-block btn-success">
									<i class="fa fa fa-file-excel-o"></i> Export to Ecel
								</a>
							</div>
							<table class="table table-bordered" id="listFinance"></table>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
	</div> -->
</div>
<?php if($_SESSION['login']['group_user'] != '3') { ?>
	<script type="text/javascript" src="<?= base_url() ?>scripts/apps/withdraw.js"></script>
<?php } else { ?>
	<script type="text/javascript" src="<?= base_url() ?>scripts/apps/withdraws.js"></script>
<?php } ?>