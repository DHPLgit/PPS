<?= $this->extend("layouts/app") ?>
<?= $this->section("body") ?>
<?php echo script_tag('js/jquery.min.js'); ?>
<?php echo script_tag('js/functions/Script.js'); ?>
<style>
	.expanded {
		background-color: #eaecee;
	}
</style>
<section class="home">
	<div class="container">
		<?php if (session()->getFlashdata('response') !== NULL): ?>
			<p style="color:green; font-size:18px;">
				<?php echo session()->getFlashdata('response'); ?>
			</p>
		<?php endif; ?>
		<div class="title-head">
			<div class="search-title">
				<input type="text" id="query" name="query" placeholder="Search order & Item id">
				<button type="submit" id="search">Search</button>
			</div>
			<div class="text-center">
				<a class="crt-sur" href="<?php echo site_url('/order/createOrder'); ?>">Create Order</a>
			</div>
		</div>
		<div class="order-list-table">
			<table id="order_table" class="table mt-6 table-striped table-bordered">
				<thead>
					<tr class="sur-lis-bd">
						<th scope="col">S.No</th>
						<th scope="col" style="display:none;">Id</th>
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
				<tbody id="table_body">
					<?php
					$count = 0;
					$groupedOrders = [];
					foreach ($orderList as $order) {
						$groupedOrders[$order['order_id']][] = $order;
					}
					foreach ($groupedOrders as $orderId => $orders) {
						$isFirst = true;
						foreach ($orders as $order) {
							$count++; ?>
							<tr class="order-row" data-order-id="<?= $order['order_id'] ?>" <?= !$isFirst ? 'style="display:none;"' : '' ?>>
								<td scope="row">
									<?= $count ?>
								</td>
								<td style="display:none;">
									<?= stripslashes($order['order_list_id']) ?>
								</td>
								<td>
									<?= stripslashes($order['order_id']) ?>
									<br />
									<div id="item-button">
										<button type="button" class="addItem"
											onclick="addItem('<?= $order['order_id'] ?>')">AddItem +</button><br /><br />
										<?php if ($isFirst && count($orders) > 1): ?>
											<button type="button" class="btn expand"
												data-order-id="<?= $order['order_id'] ?>">+</button>
										<?php endif; ?>
									</div>
								</td>
								<td>
									<?= stripslashes($order['item_id']) ?>
								</td>
								<td>
									<?= stripslashes($order['customer_id']) ?>
								</td>
								<td>
									<?php
									if ($order['order_date'] === '0000-00-00 00:00:00' || $order['order_date'] === null) {
										echo '0000-00-00';
									} else {
										echo date('Y-m-d', strtotime($order['order_date']));
									}
									?>
								</td>
								<td>
									<?= $order['type'] . " " . $order['colour'] . " " . $order['length'] . " " . $order['texture'] . " " . $order['ext_size'] ?>
								</td>
								<td>
									<?= stripslashes($order['bundle_count']) ?>
								</td>
								<td>
									<?= stripslashes($order['quantity']) ?>
								</td>
								<td>
									<?= stripslashes($order['status']) ?>
								</td>
								<td>
									<?= stripslashes($order['due_date']) ?>
								</td>
								<td class="action">
									<button type="button" <?= $order['status'] != "Not started" ? 'style="pointer-events:none"' : '' ?> class="btn editOrder">
										<img src="<?= base_url() ?>images/icons/Create.png" class="img-centered img-fluid">
									</button>
									<button type="button" <?= $order['status'] != "Not started" ? 'style="pointer-events:none"' : '' ?> class="btn deleteOrder">
										<img src="<?= base_url() ?>images/icons/remove.png" class="img-centered img-fluid">
									</button>
								</td>
							</tr>
							<?php
							$isFirst = false;
						}
					} ?>
				</tbody>
			</table>
			<div class="text-center">
				<button id="show-more" class="btn" style="display:none;">Show More</button>
			</div>
			<div class="text-center" id="no_records" style="display:none;">
				<p class="fs-3"><span class="text-danger">Oops!</span> No records found.</p>
			</div>
		</div>
		<div class="container">
			<div class="modal fade" id="orderDeleteModal">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header" style="padding:15px 50px;">
							<h4>Delete order</h4>
							<button type="button" class="close top-close" data-bs-dismiss="modal">&times;</button>
						</div>
						<form action="<?= base_url('order/deleteOrder') ?>" class="form" id="DeleteSegmentForm"
							name="DeleteSegment" method="post">
							<div class="modal-body ctr-segment-body" style="padding:20px;">
								<p>Are you sure you want to delete the order?</p>
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
	</div>
</section>

