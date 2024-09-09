<?= $this->extend("layouts/app") ?>
<?= $this->section("body") ?>
<?php echo script_tag('js/jquery.min.js'); ?>
<?php echo script_tag('js/functions/Script.js'); ?>
<section class="home">
	<style>
		.expand-button {
			cursor: pointer;
			color: green;
		}
	</style>
	<div class="container">
		<?php if (session()->getFlashdata('response') !== NULL) : ?>
			<p style="color:green; font-size:18px;">
				<?php echo session()->getFlashdata('response'); ?>
			</p>
		<?php endif; ?>
		<div class="title-head">
			<div class="search-title">
				<!-- <form id="filter_order_form" action="<?= base_url("order/filter") ?>" method="get"> -->
				<input type="text" id="query" name="query">
				<button type="submit" id="search"> Search</button>
			</div>
			<!-- </form> -->
			<div class="text-center"><a class="crt-sur" href="<?php echo site_url('/order/createOrder'); ?>">Create Order</a>
			</div>
		</div>
		<div class="order-list-table">
			<?php if (!empty($orderList)) { ?>
				<table id="order_table" class="table mt-6 table-striped table-bordered">
					<thead>
						<tr class="sur-lis-bd">
							<th scope="col">S.No</th>
							<th scope="col" style="display:none;"> Id </th>
							<th scope="col"> Item List </th>

							<th scope="col">Order Id</th>
							<!-- <th scope="col">Item number</th> -->
							<th scope="col">Customer Id</th>
							<th scope="col">Order date</th>
							<!-- <th scope="col">Item description</th>
							<th scope="col">Bundle count</th>
							<th scope="col">Quantity</th>
							<th scope="col">Status</th>
							<th scope="col">Due date</th>
							<th scope="col">Action</th> -->

						</tr>
					</thead>
					<tbody id="table_body">
						<?php $count = 0;
						$currentOrderId = null;
						foreach ($orderList as $order) {
							$count++;
							if ($order['order_id'] != $currentOrderId) {
								$currentOrderId = $order['order_id'];
						?>

								<tr id="orderRow">
									<td scope="row">
										<?php echo $count; ?>
									</td>
									<td style="display:none;">
										<?php echo stripslashes($order['order_list_id']); ?>
									</td>
									<td><span class="expand-button" onclick="getItemList('<?= $order['order_id'] ?>')">
											<i class="bi bi-caret-right-fill"></i>
										</span></td>
									<td>
										<?php echo stripslashes($order['order_id']); ?><br />
										<div id="item-button">
											<button type="button" class="addItem" onclick="addItem('<?= $order['order_id'] ?>')">AddItem +</button>
										</div>
									</td>
									<!-- <td>
										<?php echo stripslashes($order['item_id']); ?>
									</td> -->

									<td>
										<?php echo stripslashes($order['customer_id']); ?>
									</td>
									<td>
										<?php echo stripslashes($order['order_date']); ?>
									</td>
									<!-- <td>
										<?php echo $order['type'] . " " . $order['colour'] . " " . $order['length'] . " " . $order['texture'] . " " . $order['ext_size']; ?>
									</td>

									<td>
										<?php echo stripslashes($order['bundle_count']) ?>
									</td>
									<td>
										<?php echo stripslashes($order['quantity']) ?>
									</td>
									<td>
										<?php echo stripslashes($order['status']); ?>
									</td>
									<td>
										<?php echo stripslashes($order['due_date']); ?>
									</td>
									<td class="action">

										<button type="button" onclick="editOrder(this)" <?php if ($order['status'] != "Not started") {
																							echo 'style="pointer-events:none"';
																						} ?> class="btn editOrder"> <img src="<?php echo base_url(); ?>images/icons/Create.png" class="img-centered img-fluid"></a>
											<button type="button" onclick="deleteOrder(this)" <?php if ($order['status'] != "Not started") {
																									echo 'style="pointer-events:none"';
																								} ?>class="btn  deleteOrder"><img src="<?php echo base_url(); ?>images/icons/remove.png" class="img-centered img-fluid"></a>
									</td> -->
									<!-- <td>
            </td> -->
								</tr>

						<?php }
						} ?>
					</tbody>

				</table>
			<?php } else { ?>
		</div>
		<div class="text-center">
			<p class="fs-3"> <span class="text-danger">Oops!</span>No records found.</p>
		</div>
	<?php } ?>
	</div>
	<div class="container">
		<div class="modal fade" id="orderDeleteModal">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="padding:15px 50px;">
						<h4> Delete order</h4>
						<button type="button" class="close top-close" data-bs-dismiss="modal">&times;</button>

					</div>
					<form action="<?= base_url('order/deleteOrder') ?>" class="form" id="DeleteSegmentForm" name="DeleteSegment" method="post">
						<div class="modal-body ctr-segment-body" style="padding:20px;">
							<p> Are you sure you want to delete the order?</p>
							<div class="form-group">
								<input type="hidden" class="form-control" id="orderListId" name="orderListId">
							</div>
							<br />
							<div class="d-grid">
								<button type="submit" class="btn btn-danger confirm pull-right"><span class="fa fa-trash"></span>
									Confirm</button>
								<button type="button" class="btn btn-outline-secondary Cancel pull-left close" data-bs-dismiss="modal"><span class="fa fa-remove"></span> Cancel</button>
							</div>
						</div>
					</form>


				</div>
			</div>
		</div>

	</div>

	<div class="container">
		<div class="modal fade" id="itemListModal">
			<div class="modal-dialog modal-xl">
				<!-- Modal content-->
				<div class="modal-content">

					<table id="modal_order_table" class="table mt-6 table-striped table-bordered">
						<thead>
							<tr class="sur-lis-bd">
								<th scope="col">S.No</th>
								<th scope="col" style="display:none;"> Id </th>
								<th scope="col">Order Id</th>
								<th scope="col">Item number</th>
								<th scope="col">Customer Id</th>
								<th scope="col">Order date</th>
								<th scope="col">Item description</th>
								<th scope="col">Bundle count</th>
								<th scope="col">Quantity</th>
								<th scope="col">Status</th>
								<th scope="col">Due date</th>
								<th scope="col">Action</th>

							</tr>
						</thead>
						<tbody id="modal_table_body">
						</tbody>

				</div>
			</div>
		</div>

	</div>
