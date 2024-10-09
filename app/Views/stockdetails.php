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

		<!-- Flash message auto-hide script -->
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
				</header>
				<p>Files Supported: CSV</p>
				<form method="post" class="csvuploadfile" action="<?= base_url('stock/upload') ?>"
					enctype="multipart/form-data">
					<input type="file" accept=".csv" id="uploadfile" name="formData">
					<button type="submit" class="btn">Submit</button>
				</form>
				<button data-bs-target="#formatModal" data-bs-toggle="modal" class="stock-chk-btn pull-left mb-3"
					id="output" type="button">
					<span class="description">Click here to check the format.</span>
				</button>
				<a href="<?php echo base_url(); ?>uploads/stock_template.csv" class="pull-left mb-3" id="output"
					download><span class="description">(Click here to download CSV template)</span></a>
			</div>
		</div>

		<form action="<?= base_url("stock/upload") ?>" method="get">
			<input type="text" id="query" name="query" value="<?= isset($query) ? $query : ''; ?>">
			<button type="submit" id="search">Search</button>
		</form>

		<button type="button" id="reset">Reset</button>
	</div>
</section>

<!-- Filter Section -->
<section>
	<div class="container">
		<?php if (!empty($empstocklist)) { ?>
			<table class="employee-table" border="1" cellpadding="10" cellspacing="0">
				<tr>
					<td><strong>Stock ID</strong></td>
					<td><strong>Length</strong></td>
					<td><strong>Color</strong></td>
					<td><strong>Actions</strong></td>
				</tr>

				<tr>
					<td><input type="text" id="filterStockID" placeholder="Filter by Stock ID"></td>
					<td><input type="text" id="filterLength" placeholder="Filter by Length"></td>
					<td><input type="text" id="filterColor" placeholder="Filter by Color"></td>
					<td></td> 
				</tr>

				<!-- Data Rows -->
				<?php foreach ($empstocklist as $stock) { ?>
					<tr>
						<td><?php echo stripslashes($stock['stock_id']); ?></td>
						<td><?php echo stripslashes($stock['length']); ?></td>
						<td><?php echo stripslashes($stock['colour']); ?></td>
						<td>
							<form action="<?= base_url('stock/details') ?>" method="post">
								<input type="hidden" name="id" value="<?php echo stripslashes($stock['stock_list_id']); ?>">
								<button class="btn-view" type="submit">View</button>
								<button class="btn-view deleteStockDetail" type="button">Delete</button></form>
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
	// Filtering function
	function filterTable() {
		var stockIDFilter = document.getElementById('filterStockID').value.toUpperCase();
		var lengthFilter = document.getElementById('filterLength').value.toUpperCase();
		var colorFilter = document.getElementById('filterColor').value.toUpperCase();
		var table = document.querySelector('.employee-table');
		var tr = table.getElementsByTagName('tr');

		// Loop through all rows except the first two (header and filter row)
		for (var i = 2; i < tr.length; i++) {
			var tdStockID = tr[i].getElementsByTagName('td')[0];
			var tdLength = tr[i].getElementsByTagName('td')[1];
			var tdColor = tr[i].getElementsByTagName('td')[2];

			if (tdStockID && tdLength && tdColor) {
				var stockIDValue = tdStockID.textContent || tdStockID.innerText;
				var lengthValue = tdLength.textContent || tdLength.innerText;
				var colorValue = tdColor.textContent || tdColor.innerText;

				if (stockIDValue.toUpperCase().indexOf(stockIDFilter) > -1 &&
					lengthValue.toUpperCase().indexOf(lengthFilter) > -1 &&
					colorValue.toUpperCase().indexOf(colorFilter) > -1) {
					tr[i].style.display = "";
				} else {
					tr[i].style.display = "none";
				}
			}
		}
	}

	document.getElementById('filterStockID').addEventListener('keyup', filterTable);
	document.getElementById('filterLength').addEventListener('keyup', filterTable);
	document.getElementById('filterColor').addEventListener('keyup', filterTable);

	// Reset filter
	$("#reset").on("click", function () {
		document.getElementById('filterStockID').value = "";
		document.getElementById('filterLength').value = "";
		document.getElementById('filterColor').value = "";
		filterTable(); // Call filter function to reset table display
	});

	// Delete stock detail function
	$('.deleteStockDetail').on('click', function () {
		var $tr = $(this).closest('tr');
		var stockListId = $tr.find('input[name="id"]').val();
		var url = "<?= base_url('stock/delete') ?>";
		stockDetailDelete(url, stockListId);
	});
</script>

<?= $this->endSection() ?>
