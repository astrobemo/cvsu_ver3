<!DOCTYPE html>
<html lang="en" class="no-js" style="background: #3b434c;">
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>CV. SU - Sistem</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<link rel="shortcut icon" href="<?=base_url();?>image/icon.png" type="image/x-icon">
<script type="text/javascript">
  var baseurl = "<?php print base_url(); ?>";
  	function btn_disabled_load(ini){
	    $(".btn-active").prop('disabled',true);
	    // ini.prop('disabled',true);
	    ini.html("<i class='fa fa-upload'></i> load...");
	}

	const colorToko = [];
	<?foreach (get_color_toko() as $key => $value) {?>
		colorToko[<?=$key;?>] = "<?=$value?>";
	<?}?>

</script>

<?php include("stylesheet.php"); ?>
<?php include("script.php"); ?>


</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-fixed-mobile" and "page-footer-fixed-mobile" class to body element to force fixed header or footer in mobile devices -->
<!-- DOC: Apply "page-sidebar-closed" class to the body and "page-sidebar-menu-closed" class to the sidebar menu element to hide the sidebar by default -->
<!-- DOC: Apply "page-sidebar-hide" class to the body to make the sidebar completely hidden on toggle -->
<!-- DOC: Apply "page-sidebar-closed-hide-logo" class to the body element to make the logo hidden on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-hide" class to body element to completely hide the sidebar on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-fixed" class to have fixed sidebar -->
<!-- DOC: Apply "page-footer-fixed" class to the body element to have fixed footer -->
<!-- DOC: Apply "page-sidebar-reversed" class to put the sidebar on the right side -->
<!-- DOC: Apply "page-full-width" class to the body element to have full width page without the sidebar menu -->

<body>

<div class="page-header" style='height:auto'>
	<!-- BEGIN HEADER INNER -->

	<div class='page-header-top'>
		<div class="container">
			<div class="page-logo">
				<a href="<?=base_url();?>admin"><h1 style='color:#E87E04'>CV. SU</h1></a>
			</div>
			<a href="javascript:;" class="menu-toggler"></a>
			
			<div class='top-menu'>
				<ul class="nav navbar-nav pull-right">
					
					<?/* if (is_posisi_id() <= 3 || is_posisi_id() == 6) {?>
						<li class="dropdown dropdown-extended dropdown-tasks" >
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
							<i class="fa fa-bell"></i>
							<?
								if(get_piutang_warn()->num_rows() != 0){?>
									<span class="badge badge-default">
									<?=get_piutang_warn()->num_rows() ;?> </span>
								<?}
							?>
							</a>
							<ul class="dropdown-menu extended tasks"  style="height: 400px; overflow:scroll" >
								<li>
									<a  style="background:#eee"><b>Piutang Jatuh Tempo : </b></a>
									<ul>
										<?foreach (get_piutang_warn()->result() as $row) {?>
											<li>
												<a target='_blank' style='padding:0px; color:blue; display:inline' href="<?=base_url().is_setting_link('finance/piutang_payment_form').'?customer_id='.$row->customer_id;?>&toko_id=<?=$row->toko_id;?>&tanggal_start=<?=$row->tanggal_start;?>&tanggal_end=<?=$row->tanggal_end;?>&status_jt=1"> 
													<?=$row->nama_customer;?> : <b> 
												</a>
												<?=$row->counter_invoice;?> </b> invoice jatuh tempo, nilai : <b> <?=number_format($row->sisa_piutang,'0',',','.')?> </b> </li>
										<?}?>
									</ul>
								</li>
								<li>
									<a style="background:#ddd"><b>Hutang Jatuh Tempo : </b></a>
									<ul>
										<?foreach (get_hutang_warn()->result() as $row) {?>
											<li>
												<a target='_blank' style='padding:0px; color:blue; display:inline' href="<?=base_url().is_setting_link('finance/hutang_payment_form').'?supplier_id='.$row->supplier_id;?>&toko_id=<?=$row->toko_id;?>&tanggal_start=<?=$row->tanggal_start;?>&tanggal_end=<?=$row->tanggal_end;?>"> 
													<?=$row->nama_supplier;?> : <b> 
												</a>
												<?=$row->counter_invoice;?> </b> invoice jatuh tempo, nilai : <b> <?=number_format($row->sisa_hutang,'0',',','.')?> </b> </li>
										<?}?>
									</ul>
								</li>
							</ul>
						</li>
					<?} */?>

					
					<li class="dropdown dropdown-user dropdown-dark">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						<img alt="" class="img-circle" style='top:5px;position:relative;height:30px' src="<? echo base_url('assets/admin/layout/img/avatar.png'); ?>"/>
						<span style='' class="username username-hide-on-mobile">
						<?=is_username(); ?> </span>
						<i class="fa fa-angle-down"></i>
						</a>
						<ul class="dropdown-menu dropdown-menu-default">
							<li>
								<a href="<? echo base_url('home/logout');?>">
								<i class="icon-key"></i> Log Out </a>
							</li>
						</ul>
					</li>

				</ul>
			</div>
		</div>
	</div>

	<div class="page-header-menu">
		<div class='container'>
			<? include('sidebar.php'); ?>
		</div>
	</div>
	<!-- END HEADER INNER -->
</div>


<!-- BEGIN HEADER -->
<!-- END HEADER -->
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!-- BEGIN SIDEBAR -->
	<!-- END SIDEBAR -->
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">

		<div class='page-head'>
			<div class='container'>
				<div class="page-title">
					<h1><?=$breadcrumb_title;?> <small><?=$breadcrumb_small;?></small></h1> 
				</div>
					
			</div>
		</div>

		