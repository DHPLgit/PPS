<?= $this->extend("layouts/app") ?>
<?= $this->section("body") ?>
<section>
	<div class="container">
		<div id="taskinitialize" action="<?= base_url('') ?>" method="post">
			<div class="orderid">
				<label for="countryInput" class="form-label">Order ID:</label></br>
				<input type="hidden" id="orderListId">
				<input type="hidden" id="ordItemId">
				<input type="text" id="orderidsearch" autocomplete="off" class="form-control" placeholder="Search Order ID"
					class="taskini-input">
				<button id="clear" type="button" onclick="clearInput()" class="d-none">x</button>
				<p style="color:red" class="error" id="order_list_id_error" type="hidden"></p>
				<div>
					<ul id="autocompleteList" class="d-none">
					</ul>
				</div>
				<div id="item_id_div" style="display: none;">
				<label>Item ID:</label></br>
					<select id="item_id_drpdwn"  class="taskini-input"></select>
				</div>
				<div class="row mt-5" id="output-data">
					<h4>Output</h4>
					<div class="col-md-6">
						<div class="orderid">
							<label>Color:</label></br>
							<input type="search" class="taskini-input form-control" id="colour" readonly />
						</div>
						<div class="orderid">
							<label>Texture:</label></br>
							<input type="search" class="taskini-input form-control" id="texture" readonly />
						</div>
					</div>
					<div class="col-md-6">
						<div class="orderid">
							<label>Length:</label></br>
							<input type="search" class="taskini-input form-control" id="length" readonly />
						</div>
						<div class="orderid">
							<label>Quantity(gms):</label></br>
							<input type="search" class="taskini-input form-control" id="quantity" readonly />
						</div>
					</div>
				</div>
				<form id="stock-search" action="<?= base_url('stock/search') ?>" class="mt-5">
					<div class="row">
						<h4>Input</h4>
						<div class="col-md-6">
							<div class="orderid">
								<label for="dropdown">Color:</label>
								<select id="dropdown" class="taskini-input" name="colour">
									<option value="">Select an option...</option>
									<?php foreach ($drpdwnData->Colours as $colour) { ?>
										<option value="<?php echo $colour ?>">
											<?php echo $colour ?>
										</option>
									<?php } ?>
								</select>
							</div>
							<div class="orderid mt-4">
								<label for="dropdown">Length:</label>
								<select id="dropdown" class="taskini-input" name="length">
									<option value="">Select an option...</option>
									<?php foreach ($drpdwnData->Length as $length) { ?>
										<option value="<?php echo $length ?>">
											<?php echo $length ?>
										</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-md-6 input_search">
							<div class="orderid ">
								<label for="dropdown">Texture:</label>
								<select id="dropdown" class="taskini-input" name="texture">
									<option value="">Select an option...</option>
									<?php foreach ($drpdwnData->Textures as $texture) { ?>
										<option value="<?php echo $texture ?>">
											<?php echo $texture ?>
										</option>
									<?php } ?>
								</select>
								<div class="inpt-search-btn">
									<div class="mt-5">
										<label for=""></label>
										<button class="btn taskini-searchbtn" type="submit">Search</button>
									</div>
								</div>
							</div>
						</div>
						<p style="color:red" class="error" id="stock_error" type="hidden"></p>
					</div>
				</form>
				<p style="color:red" class="error" id="AddStock-div-error"></p>
				<div id="AddStock-div" class="d-none">
					<form id="AddStock-form">
						<div class="row mt-5">
							<div class="col-md-6">
								<div class="stockid">
									<div class="stockselect">
										<h4>Select Stock</h4>
										<select id="dropdown-stock" class="taskini-input" name="stock_id">
											<option value="">Select an option...</option>
										</select>
										<p style="color:red" class="error" id="stock_id_error" type="hidden"></p>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<label for="quantity">Quantity(gms):</label><br>
								<input type="number" id="quantity" name="quantity" placeholder="Please enter the Quantity "
									class="taskini-input form-control" step="any">
								<p style="color:red" class="error" id="quantity_error" type="hidden"></p>
								<div class="">
									<div class="mt-5">
										<label for=""></label>
										<button type="submit" class=" btn taskini-searchbtn">Add</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div id="preview"></div>
				<form action="<?= base_url("task/createTask") ?>" id="create-task-form" method="post">
					<input type="hidden" id="stock" name="stock">
					<div class="row mt-5">
						<div class="taskselect">
							<h4>Select Task</h4>
							<select id="dropdown-task" class="taskini-input">
								<?php foreach ($taskDetailList as $taskDetail) { ?>
									<option value="<?= $taskDetail["task_detail_id"] ?>"><?= $taskDetail["task_name"] ?></option><?php } ?>
							</select>
						</div>
					</div>
					<div class="button-row">
						<button class="btn taskini-btn" id="submit-create-task" type="button">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>

<?php echo script_tag('js/jquery.min.js'); ?>
<?php echo script_tag('js/functions/Script.js'); ?>
<script>
	$("#stock-search").submit(function(event) {
		event.preventDefault();
		searchStocks($(this))
	});

	$("#orderidsearch").keyup(function() {
		$("#order_list_id_error").text("");
		var query = $(this).val().trim();
		var url = "<?= base_url('order/search') ?>";

		if (query.length >= 1) {
			searchOrder(url, query);
		} else {
			$("#order_list_id_error").text();
		}
	});

	$("#AddStock-form").submit(function(event) {
		event.preventDefault();
		console.log($('#AddStock-form').serializeArray());
		saveStockInput($(this))
	});
	$("#submit-create-task").on("click", function() {
		var url = "<?= base_url("task/createTask") ?>";
		createTask(url);
	})


	function searchOrder(url, query) {
		console.log(query)
		var ul = document.getElementById("autocompleteList");
		if (query != '') {
			$.ajax({
				url: url,
				method: 'post',
				dataType: 'json',
				data: {
					like: query
				},
				success: function(response) {
					console.log(response);
					if (response.success) {
						if (response.output.length > 0) {

							let orders = response.output;
							$('#autocompleteList').html('');
							var input = document.getElementById("orderidsearch");

							ul.classList.remove("d-none");
							var clear = document.getElementById("clear");
							$.each(orders, function(index, value) {
								var li = document.createElement("li");
								var ord_id = value.order_id; //+ "-" + value.item_id;
								li.textContent = ord_id
								li.addEventListener("click", function() {


									input.value = ord_id;
									input.readOnly = true;
									ul.classList.add("d-none");
									clear.classList.remove("d-none");

									getItems(ord_id);

								});
								ul.appendChild(li);

							});
						} else {
							$('#autocompleteList').html('');
							$('#item_id_div').hide();

						}

					} else {
						$('#autocompleteList').html('');
						$('#item_id_div').hide();

					}
				}
			});
		} else {
			ul.classList.add("d-none");
		}
	}

	function getItems(orderId) {
		$.ajax({
			url: "<?= base_url('order/getItems') ?>",
			method: 'get',
			dataType: 'json',
			data: {
				orderId: orderId
			},
			success: function(response) {
				if (response.success) {
					let orderItems = response.output;
					console.log(orderItems);
					$('#item_id_drpdwn').html('');

					var selectElement = $('#item_id_drpdwn'); // Your <select> element

					//var option="";
					// Loop through the response and append options
					orderItems.forEach(function(item) {

						var option = $('<option>', {
							value: item.order_list_id,
							text: item.order_id + "-" + item.item_id
						});
						selectElement.append(option);
					});

					selectElement.on('change', function() {
						var selectedValue = $(this).val(); // Get the selected value
						var selectedText = $(this).find("option:selected").text(); // Get the selected text
						$("#orderListId").val(selectedValue);
						$("#ordItemId").val(selectedText);

						var selectedItem = orderItems.find(function(item) {
							return item.order_list_id == selectedValue;
						});
						if (selectedItem) {
							populateOutputvalues(selectedItem);
						};
					});
					selectElement.trigger('change');

					$('#item_id_div').show();
				} else {
					$('#autocompleteList').html('');
				}
			}
		})
	}

	function populateOutputvalues(selectedItem) {
		var outputDiv = document.getElementById("output-data");

		var inputs = outputDiv.querySelectorAll("input");
		console.log("inputs", inputs);
		inputs.forEach(input => {
			console.log("input", input);
			var fieldName = input.id;
			input.value = selectedItem[fieldName];
		})
	}

	function clearInput() {
		var input = document.getElementById("orderidsearch");
		var ul = document.getElementById("autocompleteList");
		var selectDrpDwn = document.getElementById("item_id_drpdwn");
		var clear = document.getElementById("clear");
		$("#orderListId").val("");
		$("#ordItemId").val("");
		ul.innerHTML = "";
		$("#item_id_div").hide();
		if (input.value.length > 0) {
			input.value = "";
			if (input.value == "") {
				clear.classList.add("d-none");
				input.readOnly = false;
			}
		}
		var outputDiv = document.getElementById("output-data");

		var inputs = outputDiv.querySelectorAll("input");
		inputs.forEach(input => {
			var fieldName = input.id;
			input.value = "";
		})
	}
</script>
<?= $this->endSection() ?>