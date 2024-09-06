<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php echo script_tag('js/jquery.min.js'); ?>
<?php echo script_tag('js/functions/Script.js'); ?>
<section class="home">
	<div class="container">
		<div class="title-head">
			<div class="text-center">
				<a class="crt-sur float-end" href="<?= site_url('/taskDetail/deptMap'); ?>">Create Department</a>
			</div>
		</div>
		<div class="mt-3">
			<?php if (!empty($deptList)) { ?>
				<table class="table mt-6 table-striped table-bordered">
					<thead>
						<tr class="sur-lis-bd">
							<th scope="col">S.No.</th>
							<th scope="col" style="display:none;"> Id </th>
							<th scope="col">Department Name</th>
							<th scope="col">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $count = 0;
						foreach ($deptList as $dept) {
							$count++; ?>
							<tr id="task-det-row">
								<td><?= stripslashes($count); ?></td>
								<td style="display:none;"><?= stripslashes($dept['dept_id']); ?></td>
								<td><?= stripslashes($dept['dept_name']); ?></td>
								<td class="d-flex justify-content-evenly">
									<button type="button" class="view-btn editDepartment">Edit department</button>&nbsp;
									<form action="<?= base_url('taskDetail/deptMap') ?>" method="get">
										<input type="hidden" name="deptId" value="<?= $dept['dept_id'] ?>">
										<button class="view-btn view_edit_emp_list" type="submit">
											View / Edit employee list
										</button>
									</form>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } else { ?>
				<div class="text-center">
					<p class="fs-3"> <span class="text-danger">Oops!</span> No records found.</p>
				</div>
			<?php } ?>
		</div>
	</div>

	<!-- Modal for Editing Department -->
	<div class="container">
		<div class="modal fade" id="departmentEditModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header" style="padding:15px 50px;">
						<h4>Edit department</h4>
						<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
					</div>
					<form action="<?= base_url('department/updateDepartment') ?>" class="form" id="updateDepartmentForm"
						method="post">
						<div class="modal-body ctr-segment-body" style="padding:20px;">
							<div class="form-group">
								<input type="hidden" class="form-control" id="deptId" name="deptId">
								<label for="deptName">Department name</label>
								<input type="text" class="form-control" id="deptName" name="department">
								<p style="color:red" class="error" id="department_error" type="hidden"></p>
							</div>
							<br />
							<div class="d-grid">
								<button type="submit" class="btn btn-success confirm pull-right">
									<span class="fa fa-edit"></span> Save
								</button>
								<button type="button" class="btn btn-outline-secondary Cancel pull-left close"
									data-bs-dismiss="modal">
									<span class="fa fa-remove"></span> Cancel
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

<script>
	$(".editDepartment").on("click", function () {
		$tr = $(this).closest('tr');
		var data = $tr.children().map(function () {
			return $(this).text();
		}).get();
		console.log(data[1].trim());
		var deptId = data[1].trim();
		var deptName = data[2].trim();
		$("#deptId").val(deptId);
		$("#deptName").val(deptName);
		$("#departmentEditModal").modal("show");
	});

	$("#updateDepartmentForm").submit(function (event) {
		event.preventDefault();
		var form = $(this);
		updateDepartment(form);
	});

	$(".close").on("click", function () {
		removeError();
	});

	$("#reset").on("click", function () {
		var currentUrl = window.location.href;
		window.location.href = currentUrl.split('?')[0];
	});
</script>
<?= $this->endSection() ?>