<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php echo script_tag('js/jquery.min.js'); ?>
<?php echo script_tag('js/functions/Script.js'); ?>
<section class="upload-files">
	<div class="container">
		<?php if (session()->getFlashdata('response') !== NULL): ?>
			<p style="color:green; font-size:18px;">
				<?php echo session()->getFlashdata('response'); ?>
			</p>
		<?php endif; ?>
		<!-- Flash message -->
		<?php if (session()->getFlashdata('success')): ?>
			<div class="alert alert-success" id="flash-success">
				<?= session()->getFlashdata('success') ?>
			</div>
		<?php endif; ?>

		<?php if (session()->getFlashdata('error')): ?>
			<div class="alert alert-danger" id="flash-error">
				<?= session()->getFlashdata('error') ?>
			</div>
		<?php endif; ?>
		<!-- Flash message gone in 5 sec -->
		<script>
			document.addEventListener("DOMContentLoaded", function () {
				var timeoutDuration = 5000;
				function hideFlashMessage(id) {
					var element = document.getElementById(id);
					if (element) {
						setTimeout(function () {
							element.style.opacity = '0';
							setTimeout(function () {
								element.style.display = 'none';
							}, 600);
						}, timeoutDuration);
					}
				}
				hideFlashMessage('flash-success');
				hideFlashMessage('flash-error');
			});
		</script>

		<div class="card">
			<h3>Stock Upload Files</h3>
			<div class="drop_box">
				<header>
					<h4>Select CSV File here</h4>
					<!-- Updated text for CSV file selection -->
				</header>
				<p>Files Supported: CSV</p>
				<!-- Updated to include CSV -->
				<form method="post" class="csvuploadfile" action="<?= base_url('stock/upload') ?>"
					enctype="multipart/form-data">
					<input type="file" accept=".doc,.docx,.pdf,.csv" id="uploadfile" name="formData">
					<!-- Removed 'hidden' attribute -->
					<button type="submit" class="btn">Submit</button>
				</form>
				<button data-bs-target="#formatModal" data-bs-toggle="modal" class="stock-chk-btn pull-left mb-3"
					id="output" type="button">
					<span class="description">click here to check the format.
					</span>
				</button>

				<a href="<?php echo base_url(); ?>uploads/stock_template.csv" class="pull-left mb-3" id="output"
					download><span class="description">(click here to download CSV
						template)</span></a>
			</div>
		</div>
		<form action="<?= base_url("stock/upload") ?>" method="get">
			<input type="text" placeholder="" id="query" name="query" value="<?= isset($query) ? $query : ''; ?>">
			<button type="submit" id="search">Search</button>
		</form>
		<button type="button" id="reset">Reset</button>
	</div>
	<div class="container">
		<div class="modal fade" id="formatModal">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="padding:15px 50px;">
						<h4>Format</h4>
						<button type="button" class="close top-close" data-bs-dismiss="modal">&times;</button>

					</div>
					<div class="modal-body ctr-segment-body" style="padding:20px;">
						<table class="stock-modal-popup">
							<thead>
								<tr>
									<td>Colour</td>
									<td>Texture</td>
									<td>Size</td>
									<td>Type</td>
									<td>Quantity</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<?php foreach ($stdData->Colours as $Colour) { ?>
											<div>
												<?php echo $Colour; ?>
											</div>
										<?php } ?>
									</td>
									<td style="height:05px; vertical-align:top;">

										<?php foreach ($stdData->Textures as $texture) { ?>
											<div>
												<?php echo $texture; ?>
											</div>
										<?php } ?>
									</td>
									<td style="height:05px; vertical-align:top;">
										<?php foreach ($stdData->Length as $length) { ?>
											<div>
												<?php echo $length; ?>
											</div>
										<?php } ?>

									</td>
									<td style="height:05px; vertical-align:top;">
										<?php foreach ($stdData->Types as $type) { ?>
											<div>
												<?php echo $type; ?>
											</div>
										<?php } ?>

									</td>
									<td style="height:05px; vertical-align:top;">
										Quantity should be in grams.
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

	</div>
</section>
<section>
	<div class="container">
		<?php if (!empty($empstocklist)) { ?>
			<table class="employee-table">
				<!-- <th> -->
				<td>
					<b>Stock ID</b>
				</td>
				<td>
					<b>Length</b>
				</td>

				<td>
					<b>Color</b>
				</td>
				<!-- <td>
										<b>Texture</b>
								</td>
								<td>
										<b>Unit</b>
								</td> -->
				<td>
					<b>Action</b>
				</td>
				<!-- </th> -->
				<?php $count = 0;
				foreach ($empstocklist as $stock) {
					$count++; ?>
					<tr id="stock-det-row">
						<td class="d-none">
							<?php echo stripslashes($stock['stock_list_id']); ?>
						</td>
						<td>
							<?php echo stripslashes($stock['stock_id']); ?>
						</td>
						<td>
							<?php echo stripslashes($stock['length']); ?>
						</td>
						<td>
							<?php echo stripslashes($stock['colour']); ?>
						</td>
						<!-- <td>
														<?php echo stripslashes($stock['texture']); ?>
												</td>
												<td>
														<?php echo stripslashes($stock['unit']); ?>
												</td> -->

						<td class="actions">

							<form action="<?= base_url('stock/details') ?>" method="post">
								<input type="text" name="id" value="<?php echo stripslashes($stock['stock_id']); ?>" hidden>

								<button class="btn-view" type="submit">View</button>
							</form>
							&nbsp;
							<button class="btn-view deleteStockDetail" type="buttom">Delete</button>
						</td>
					</tr>
				<?php } ?>
			</table>
		<?php } else { ?>
			<div class="no-data">
				<p>No data found!</p>
			</div>
		<?php } ?>
	</div>

</section>

<script>
	$('.deleteStockDetail').on('click', function () {
		//getting data of selected row using id.

		console.log("ENtry");
		$id = document.getElementById('stock-det-row');
		console.log($id);
		$tr = $(this).closest('tr');
		console.log($tr);
		var data = $tr.children().map(function () {
			return $(this).text();
		}).get();
		console.log(data[0].trim());
		var taskDetId = data[0].trim();
		var url = "<?= base_url('stock/delete') ?>";
		console.log(url);
		stockDetailDelete(url, taskDetId);
	});

	$("#reset").on("click", function () {

		var currentUrl = window.location.href;
		window.location.href = currentUrl.split('?')[0];
	})
</script>
<?= $this->endSection() ?>