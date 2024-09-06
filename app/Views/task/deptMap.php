<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php echo script_tag('js/jquery.min.js'); ?>
<?php echo script_tag('js/functions/Script.js'); ?>
<style>
	.checkbox-menu li label {
		display: block;
		padding: 3px 10px;
		font-weight: normal;
		line-height: 1.42857143;
		color: #333;
		white-space: nowrap;
		margin: 0;
		transition: background-color .4s ease;
	}

	.checkbox-menu li input {
		margin: 0px 5px;
		position: relative;
		accent-color: #000;
	}

	.checkbox-menu li.active label {
		background-color: #cbcbff;
		font-weight: bold;
	}

	.checkbox-menu li label:hover,
	.checkbox-menu li label:focus {
		background-color: #f5f5f5;
	}

	.checkbox-menu li.active label:hover,
	.checkbox-menu li.active label:focus {
		background-color: #b8b8ff;
	}

	.modal {
		display: none;
		position: fixed;
		z-index: 1;
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;
		overflow: auto;
		background-color: rgba(0, 0, 0, 0.4);
	}

	.modal-content {
		background-color: #fefefe;
		margin: 15% auto;
		padding: 20px;
		border: 1px solid #888;
		width: 80%;
	}
	
	.close {
		color: #aaa;
		float: right;
		font-size: 28px;
		font-weight: bold;
	}

	.close:hover,
	.close:focus {
		color: black;
		text-decoration: none;
		cursor: pointer;
	}
</style>
<section class="home">
	<div class="container">
		<?php if (session()->getFlashdata('message')): ?>
			<div class="alert alert-success">
				<?= session()->getFlashdata('message') ?>
			</div>
		<?php endif; ?>

		<div class="mt-5">
			<form id="deptForm" action="<?= base_url("department/addDepartment") ?>" method="post">
				<label for="department">Enter Department name:</label>
				<input type="text" id="department" name="department">
				<p style="color:red" class="error" id="department_error"></p>
				<button id="deptForm-button" type="submit">Verify</button>
			</form>
			<form id="mapDeptEmpForm" style="display:none" action="<?= base_url("taskDetail/deptMap") ?>" method="post">
				<input type="hidden" id="dept_id" name="dept_id">
				<input type="hidden" id="deptEmpMapId" name="deptEmpMapId">
				<div class="d-flex mb-5">
					<label for="supervisor_id">Select Supervisor:</label>
					<select id="supervisor_id" name="supervisor">
						<?php foreach ($supervisorList as $supervisor) { ?>
							<option value="<?= $supervisor["id"] ?>"><?= $supervisor["name"] ?></option>
						<?php } ?>
					</select>
				</div>
				<p style="color:red" class="error" id="supervisor_error"></p>
				<div class="dropdown">
					<label for="dropdownMenu1">Select Employee:</label>
					<p style="color:red" class="error" id="employee_error"></p>
					<ul class="checkbox-menu allow-focus">
						<?php foreach ($employeeList as $employee) { ?>
							<li>
								<label>
									<input id="c_<?= $employee["id"] ?>" type="checkbox" value="<?= $employee["id"] ?>"
										name="employee[]"> <?= $employee["name"] ?>
								</label>
							</li>
						<?php } ?>
					</ul>
				</div>
				<button class="submit" onclick="showModal()" type="submit">Submit</button>
			</form>
		</div>
	</div>
</section>

<!-- Modal HTML -->
<div id="successModal" class="modal">
	<div class="modal-content">
		<span class="close" onclick="closeModal()">&times;</span>
		<p>New department saved successfully!</p>
	</div>
</div>

<script>
	function showModal() {
		var modal = document.getElementById('successModal');
		modal.style.display = 'block';
		setTimeout(function () {
			closeModal();
		}, 10000);
		
	}

	function closeModal() {
		document.getElementById('successModal').style.display = 'none';
	}

	$(".checkbox-menu").on("change", "input[type='checkbox']", function () {
		$(this).closest("li").toggleClass("active", this.checked);
	});

	$(".allow-focus").on("click", function (e) {
		e.stopPropagation();
	});

	$("#mapDeptEmpForm").submit(function (event) {
		event.preventDefault();
		console.log("Mapping department and employees");
		mapDeptEmp($(this));
	});

	$("#deptForm").submit(function (event) {
		event.preventDefault();
		console.log("Inserting department");
		insertDepartment($(this));
	});

	var deptEmpMapData = <?php echo json_encode($deptEmpMapData ?? []); ?>;
	var isEdit = <?= $isEdit ?>;
	if (isEdit == "1") {
		$("#deptForm").hide();
		$("#mapDeptEmpForm").show();
		$("#dept_id").val(deptEmpMapData["dept_id"]);
		$("#deptEmpMapId").val(deptEmpMapData["dept_emp_map_id"]);
		if (deptEmpMapData && Object.keys(deptEmpMapData).length > 0) {
			var drpdwn = document.getElementById("supervisor_id");
			for (var option of drpdwn.options) {
				if (option.value == deptEmpMapData["supervisor_id"]) {
					option.selected = true;
					break;
				}
			}
			deptEmpMapData["employee_ids"].split(",").forEach(function (id) {
				$("#c_" + id).prop("checked", true);
			});
		}
	}
</script>
<?= $this->endSection() ?>