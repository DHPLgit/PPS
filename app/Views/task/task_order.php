<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php echo script_tag('js/jquery.min.js'); ?>
<?php echo script_tag('js/functions/Script.js'); ?>
<section class="home">
	<div class="container">
		<?php if (session()->getFlashdata('response') !== NULL): ?>
			<p style="color:green; font-size:18px;">
				<?php echo session()->getFlashdata('response'); ?>
			</p>
		<?php endif; ?>
		<h3><?= $taskDetail[0]["task_name"] ?></h3>
		<div class="text-center"><a class="task-bck-btn" href="<?php echo site_url('/task/list'); ?>">Back</a>
		</div>
		<div class="text-center"><a class="crt-sur float-end" href="<?php echo site_url('/task/createTask'); ?>">Create
				Task</a>
		</div>

		<div class="mt-5">
			<?php if (!empty($taskList)) { ?>
				<table class="table mt-6 table-striped table-bordered">
					<thead>
						<tr class="sur-lis-bd">
							<th scope="col"> Id </th>
							<th scope="col">Order-Item id</th>
							<th scope="col">Supervisor</th>
							<th scope="col">Employee</th>
							<th scope="col">Start time</th>
							<th scope="col">End time</th>
							<th scope="col">Time taken</th>
							<th scope="col">Status</th>
							<th scope="col" colspan="3">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $count = 0;
						foreach ($taskList as $task) {
							$count++; ?>
							<tr id="task-det-row">

								<td>
									<?php echo stripslashes($task['task_id']); ?>
								</td>
								<td>
									<?php echo stripslashes($task['order_id'] ."-".$task['item_id']); ?>
								</td>
								<td>
									<?php echo stripslashes($task['supervisor_name']); ?>
								</td>
								<td>
									<?php echo stripslashes($task['employee_name']); ?>
								</td>
								<td>
									<?php echo stripslashes($task['start_time']); ?>
								</td>
								<td>
									<?php echo stripslashes($task['end_time']); ?>
								</td>
								<td>
									<?php echo stripslashes($task['time_taken']); ?>

								</td>
								<td>
									<?php echo stripslashes($task['status']); ?>

								</td>
								<td class="actions-order-list">
									<a class="view-btn" <?php if ($task['is_qa'] == 0) { ?>
											href="<?= base_url("task/mapEmployee/" . $task['task_id']) ?>" <?php }  elseif ($task['is_qa'] == 1) {if($task['status'] == "Completed"){  echo "style='pointer-events:none;background:#e63b3b;padding:10px 15px;border-radius:5px;border: 0px solid #000;filter: invert(100%) sepia(100%) saturate(0%) hue-rotate(288deg) brightness(102%) contrast(102%);'";} else { ?> href="<?= base_url("task/qualityCheck/" . $task['task_id']) ?>"
										<?php } }?>>
										Check
									</a>
									<button onclick='nextTask(<?= json_encode($task["next_task"]) ?>)' <?php if ($task['status'] == "Completed") {
										  echo "style='pointer-events:default;border:1px;padding:10px 15px;border-radius:5px;font-size:15px'";
									  } ?> style="pointer-events:none;background:#e63b3b;padding:10px 15px;border-radius:5px;font-size:15px;border: 0px solid #000;filter: invert(100%) sepia(100%) saturate(0%) hue-rotate(288deg) brightness(102%) contrast(102%);"><span
											class="far fa-arrow-alt-circle-right"></span></button>
									<button class="view-btn" onclick="getPrevTaskList(<?= $task['task_id'] ?>)"
										href="<?= base_url("task/getPrevTaskList/" . $task['task_id']) ?>">History</button>

									<button <?php if ($task['status'] == "Not started") {
										echo "style=pointer-events:default;border: 1px solid #000;";
									} ?> type="button"
										class="btn deleteTask"
										style="pointer-events:none;background:#e63b3b;border: 0px solid #000;filter: invert(100%) sepia(100%) saturate(0%) hue-rotate(288deg) brightness(102%) contrast(102%);"><img
											src="<?php echo base_url(); ?>images/icons/trash3-fill.svg"
											class="img-centered img-fluid"></a>

								</td>


							</tr>

						<?php } ?>
					</tbody>

				</table>
			<?php } else { ?>
			</div>
			<div class="text-center">
				<p class="fs-3"> <span class="text-danger">Oops!</span>No records found.</p>
			</div>
		<?php } ?>
	</div>


	<div class="modal fade" id="prev_task_list_modal">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header" style="">

					<h4>Previous task List</h4>
					<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
				</div>
				<div id="modal-body">
					<table id="data-table">
						<thead>
							<tr>
								<th>Task</th>
								<th>supervisor</th>
								<th>Employee</th>
								<th>Start time</th>
								<th>End time</th>
								<th>Quantity</th>
							</tr>
						</thead>
						<tbody id="table-body">
							<!-- Table body will be populated dynamically -->
						</tbody>
					</table>
				</div>
			</div>



		</div>
	</div>
	<div class="nxt-tsk">
		<div class="modal fade" id="next_task_list_modal">
			<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">

						<h4>Next task List</h4>
						<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
					</div>
					<div id="modal-body">
						<table id="data-table">
							<thead>
								<tr>
									<th>Task Id</th>

									<th>Action</th>
								</tr>
							</thead>
							<tbody id="next_task_table_body">
								<!-- Table body will be populated dynamically -->
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="modal fade" id="taskDeleteModal">
			<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="padding:15px 50px;">
					<h4> Delete task</h4>
						<button type="button" class="top-close " data-bs-dismiss="modal">&times;</button>
						
					</div>
					<form action="<?= base_url('task/deleteTask') ?>" class="form" id="DeleteTaskForm" name="DeleteTask"
						method="post">
						<div class="modal-body ctr-segment-body" style="padding:20px;">
							<p> Are you sure you want to delete the task?</p>
							<div class="form-group">
								<input type="hidden" class="form-control" id="taskId" name="taskId">
								<input type="hidden" class="form-control" id="taskDetailId" name="taskDetailId"
									value="<?= $taskDetail[0]["task_detail_id"] ?>">
							</div>
							<br />
							<div class="d-grid">
								<button type="submit" class="btn btn-danger confirm pull-right"><span
										class="fa fa-trash"></span>
									Confirm</button>
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
	function getPrevTaskList(taskId) {

		$.ajax({

			url: "<?= base_url('task/getPrevTaskList/') ?>" + taskId,
			type: 'get',
			dataType: 'json',
			success: function (response) {
				console.log(response);
				if (response.success) {
					console.log("successentry");

					console.log("response.output", response.output);
					populateModalTable(response.output);


					$("#prev_task_list_modal").modal("show");

				} else {
					console.log(response.error);
					console.log("failure");

				}
			},
			error: function (response) {
				console.log(response);
			}

		});
	}

	// Function to handle AJAX call and populate modal table
	function populateModalTable(data) {
		const tableBody = document.getElementById('table-body');

		// Clear existing table rows
		tableBody.innerHTML = '';

		// Loop through the data and create table rows
		data.forEach(item => {
			const row = document.createElement('tr');
			row.innerHTML = "<td>" + item.task_name + "</td><td>" + item.supervisor_name + "</td><td>" + item.employee_name + "</td><td>" + item.start_time + "</td><td>" + item.end_time + "</td><td>" + item.out_qty + "</td>";
			// Add more cells if needed
			tableBody.appendChild(row);
		});
	}

	$('.deleteTask').on('click', function () {
		//getting data of selected row using id.
		console.log("ENtry");
		// $id = document.getElementById('orderRow');
		// console.log($id);
		$tr = $(this).closest('tr');
		var data = $tr.children().map(function () {
			return $(this).text();
		}).get();
		console.log(data[0].trim());
		var taskId = data[0].trim();
		$('#taskId').val(taskId);
		$("#taskDeleteModal").modal('show');

		// var url = "<?= base_url('order/deleteOrder') ?>";



		//orderDelete(url, orderId);
	});

	function nextTask(nextTask) {

		if (nextTask.length > 0) {

			console.log("length", nextTask.length);
			if (nextTask.length == 1) {
				console.log("nextTask", nextTask[0].id);

				if (nextTask[0].is_qa == "1") {
					window.location.href = '<?= base_url("/task/qualityCheck/") ?>' + nextTask[0].id;
				}
				else {
					window.location.href = '<?= base_url("/task/mapEmployee/") ?>' + nextTask[0].id;

				}
			} else {
				console.log("length", nextTask.length);

				const tableBody = document.getElementById('next_task_table_body');

				// Clear existing table rows
				tableBody.innerHTML = '';

				// Loop through the data and create table rows
				nextTask.forEach(item => {
					console.log("item", item);
					const row = document.createElement('tr');
					if (item.is_qa == "1") {
						var url = '<?= base_url("/task/qualityCheck/") ?>' + item.id;
					} else {
						var url = '<?= base_url("/task/mapEmployee/") ?>' + item.id;

					}
					row.innerHTML = '<td>' + item.id + '</td><td><a type="button" href="' + url + '"><span class="far fa-arrow-alt-circle-right"></span></button></td>';
					// Add more cells if needed
					tableBody.appendChild(row);
				});
				$("#next_task_list_modal").modal("show");
			}
		}
		console.log("nextTask", nextTask);
	}
</script>
<?= $this->endSection() ?>