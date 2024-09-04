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
			<div class="text-center"><a class="crt-sur" href="<?php echo site_url('/order/createOrder'); ?>">Create
					Order</a></div>
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
			<div class="text-center" id="show_more_container" style="display:none;">
				<button id="show_more" class="btn">Show more item</button>
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
			// Toggle the rows and manage the 'expanded' class
			$('tr[data-order-id="' + orderId + '"]').not(':first').each(function () {
				if (action === 'show') {
					$(this).show().addClass('expanded');
				} else {
					$(this).hide().removeClass('expanded');
				}
			});
			button.text(action === 'show' ? '-' : '+');
		});

		// Search 
		$("#search").on("click", function () {
			var query = $("#query").val();
			$.ajax({
				url: "<?= base_url('order/filter') ?>",
				type: "get",
				data: { query: query },
				dataType: "json",
				success: function (response) {
					var table_body = $('#table_body');
					table_body.empty();
					$('#show_more_container').hide();
					$('#no_records').hide();

					if (response.success) {
						var data = response.output;
						if (data.length > 0) {
							var index = 0;
							data.forEach(function (item, idx) {
								index++;
								var row = '<tr class="order-row" data-order-id="' + item.order_id + '" ' + (idx > 0 ? 'style="display:none;"' : '') + '>';
								row += '<td>' + index + '</td>';
								row += '<td style="display:none;">' + item.order_list_id + '</td>';
								row += '<td>' + item.order_id;
								if (idx === 0 && data.length > 1) {
									row += '<button type="button" class="btn btn-primary expand" data-order-id="' + item.order_id + '">Expand</button>';
								}
								row += '<br /><div id="item-button"><button type="button" class="addItem" onclick="addItem(' + item.order_id + ')">AddItem +</button></div></td>';
								row += '<td>' + item.item_id + '</td>';
								row += '<td>' + item.customer_id + '</td>';
								row += '<td>' + item.order_date + '</td>';
								row += '<td>' + item.type + ' ' + item.colour + ' ' + item.length + ' ' + item.texture + ' ' + item.ext_size + '</td>';
								row += '<td>' + item.bundle_count + '</td>';
								row += '<td>' + item.quantity + '</td>';
								row += '<td>' + item.status + '</td>';
								row += '<td>' + item.due_date + '</td>';
								row += '<td class="action"><button type="button" class="btn editOrder"><img src="<?= base_url() ?>images/icons/Create.png" class="img-centered img-fluid"></button><button type="button" class="btn deleteOrder"><img src="<?= base_url() ?>images/icons/remove.png" class="img-centered img-fluid"></button></td>';
								row += '</tr>';

								table_body.append(row);

								if (idx === data.length - 1 && data.length > 1) {
									table_body.find('tr[data-order-id="' + item.order_id + '"]').first().find('td:eq(2)').append('<button type="button" class="btn btn-primary expand" data-order-id="' + item.order_id + '">Expand</button>');
								}
							});
							$('#show_more_container').show();
						} else {
							$('#no_records').show();
						}
					}

					$('#order_table').find('.expand').hide();
				},
				error: function (response) {
					console.error('Error fetching search results:', response);
				}
			});
		});

		$('#show_more').on('click', function () {
			$('#order_table').find('tr:hidden').show();
			$(this).hide();
			$('#order_table').find('.expand').hide();
		});

		$(document).on('click', '.editOrder', function () {
			var orderId = $(this).closest('tr').children().eq(1).text().trim();
			window.location.href = "<?= base_url('order/editOrder/') ?>" + orderId;
		});

		$(document).on('click', '.deleteOrder', function () {
			var orderId = $(this).closest('tr').children().eq(1).text().trim();
			$('#orderListId').val(orderId);
			$("#orderDeleteModal").modal('show');
		});
	});

	function addItem(orderId) {
		window.location.href = "<?= base_url('order/generateItemId/') ?>" + orderId;
	}
</script>
<?= $this->endSection() ?>