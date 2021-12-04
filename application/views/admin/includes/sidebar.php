<div class="hor-menu">
	<!-- BEGIN DROPDOWN MENU -->
	<ul class="nav navbar-nav">
		<li id="menu_dashboard">
			<a href="<?=site_url('dashboard');?>">
			<i class="fa fa-home"></i>
			<span class="title">Dashboard</span>
			</a>
		</li>

		<?//=print_r($common_data['user_menu_list']);?>
		<?foreach ($common_data['user_menu_list']['menu_list'] as $row) { ?>
			<li class="menu-dropdown classic-menu-dropdown" id="<?=$row->nama_id;?>">

				<a data-click="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;">
					<i class="<?=$row->icon_class;?>"></i> <span class="title"><?=$row->text;?></span> <i class="fa fa-angle-down"></i>
				</a>
				<ul class="dropdown-menu pull-left">
					<? $i = 1;
					foreach ($common_data['user_menu_list']['menu_list_detail'] as $isi) {
						if ($row->id == $isi->menu_id) { ?>
							<li id="<?=$isi->page_link;?>">
								
								<a href="<?=base_url(rtrim(base64_encode($isi->controller.'/'.$isi->page_link),'='));?>/">
								<?=$isi->text;?></a>
							</li>
							<?
							if ($i %4 == 0) { ?>
								<li class='divider'></li>
							<?}
							$i++;
							?>
						<? }
						
					}?>
							
				</ul>
			</li>
		<?}?>

	</ul>
	<!-- END DROPDOWN MENU -->
</div>