</section>
<script>
	$(document).ready(function() {
		window.onpopstate = function(event) {
			// Reset or empty the input element
			$('#query').val('');
		};
	});
	$("#search").on("click", function() {


		var query = $("#query").val();
		// if (query.length > 0) {
		$.ajax({
			url: "<?= base_url("order/filter") ?>",
			type: "get",
			data: {
				query: query
			},
			dataType: "json",
			success: function(response) {

				if (response.success) {
					var table_body = document.getElementById('table_body');

					populateTable(response.output, table_body, false)
				}

			},
			error: function(response) {

			}
		})
	})

	function populateTable(data, table_body, isModal) {

		if (data.length > 0) {
			table_body.innerHTML = "";
			var header = true;
			var index = 0;
			data.forEach(function(item) {

				index++;
				console.log(item);
				var row = table_body.insertRow();
				var count = 0;

				var s_no = row.insertCell(count);
				s_no.innerHTML = index;
				for (let key in item) {
					if ((key == "item_id" && !isModal) || (count > 4 && !isModal)) {
						continue;
					}

					count++

					console.log(`${key}: ${item[key]}`);
					var cell = row.insertCell(count);

					if (key == "order_list_id") {

						cell.style.display = 'none';
					}
					if (key == "order_id") {
						if (!isModal) {
							cell.innerHTML = '<span class="expand-button" onclick="getItemList(' + item[key] + ')"><i class="bi bi-caret-right-fill"></i></span>'

							count++;
							var cell = row.insertCell(count);
						}
						cell.innerHTML = item[key] + '<div id="item-button"><button type="button" class="addItem" onclick="addItem(' + item[key] + ')">AddItem +</button></div>'; // Update with new data


					} else {
						cell.innerHTML = item[key];
					}

				}
				if (isModal) {
					var cell = row.insertCell(count + 1);
					if (item["status"] != "Not started") {
						var style = 'style="pointer-events:none"';

					}
					else style="";
					cell.innerHTML = '<button type="button" onclick="editOrder(this)" class="btn editOrder" '+style+'> <img src="<?php echo base_url(); ?>images/icons/Create.png" class="img-centered img-fluid"></a><button type="button" onclick="deleteOrder(this)" class="btn  deleteOrder" '+style+'><img src="<?php echo base_url(); ?>images/icons/remove.png" class="img-centered img-fluid"></a>';

				}
			});
		} else {
			table_body.innerHTML = "No matching order found";
		}
	}

	function deleteOrder(element) {
		//getting data of selected row using id.
		console.log("ENtry");
		$tr = $(element).closest('tr');
		var data = $tr.children().map(function() {
			return $(this).text();
		}).get();
		console.log(data[1].trim());
		var orderId = data[1].trim();
		$('#orderListId').val(orderId);
		$("#orderDeleteModal").modal('show');

	}

	function editOrder(element) {
		//getting data of selected row using id.

		$tr = $(element).closest('tr');
		var data = $tr.children().map(function() {
			return $(this).text();
		}).get();
		console.log(data[1].trim());
		var orderId = data[1].trim();
		var url = "<?= base_url('order/editOrder/') ?>" + orderId;
		window.location.href = url;

	}

	function addItem(orderId) {
		var url = "<?= base_url('order/generateItemId/') ?>" + orderId;;
		window.location.href = url;
	}

	function getItemList(orderId) {
		$.ajax({
			type: "GET",
			url: "<?= base_url("order/getItems") ?>",
			data: {
				orderId: orderId
			},
			dataType: "json",
			success: function(response) {
				if (response.success) {
					var table_body = document.getElementById('modal_table_body');

					populateTable(response.output, table_body, true);
					$("#itemListModal").modal("show");

				}
			},
			error: function(response) {
				console.log("failure", response)
			}
		})
	}
</script>
<?= $this->endSection() ?>