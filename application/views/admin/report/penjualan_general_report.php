<?php echo link_tag('assets/global/plugins/jqvmap/jqvmap/jqvmap.css'); ?>
<?php echo link_tag('assets/global/plugins/morris/morris.css'); ?>

		<div class="page-content">
			<div class='container'>
				<div class="row margin-top-10">
					<div class="col-md-12">
						<div class="portlet light ">
							<div class="portlet-title">
								<div class="caption caption-md">
									<i class="icon-bar-chart theme-font hide"></i>
									<span class="caption-subject theme-font bold uppercase">Laporan Penjualan</span>
									<!-- <span class="caption-helper hide">weekly stats...</span> -->
								</div>
								<div class="actions">
									<!-- <div class="btn-group btn-group-devided" data-toggle="buttons">
										<label class="btn btn-transparent grey-salsa btn-circle btn-sm active">
										<input type="radio" name="options" class="toggle" id="option1">Today</label>
										<label class="btn btn-transparent grey-salsa btn-circle btn-sm">
										<input type="radio" name="options" class="toggle" id="option2">Week</label>
										<label class="btn btn-transparent grey-salsa btn-circle btn-sm">
										<input type="radio" name="options" class="toggle" id="option2">Month</label>
									</div> -->
								</div>
							</div>
							<div class="portlet-body">
								<form>
									<table>
										<tr>
											<td>Tanggal</td>
											<td> : </td>
											<td>
												<input name='tanggal_start' id="tanggalStartInput"  class='date-picker text-center' style='width:100px' value="<?=$tanggal_start?>">s/d
												<input name='tanggal_end' id="tanggalEndInput" class='date-picker text-center' style='width:100px' value="<?=$tanggal_end?>">
											</td>
										</tr>
										<tr>
											<td>Periode Tahun</td>
											<td> : </td>
											<td>
												<select style='width:100px' name="tahun" id="tahunInput">
													<?
														$tahun_start = $tahun-5;
														$tahun_end = $tahun+2;
													?>
													<?for ($i=$tahun_start; $i < $tahun_end ; $i++) {?>
														<option value="<?=$i?>" <?=($i == $tahun ? 'selected' : '')?> ><?=$i?></option>
													<?}?>
												</select>
											</td>
										</tr>
										<tr>
											<td></td>
											<td></td>
											<td>
												<button style='width:100px' class='btn btn-xs default'>OK</button>
											</td>
										</tr>
									</table>
								</form>
								<hr/>

								<?if (is_posisi_id() < 3) { ?>
									<div class="row list-separated">
										<div class="col-md-3 col-sm-3 col-xs-6">
											<div class="font-grey-mint font-sm">
												Total Pembelian <?=$ket_tgl;?>
											</div>
											<div class="uppercase font-hg font-purple">
												<?foreach ($recap_pembelian_bulanan as $row) { ?>
													Rp <?=number_format($row->amount,'0',',','.')?> <span class="font-lg font-grey-mint"></span>
												<?}?>
											</div>
										</div>
										<div class="col-md-3 col-sm-3 col-xs-6">
											<div class="font-grey-mint font-sm">
												Total Penjualan <?=$ket_tgl;?>
											</div>
											<div class="uppercase font-hg font-blue-sharp">
												<?foreach ($recap_penjualan_bulanan as $row) { ?>
													Rp <?=number_format($row->amount,'0',',','.')?> <span class="font-lg font-grey-mint"></span>
												<?}?>
											</div>
										</div>
									</div>
									<hr/>

									<div class="row list-separated">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<h4><b>Grafik Penjualan Harian <?=$ket_tgl;?></b> </h4>

											<div id="sales_statistics" class="portlet-body-morris-fit morris-chart" style="height: 200px; padding:20px;">
											</div>
											<hr/>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<h4><b>Chart Rekap Penjualan Per Tahun <?=$tahun;?></b> </h4>
											<div id="chart_1" class="chart" style="height: 200px;">
											</div>
										</div>
									</div>

									<hr/>
									<div class="row list-separated" hidden>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
											<h4><b>10 Penjualan Warna Terbanyak <?=$tahun;?></b> </h4>

											<div id="chart_warna_1" class="chart" style="height: 200px;">
											</div>
										<hr/>
											
										</div>

										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<h4><b>10 Penjualan Warna Terbanyak <?=$tahun;?>></b> </h4>

											<div id="chart_warna_2" class="chart" style="height: 400px;">
											</div>
											
										</div>

									</div>

									<hr/>
									<div class="row list-separated">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" hidden>
											<h4><b>10 Penjualan Barang Terbanyak <?=$tahun;?></b> </h4>

											<div id="chart_2" class="chart" style="height: 200px;">
											</div>
										<hr/>
											
										</div>

										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<h4><b>10 Penjualan Barang Terbanyak <?=$tahun;?></b> </h4>

											<div id="chart_3" class="chart" style="height: 400px;">
											</div>
											
										</div>

									</div>

									<hr/>

									<div class="row list-separated">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<h4><b>10 Pelanggan Terbaik <?=$tahun;?></b> </h4>

											<div id="chart_4" class="chart" style="height: 200px;">
											</div>
											<hr/>
											
										</div>

										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<h4><b>10 Pelanggan Terbaik <?=$tahun;?>></b> </h4>

											<div id="chart_5" class="chart" style="height: 400px;">
											</div>
											
										</div>

									</div>
								<?}?>

							</div>
						</div>
					</div>
				</div>
			</div>			
		</div>

<script src="<?=base_url('assets/global/plugins/morris/morris.min.js');?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/morris/raphael-min.js');?>" type="text/javascript"></script>


<script src="<?=base_url('assets/global/plugins/amcharts/amcharts/amcharts.js');?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/amcharts/amcharts/serial.js');?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/amcharts/amcharts/themes/light.js');?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/amcharts/amcharts/pie.js');?>" type="text/javascript"></script>

<script src="<?=base_url('assets_noondev/js/charts-amcharts.js');?>"></script>
<script src="<?=base_url('assets_noondev/js/index3.js'); ?>" type="text/javascript"></script>

<script>

	const tahun = '<?=$tahun?>';
$(document).ready(function() {   
	//$("#sidebar").load("sidebar.html"); 
   	Metronic.init(); // init metronic core componets
   	Layout.init(); // init layout
   	Index.init(); // init index page
   	ChartsAmcharts.init();  
});
</script>
<!-- END JAVASCRIPTS -->
