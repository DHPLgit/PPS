<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php echo script_tag('js/jquery.min.js'); ?>
<?php echo script_tag('js/functions/Script.js'); ?>
<section class="create-order">
	<div class="container">
		<div class="d-flex justify-content-between">
			<h2 id="Action-type">Order and Item details</h2>
			<div class="text-center"><a class="crt-sur float-end" href="<?php echo site_url('/order/orderList'); ?>">Back</a>
			</div>
			<div class="text-center">
				<button class="crt-sur float-end" type="button" id="AddItemButton" onclick="showAddItemForm()">Add Item</button>
			</div>
		</div>
		<div class="row">
			<div class="">
				<div class="form-list">
					<label class="login-label">Order id</label>
					<div class="mb-3">
						<input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Order id" id="order_id" name="order_id" />

						<p style="color:red" class="error" id="order_id_error" type="hidden"></p>
						<?php if (isset($validation)) : ?>
							<div style="color:red">
								<?= $validation->showError('order_id') ?>
							</div>
						<?php endif; ?>
					</div>
				</div>


			</div>

			<div class="order-list-table">
				<table id="order_table" class="table mt-6 table-striped table-bordered">
					<thead>
						<tr class="sur-lis-bd">
							<th scope="col" style="display:none;">Id</th>

							<th scope="col">Item id</th>
							<th scope="col">Order Date</th>
							<th scope="col">Type</th>
							<th scope="col">Colour</th>
							<th scope="col">Length</th>
							<th scope="col">Texture</th>
							<th scope="col">Extension size</th>
							<!-- <th scope="col">Unit</th> -->
							<th scope="col">Quantity</th>
							<th scope="col">Bundle count</th>
							<th scope="col">Status</th>
							<th scope="col">Completion Status</th>
							<th scope="col">Action</th>


						</tr>
					</thead>

					<tbody id="table_body">
						<?php if (isset($orderItems) && count($orderItems) > 0) { ?>

							<?php
							foreach ($orderItems as $orderItem) { ?>
								<tr class="order-row" id="<?= $orderItem['order_id'] ?>">
									<td style="display: none;"><?= stripslashes($orderItem['order_list_id']) ?></td>
									<td><?= stripslashes($orderItem['item_id']) ?></td>
									<td>
										<?= ($orderItem['order_date'] === '0000-00-00 00:00:00' || $orderItem['order_date'] === null) ? '0000-00-00' : date('Y-m-d', strtotime($orderItem['order_date'])) ?>
									</td>
									<td><?= $orderItem['type'] ?></td>
									<td><?= $orderItem['colour'] ?></td>
									<td><?= $orderItem['length'] ?></td>
									<td><?= $orderItem['texture'] ?></td>
									<td><?= $orderItem['ext_size'] ?></td>
									</td>
									<td><?= stripslashes($orderItem['bundle_count']) ?></td>
									<td><?= stripslashes($orderItem['quantity']) ?></td>
									<td><?= stripslashes($orderItem['status']) ?></td>
									<td><?= stripslashes($orderItem['completion_percentage']) ?></td>

									<!-- <td><?= stripslashes($orderItem['due_date']) ?></td> -->
									<td class="action">
										<button type="button" onclick="deleteItemModal(this)" <?= $orderItem['status'] != "Not started" ? 'style="pointer-events:none"' : '' ?> class="btn deleteOrder">
											<img src="<?= base_url() ?>images/icons/remove.png" class="img-centered img-fluid">
										</button>
									</td>
								</tr>
							<?php
							}
							?>
						<?php } else { ?>
							<div class="text-center" id="no_records">
								<p class="fs-3"><span class="text-danger"> No records found.</p>
							</div>
						<?php } ?>
					</tbody>

				</table>

			</div>
			<p id="notification" style="color:green; font-size:18px;"></p>
			<div class="col-md-12" style="text-align:center; display: none;" id="item_form_div">
				<h4>Add Item</h4>

				<form id="orderForm" action="<?= base_url('order/createItem') ?>" method="post">

					<input name="order_id" type="hidden" value="<?php echo $order['order_id'] ?>">
					<div id="item-form">
						<div class="form-list">
							<label class="login-label">Item number</label>
							<div class="mb-3">

								<input type="text" readonly class="form-control" aria-describedby="emailHelp" placeholder="Item id" id="item_id" name="item_id" />

							</div>
						</div>
						<div class="form-list">

							<div class="form-list">
								<label class="login-label">Order date</label>
								<div class="mb-3">
									<input type="date" class="form-control" aria-describedby="emailHelp" placeholder="Order date" id="order_date" name="order_date" />
									<p style="color:red" class="error" id="order_date_error" type="hidden"></p>
									<?php if (isset($validation)) : ?>
										<div style="color:red">
											<?= $validation->showError('Order_date') ?>
										</div>
									<?php endif; ?>
								</div>

							</div>

							<div class="form-list">
								<label class="login-label">Type</label>
								<div class="mb-3">
									<select placeholder="Type" id="type" name="type">
										<?php foreach ($json->Types as $type) { ?>
											<option value="<?php echo $type ?>">
												<?php echo $type ?>
											</option>
										<?php } ?>
									</select>
									<p style="color:red" class="error" id="type_error" type="hidden"></p>
									<?php if (isset($validation)) : ?>
										<div style="color:red">
											<?= $validation->showError('Type') ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
							<div class="form-list">
								<label class="login-label">Colour</label>
								<div class="mb-3">
									<select placeholder="Colour" id="colour" name="colour">
										<?php foreach ($json->Colours as $colour) { ?>
											<option value="<?php echo $colour ?>">
												<?php echo $colour ?>
											</option>
										<?php } ?>
									</select>
									<input type="text" name="other_colour" id="other_colour" style="display: none;">
									<p style="color:red" class="error" id="colour_error" type="hidden"></p>
									<?php if (isset($validation)) : ?>
										<div style="color:red">
											<?= $validation->showError('Colour') ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
							<div class="form-list">
								<label class="login-label">Length</label>
								<div class="mb-3">
									<select placeholder="Length" id="length" name="length">
										<?php foreach ($json->Length as $length) { ?>
											<option value="<?php echo $length ?>">
												<?php echo $length ?>
											</option>
										<?php } ?>
									</select>
									<p style="color:red" class="error" id="length_error" type="hidden"></p>
									<?php if (isset($validation)) : ?>
										<div style="color:red">
											<?= $validation->showError('Length') ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
							<div class="form-list">
								<label class="login-label">Texture</label>
								<div class="mb-3">
									<select placeholder="Texture" id="texture" name="texture">
										<?php foreach ($json->Textures as $texture) { ?>
											<option value="<?php echo $texture ?>">
												<?php echo $texture ?>
											</option>
										<?php } ?>
									</select>
									<p style="color:red" class="error" id="texture_error" type="hidden"></p>
									<?php if (isset($validation)) : ?>
										<div style="color:red">
											<?= $validation->showError('Texture') ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
							<div class="form-list">
								<label class="login-label">Extension size</label>
								<div class="mb-3">
									<select placeholder="Extension size" id="ext_size" name="ext_size">
										<?php foreach ($json->Ext_sizes as $ext_size) { ?>
											<option value="<?php echo $ext_size ?>">
												<?php echo $ext_size ?>
											</option>
										<?php } ?>
									</select>
									<p style="color:red" class="error" id="ext_size_error" type="hidden"></p>
									<?php if (isset($validation)) : ?>
										<div style="color:red">
											<?= $validation->showError('Ext_size') ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
							<div class="form-list">
								<label class="login-label">Unit: </label>
								<input type="radio" id="is_bundle" name="unit" value="1"> Bundle&nbsp;
								<input type="radio" id="is_bundle1" name="unit" value="0"> grams
								<p style="color:red" class="error" id="unit_error" type="hidden"></p>
							</div>
							<div class="form-list" id="bundle-count-div" style="display: none;">
								<label class="login-label">Quantity(Bundles)</label>
								<div class="mb-3">
									<input type="text" class="form-control" placeholder="Bundle count" id="bundle_count" name="bundle_count">
									<p style="color:red" class="error" id="bundle_count_error" type="hidden"></p>
									<?php if (isset($validation)) : ?>
										<div style="color:red">
											<?= $validation->showError('count') ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
							<div class="form-list" id="quantity-div" style="display: none;">
								<label class="login-label">Weight(gms)</label>
								<div class="mb-3">
									<input type="text" class="form-control" placeholder="Quantity" id="quantity" name="quantity">
									<p style="color:red" class="error" id="quantity_error" type="hidden"></p>
									<?php if (isset($validation)) : ?>
										<div style="color:red">
											<?= $validation->showError('Quantity') ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
							<div class="form-list">
								<label class="login-label">Due date</label>
								<div class="mb-3">
									<input type="date" class="form-control" placeholder="Due_date" id="due_date" name="due_date">
									<p style="color:red" class="error" id="due_date_error" type="hidden"></p>
									<?php if (isset($validation)) : ?>
										<div style="color:red">
											<?= $validation->showError('Due_date') ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
							<button id="submitBtn" type="submit" class="order-submit">submit</button>
						</div>
					</div>
				</form>
			</div>
			<div class="container">
				<div class="modal fade" id="itemDeleteModal">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header" style="padding:15px 50px;">
								<h4>Delete item</h4>
								<button type="button" class="close top-close" data-bs-dismiss="modal">&times;</button>
							</div>
							<form action="<?= base_url('order/deleteItem') ?>" class="form" id="itemDeleteForm" method="post">
								<div class="modal-body ctr-segment-body" style="padding:20px;">
									<p>Are you sure you want to delete the item?</p>
									<div class="form-group">
										<input type="hidden" class="form-control" id="orderListId" name="orderListId">
									</div>
									<br />
									<div class="d-grid">
										<button type="submit" class="btn btn-danger confirm pull-right"><span
												class="fa fa-trash"></span> Confirm</button>
										<button type="button" class="btn btn-outline-secondary Cancel pull-left close"
											data-bs-dismiss="modal"><span class="fa fa-remove"></span> Cancel</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
