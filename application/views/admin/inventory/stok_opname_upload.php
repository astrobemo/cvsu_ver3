<?=link_tag('assets/global/plugins/dropzone/css/dropzone.css'); ?>
<style>
	#tableFiles tr td, #tableFiles tr th{
		padding:5px 20px 5px 5px;
		border:1px solid #ddd;
	}

	#cover-load{
		position: fixed;
		top: 0;
		left: 0;
		width:100vw;
		height:100vh;
		background-color:rgba(0,0,0,0.4);
		display: none;
	}
</style>

<div class="page-content">
	<div class='container'>
		<div class="row margin-top-10">
			<div class="col-md-12">
				<div class="portlet light ">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">Upload</span>
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
					<form action="<?=base_url()?>stok/stok_opname/upload_file" method="post" enctype="multipart/form-data">
						Select file to upload:
						<input type="file" name="file" id="fileToUpload">
						<br>
						<span id='fileInfo'></span>
						<br>
						<input type="submit" id='submit-button' value="Upload File" name="submit">
					</form>



					<!-- <form  action="<?=base_url('stok/stok_opname/uploadFile'); ?> " class="dropzone" id="my-dropzone">
					</form> -->

					</div>
				</div>
			</div>

			<div class="col-md-12">
				<div class="portlet light ">
					<div class="portlet-title">
						<div class="caption caption-md">
							<i class="icon-bar-chart theme-font hide"></i>
							<span class="caption-subject theme-font bold uppercase">HISTORY</span>
							<!-- <span class="caption-helper hide">weekly stats...</span> -->
						</div>
						<div class="actions">
						</div>
					</div>
					<div class="portlet-body">
						<?$dir = "./uploads";
						$files = scandir($dir); ?>
						<table id='tableFiles'>
							<tr>
								<th>Nama Files</th>
								<th>Date Modified</th>
								<th>Action</th>
							</tr>
							<?
							/* $date_minimal = date("Y-m-d", strtotime("-1month"));
							$file_list = array();
							foreach ($files as $key => $value) {
								$flnm = explode(".",$value);
								$dm = date ("Y-m-d", filemtime("./uploads/".$value));
								$diff = strtotime($dm) - strtotime($date_minimal);
								if ($value != '.' && $value != '..') {
									array_push($file_list, $value);
									# code...
								}
								if (strtolower($flnm[count($flnm) - 1]) == 'csv' && $diff > 0) {
									?>
									<tr>
										<td><?=$value?></td>
										<td><?=date ("F d Y H:i:s", filemtime("./uploads/".$value))?></td>
										<td><a class='btn btn-xs green' onclick='showLoad()' href="<?=base_url()?>stok/stok_opname/insertFileMutasi?nama=<?=$value;?>">Use This File</a></td>
									</tr>	
								<?}?>
							<?} */
							$idx = 0;
							foreach ($fileHistory as $row) { ?>
								<tr>
									<td><?=$row->nama_file?></td>
									<td><?=date ("F d Y H:i:s", strtotime($row->created_at))?></td>
									<td>
										<?if ($idx == 0) {?>
											<a hidden  onclick='showLoad()' href="<?=base_url()?>stok/stok_opname/insertFileMutasi?nama=<?=$row->nama_file;?>">Use This File</a>
											<a class='btn btn-xs green' onclick='showLoad()' href="<?=base_url()?>stok/stok_opname/show_file_uploaded">View Data</a>
										<?}?>
									</td>
								</tr>	
							<?$idx++;}?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>			
</div>

<div id='cover-load' class='text-center'>
	<h1 style='position:relative; top:20%; color:white;'>uploading....</h1>
</div>

<script src="<?php echo base_url('assets/global/plugins/bootbox/bootbox.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/dropzone/dropzone.js'); ?>"></script>

<script>

var fileList = [];
var fileAr = [];

<?/* foreach ($fileHistory as $key => $value) {?>
	fileList["<?=trim($value)?>"] = true;
	fileAr.push("<?=$value?>");
<?} */?>

jQuery(document).ready(function() {
	// cek_harga_barang
	// getText();

	$("#fileToUpload").change(function(){
		const fileToRead = document.getElementById("fileToUpload");
		const file = fileToRead.files;
		const name = file[0].name;
		const type = file[0].type;

		for (let m = 0; m < fileAr.length; m++) {
			console.log(fileAr[m] +'=='+ name,fileAr[m] == name.replaceAll(" ","_"));
		}
		if (typeof fileList[name.replaceAll(" ","_")] !== 'undefined') {
			$("#fileInfo").html(`File dengan nama ${name} sudah ada`);
		}



	});
	
});

function showLoad(){
	$("#cover-load").show();
}
</script>
