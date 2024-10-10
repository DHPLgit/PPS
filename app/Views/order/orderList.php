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
				<form method="get" action="<?= base_url("order/filter") ?>">
					<input type="text" id="query" value="<?= $search?>" name="query" placeholder="Search order & Item id">
					<button type="submit" id="search">Search</button>
				</form>
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
					<?php if (isset($orderList) && count($orderList) > 0) { ?>

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
									<td scope="row"><?= $count ?></td>
									<td style="display:none;"><?= stripslashes($order['order_list_id']) ?></td>
									<td>
										<?= stripslashes($order['order_id']) ?>
										<br />
										<div id="item-button">
											<button type="button" class="addItem"
												onclick="addItem('<?= $order['order_id'] ?>')">AddItem +</button>
										</div>
										<?php if ($isFirst && count($orders) > 1): ?>
											<div id="expand-button">
												<button type="button" class="btn expand-more"
													data-order-id="<?= $order['order_id'] ?>">+</button>
											</div>
										<?php endif; ?>
									</td>
									<td><?= stripslashes($order['item_id']) ?></td>
									<td><?= stripslashes($order['customer_id']) ?></td>
									<td>
										<?= ($order['order_date'] === '0000-00-00 00:00:00' || $order['order_date'] === null) ? '0000-00-00' : date('Y-m-d', strtotime($order['order_date'])) ?>
									</td>
									<td><?= $order['type'] . " " . $order['colour'] . " " . $order['length'] . " " . $order['texture'] . " " . $order['ext_size'] ?>
									</td>
									<td><?= stripslashes($order['bundle_count']) ?></td>
									<td><?= stripslashes($order['quantity']) ?></td>
									<td><?= stripslashes($order['status']) ?></td>
									<td><?= stripslashes($order['due_date']) ?></td>
									<td class="action">
										<button type="button" onclick="editOrder(this)" <?= $order['status'] != "Not started" ? 'style="pointer-events:none"' : '' ?> class="btn editOrder">
											<img src="<?= base_url() ?>images/icons/Create.png" class="img-centered img-fluid">
										</button>
										<button type="button" onclick="deleteOrder(this)" <?= $order['status'] != "Not started" ? 'style="pointer-events:none"' : '' ?> class="btn deleteOrder">
											<img src="<?= base_url() ?>images/icons/remove.png" class="img-centered img-fluid">
										</button>
									</td>
								</tr>
						<?php
								$isFirst = false;
							}
						}
						?>
					<?php } else { ?>
						<div class="text-center" id="no_records">
							<p class="fs-3"><span class="text-danger"> No records found.</p>
						</div>
					<?php } ?>
				</tbody>

			</table>

			<!-- Pagination Links -->
			<div id="pageLinks">
				<?php echo $pageLinks ?>
			</div>
			<!-- <div class="text-center">
				<button id="show-more" class="btn" style="display:none;">Show More</button>
			</div> -->
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
		var url = "<?= base_url('order/generateItemId/') ?>" + orderId;
		window.location.href = url;
	}
	$(document).ready(function() {

		$(document).on('click', '.expand-more', function() {
			var orderId = $(this).data('order-id');
			var button = $(this);
			var isExpanded = button.text() === '-';
			var rows = $('tr.order-row[data-order-id="' + orderId + '"]');

			rows.each(function(index) {
				if (index > 0) {
					$(this).toggle(!isExpanded).toggleClass('expanded', !isExpanded);
				}
			});

			button.text(isExpanded ? '+' : '-');
		});

		//$("#search").on("click", function() {
		//	var query = $("#query").val().trim();
		//	if (query === "") {
		//		location.reload();
		//	} else {

		//		searchOrder(query);
		//	}
		//});


		function searchOrder(query) {
			$.ajax({
				url: "<?= base_url('order/filter') ?>",
				type: "get",
				data: {
					query: query
				},
				dataType: "json",
				success: function(response) {
					var table_body = $('#table_body');
					table_body.empty();
					$('#no_records').hide();

					if (response.success && response.output.length > 0) {
						var data = response.output;
						var rows = [];
						var orderGroups = {};

						data.forEach(function(item) {
							if (!orderGroups[item.order_id]) {
								orderGroups[item.order_id] = [];
							}
							orderGroups[item.order_id].push(item);
						});

						Object.keys(orderGroups).forEach(function(orderId) {
							var items = orderGroups[orderId];
							var isExpanded = false;

							items.forEach(function(item, index) {
								var orderDate = item.order_date.split(" ")[0];
								var isHeader = index === 0;

								var row = '<tr class="order-row' + (isHeader ? ' order-header' : '') + '" data-order-id="' + orderId + '" style="' + (isHeader ? 'display: table-row;' : 'display: none;') + '">';
								row += '<td>' + (index + 1) + '</td>';
								row += '<td style="display:none;">' + item.order_list_id + '</td>';
								row += '<td>' + item.order_id;
								row += '<br /><div id="item-button"><button type="button" class="addItem" onclick="addItem(\'' + item.order_id + '\')">AddItem +</button></div>';

								if (items.length > 1 && isHeader) {
									row += '<div id="expand-button"><button type="button" class="btn expand-more" data-order-id="' + orderId + '">+</button></div>';
								}

								row += '</td>';
								row += '<td>' + item.item_id + '</td>';
								row += '<td>' + item.customer_id + '</td>';
								row += '<td>' + orderDate + '</td>';
								row += '<td>' + item.item_description + '</td>';
								row += '<td>' + item.bundle_count + '</td>';
								row += '<td>' + item.quantity + '</td>';
								row += '<td>' + item.status + '</td>';
								row += '<td>' + item.due_date + '</td>';
								row += '<td class="action"><button type="button" onclick="editOrder(this)" class="btn editOrder"><img src="<?= base_url() ?>images/icons/Create.png" class="img-centered img-fluid"></button><button type="button" onclick="deleteOrder(this)" class="btn deleteOrder"><img src="<?= base_url() ?>images/icons/remove.png" class="img-centered img-fluid"></button></td>';
								row += '</tr>';

								rows.push(row);
							});
						});

						table_body.append(rows.join(''));

						table_body.find('tr.order-header').each(function() {
							var orderId = $(this).data('order-id');
							if (orderId) {
								$(this).show();
							}
						});
						console.log("123", response.pageLinks);
						$("#pageLinks").html("");
						$("#pageLinks").html(response.pageLinks)
						$(".pagination").on("click", function(event) {
							console.log("here");
							event.preventDefault();
							console.log("this", $(this).attr("href"));
						})
					} else {
						$('#no_records').show();
					}
				},
				error: function(response) {
					console.error('Error fetching search results:', response);
				}
			});
		}

	});
</script>
<?= $this->endSection() ?>