<script>
	$(document).ready(function () {
		$(document).on('click', '.expand', function () {
			var orderId = $(this).data('order-id');
			var button = $(this);
			var action = button.text() === '+' ? 'show' : 'hide';

			$('tr[data-order-id="' + orderId + '"]').not(':first').each(function () {
				if (action === 'show') {
					$(this).show().addClass('expanded');
				} else {
					$(this).hide().removeClass('expanded');
				}
			});
			button.text(action === 'show' ? '-' : '+');
		});

		$("#search").on("click", function () {
			var query = $("#query").val();
			if (query.trim() === "") {
				$.ajax({
					url: "<?= base_url('order/getAllOrders') ?>",
					type: "get",
					dataType: "json",
					success: function (response) {
						var table_body = $('#table_body');
						table_body.empty();
						$('#show-more').hide();
						$('#no_records').hide();

						if (response.success) {
							var data = response.output;
							var rows = [];
							var index = 0;

							if (data.length > 0) {
								data.forEach(function (item, idx) {
									index++;
									var orderDate = item.order_date.split(" ")[0];

									var row = '<tr class="order-row" data-order-id="' + item.order_id + '">';
									row += '<td>' + index + '</td>';
									row += '<td style="display:none;">' + item.order_list_id + '</td>';
									row += '<td>' + item.order_id;
									row += '<br /><div id="item-button"><button type="button" class="addItem" onclick="addItem(\'' + item.order_id + '\')">AddItem +</button></div></td>';
									row += '<td>' + item.item_id + '</td>';
									row += '<td>' + item.customer_id + '</td>';
									row += '<td>' + orderDate + '</td>';
									row += '<td>' + item.item_description + '</td>';
									row += '<td>' + item.bundle_count + '</td>';
									row += '<td>' + item.quantity + '</td>';
									row += '<td>' + item.status + '</td>';
									row += '<td>' + item.due_date + '</td>';
									row += '<td class="action"><button type="button" class="btn editOrder"><img src="<?= base_url() ?>images/icons/Create.png" class="img-centered img-fluid"></button><button type="button" class="btn deleteOrder"><img src="<?= base_url() ?>images/icons/remove.png" class="img-centered img-fluid"></button></td>';
									row += '</tr>';
									rows.push(row);
								});
								table_body.append(rows.join(''));
								table_body.find('tr').show();
							} else {
								$('#no_records').show();
							}
						}
					},
					error: function (response) {
						console.error('Error fetching all orders:', response);
					}
				});
			} else {
				$.ajax({
					url: "<?= base_url('order/filter') ?>",
					type: "get",
					data: { query: query },
					dataType: "json",
					success: function (response) {
						var table_body = $('#table_body');
						table_body.empty();
						$('#show-more').hide();
						$('#no_records').hide();

						if (response.success) {
							var data = response.output;
							var rows = [];
							var index = 0;

							if (data.length > 0) {
								data.forEach(function (item, idx) {
									index++;
									var orderDate = item.order_date.split(" ")[0];

									var row = '<tr class="order-row" data-order-id="' + item.order_id + '" style="display:none;">';
									row += '<td>' + index + '</td>';
									row += '<td style="display:none;">' + item.order_list_id + '</td>';
									row += '<td>' + item.order_id;
									row += '<br /><div id="item-button"><button type="button" class="addItem" onclick="addItem(\'' + item.order_id + '\')">AddItem +</button></div></td>';
									row += '<td>' + item.item_id + '</td>';
									row += '<td>' + item.customer_id + '</td>';
									row += '<td>' + orderDate + '</td>';
									row += '<td>' + item.item_description + '</td>';
									row += '<td>' + item.bundle_count + '</td>';
									row += '<td>' + item.quantity + '</td>';
									row += '<td>' + item.status + '</td>';
									row += '<td>' + item.due_date + '</td>';
									row += '<td class="action"><button type="button" class="btn editOrder"><img src="<?= base_url() ?>images/icons/Create.png" class="img-centered img-fluid"></button><button type="button" class="btn deleteOrder"><img src="<?= base_url() ?>images/icons/remove.png" class="img-centered img-fluid"></button></td>';
									row += '</tr>';
									rows.push(row);
								});
								table_body.append(rows.join(''));
								table_body.find('tr:first').show();
								if (rows.length > 1) {
									$('#show-more').show();
								} else {
									$('#show-more').hide();
								}

								if (rows.length === 0) {
									$('#no_records').show();
								}
							} else {
								$('#no_records').show();
							}
						}
					},
					error: function (response) {
						console.error('Error fetching search results:', response);
					}
				});
			}
		});

		$('#show-more').on('click', function () {
			$('#table_body').find('tr').show();
			$(this).hide();
		});
	});
</script>
<?= $this->endSection() ?>