</section>
<script>
	$(document).ready(function() {

		$("#orderForm").submit(function(event) {
			event.preventDefault();
			addItem($(this), "<?= base_url() ?>");
		});


		//map the order id
		var order_id = <?php if (isset($order)) {
							echo json_encode($order["order_id"]);
						} else {
							echo json_encode([]);
						} ?>;
		mapOrderId(order_id);


		$("#is_bundle").on('change', function() {
			$("#bundle_count").val("");
			$("#bundle-count-div").show();
			$("#quantity-div").hide();
			$("bundle_count_error").hide();
			$("quantity_error").hide();
			var qty_element = document.getElementById("quantity_error")
			qty_element.style.display = "none";
			var count_element = document.getElementById("bundle_count_error")
			count_element.style.display = "none";
		})
		$("#is_bundle1").on('change', function() {
			$("#bundle-count-div").hide();

			$("#quantity-div").show();
			$('#quantity').prop('readonly', false);
			$('#quantity').val("");
			var element = document.getElementById("quantity_error")
			element.style.display = "none";
		})
		$("#bundle_count").keyup(function() {
			var length = $("#length").val();
			var count = $(this).val();
			var type = $("#type").val();
			if (count.length > 0) {
				var url = "<?= base_url('order/getWeight') ?>"
				calculateWeight(url, length, count, type);
			}
		});

		$("#length").on("change", function() {

			$("#is_bundle").prop("checked", false);
			$("#is_bundle1").prop("checked", false);
			$("#bundle-count-div").hide();
			$("#quantity-div").hide();
			$("#bundle_count").val("");
		})
		$("#type").on("change", function() {

			$("#is_bundle").prop("checked", false);
			$("#is_bundle1").prop("checked", false);
			$("#bundle-count-div").hide();
			$("#quantity-div").hide();
			$("#bundle_count").val("");
		})
		$("#colour").on("change", function() {

			var colour = $(this).val();
			if (colour == "Others") {
				$("#other_colour").val("");
				$("#other_colour").show();
			} else {
				$("#other_colour").hide();
			}
			console.log("colour", colour)
		})
	});

	function showAddItemForm() {
		$("#AddItemButton").prop('disabled', true);
		url = "<?= base_url('order/checkAndGenerateId') ?>";
		order_id = $("#order_id").val();
		checkAndGenerateItemId(url, order_id)
		$("#item_form_div").show();

		$('html, body').animate({
			scrollTop: $("#item_form_div").offset().top - 100
		});
	};

	function mapOrderId(order_id) {

		$("#order_id").prop("readonly", true);
		$("#order_id").val(order_id);


	}

	function checkAndGenerateItemId(url, order_id) {

		$.ajax({
			url: url,
			type: 'get',
			data: {
				isAddItem: 0,
				order_id: order_id
			},
			dataType: 'json',
			success: function(response) {
				console.log(response);
				if (response.success) {
					console.log("successentry");

					console.log("response.output", response.output);
					$("#order_id").prop("readonly", true);
					$("#item_form_div").show();
					$("#item_id").val(response.output.item_id);

					$("#ord-item-button").hide();
				} else {
					$("#order_id_error").text(response.error['order_id'])
					console.log(response.error);
					console.log("failure");

				}
			},
			error: function(response) {
				console.log(response);
			}

		});

	}

	function deleteItemModal(element) {
		//getting data of selected row using id.
		$tr = $(element).closest('tr');
		var data = $tr.children().map(function() {
			return $(this).text();
		}).get();
		console.log(data[0].trim());
		var orderListId = data[0].trim();
		$('#orderListId').val(orderListId);
		$("#itemDeleteModal").modal('show');

	}

	$("#itemDeleteForm").submit(function(event) {
		event.preventDefault();
		var form = $("#itemDeleteForm");
		var url = form.attr("action");
		$.ajax({
			type: "POST",
			url: url,
			data: form.serialize(),
			dataType: "json",

			success: function(response) {
				if (response.success) {
					console.log("success", response);
					$("#itemDeleteModal").modal('hide');
					location.reload();
				} else {
					console.log("failure", response);
				}
			},
			error: function(response) {
				console.log("failure", response);
			}

		})
	})

	function addItem(form, base_url) {
		$('#loader').show();
		$("#submitBtn").prop("disabled", true);

		$.ajax({
			url: form.attr("action"),
			type: 'post',
			dataType: 'json',
			data: form.serialize(),
			success: function(response) {
				$('#loader').hide();
				$("#submitBtn").prop("disabled", false);

				if (response.success) {

					var item = response.output.item;
					var row_item = '<tr class="order-row" data-order-id="' + item.order_list_id + '">';
					var flag = true;
					for (const key in item) {
						if (flag) {
							row_item += '<td style="display: none;">' + item[key] + '</td>'
							flag = false;
						} else if (item.hasOwnProperty(key)) {
							console.log(key, item[key]);
							row_item += '<td>' + item[key] + '</td>'
						}

					};
					var style = item['status'] != "Not started" ? 'style="pointer-events:none"' : '';
					row_item += '<td><button type="button" onclick="deleteItemModal(this)"' + style + 'class="btn deleteOrder"><img src="<?= base_url() ?>images/icons/remove.png" class="img-centered img-fluid"></button></td>';

					$("#table_body").append(row_item);
					$('html, body').animate({
						scrollTop: $("#item_form_div").offset().top - 200
					});
					$("#orderForm").trigger("reset");
					$("#notification").text("Item added successfully");
				} else {
					$("#submitBtn").prop("disabled", false);

					console.log(response.error);
					console.log("failure");
					const idArray = ['order_id', 'customer_id', 'order_date', 'type', 'colour', 'length', 'texture', 'ext_size', 'unit', 'bundle_count', 'quantity', 'due_date'];
					const errorArray = ["order_id_error", "customer_id_error", "order_date_error", "type_error", "colour_error", "length_error", "texture_error", "ext_size_error", "unit_error", "bundle_count_error", "quantity_error", "due_date_error"];

					errorDisplay(errorArray, idArray, response.error);

				}
			},
			error: function(response) {
				console.log(response);
			}

		});

	}
</script>
<?= $this->endSection() ?>