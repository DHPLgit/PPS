<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php echo script_tag('js/jquery.min.js'); ?>
<?php echo script_tag('js/functions/Script.js'); ?>
<style>
	.checkbox-menu li label {
		display: block;
		padding: 3px 10px;
		clear: both;
		font-weight: normal;
		line-height: 1.42857143;
		color: #333;
		white-space: nowrap;
		margin: 0;
		transition: background-color .4s ease;
	}

	.checkbox-menu li input {
		margin: 0px 5px;
		top: 2px;
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
</style>
<section class="home">
	<div class="container">
		<?php if (session()->getFlashdata('response') !== NULL): ?>
			<p style="color:green; font-size:18px;">
				<?php echo session()->getFlashdata('response'); ?>
			</p>
		<?php endif; ?>

		<div class="mt-5">
			<form id="deptForm" action="<?= base_url("department/addDepartment") ?>" method="post">
				<label for="dept_id">Enter Department name:</label>
				<input type="text" id="department" name="department">

				<p style="color:red" class="error" id="department_error" type="hidden"></p>
				<button id="deptForm-button" style='display:block' type="submit"> Verify</button>
			</form>
			<form id="mapDeptEmpForm" style="display:none" action="<?= base_url("taskDetail/deptMap") ?>" method="post">

				<input type="hidden" id="dept_id" name="dept_id">
				<input type="hidden" id="deptEmpMapId" name="deptEmpMapId">
				<div class="d-flex mb-5">
					<label for="supervisor_id">Select Supervisor:</label>
					<select id="supervisor_id" name="supervisor">
						<?php foreach ($supervisorList as $key => $supervisor) { ?>
							<option value="<?= $supervisor["id"] ?>"><?= $supervisor["name"] ?></option>
						<?php } ?>
					</select>
				</div>
				<p style="color:red" class="error" id="supervisor_error" type="hidden"></p>
				<div class="dropdown">
				<label for="dropdownMenu1">Select Employee:</label>
					<!-- <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1"
						data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Choose employees
						<span class="caret"></span>
					</button> -->
					<p style="color:red" class="error" id="employee_error" type="hidden"></p>
					<ul class="checkbox-menu allow-focus" >

						<?php foreach ($employeeList as $key => $employee) { ?>
							<li>
								<label>
									<input id="c_<?= $employee["id"] ?>" type="checkbox" value="<?= $employee["id"] ?>"
										name="employee[]"> <?= $employee["name"] ?>
								</label>
							</li>

						<?php } ?>

					</ul>
					
				</div>
				<button class="submit" type="submit">submit</button>
			</form>

		</div>

	</div>


</section>
<script>
	$(".checkbox-menu").on("change", "input[type='checkbox']", function () {
		$(this).closest("li").toggleClass("active", this.checked);
	});

	$(".allow-focus").on("click", function (e) {
		e.stopPropagation();
	});
	$("#mapDeptEmpForm").submit(function (event) {
		event.preventDefault();
		console.log("aa");
		mapDeptEmp($(this));
	});

	$("#deptForm").submit(function (event) {
		event.preventDefault();
		console.log("a")
		insertDepartment($(this))

	});


	var deptEmpMapData = <?php if (isset($deptEmpMapData)) {
		echo json_encode($deptEmpMapData);
	} else {
		echo json_encode(array());
	} ?>;
	var isEdit = <?= $isEdit ?>;
	if (isEdit == "1") {
		$("#deptForm").hide();
		$("#mapDeptEmpForm").show();
		$("#dept_id").val(deptEmpMapData["dept_id"]);
		$("#deptEmpMapId").val(deptEmpMapData["dept_emp_map_id"])
		if (deptEmpMapData && Object.keys(deptEmpMapData).length > 0) {

			var form = document.getElementById("mapDeptEmpForm");
			var drpdwn = form.querySelector("select");
			var options = drpdwn.options;

			for (let index = 0; index < options.length; index++) {
				const option = options[index];

				if (option.value == deptEmpMapData["supervisor_id"]) {
					option.selected = true;
					console.log("hi")
					break;
				}
				console.log("hi")

			}
			// var inputs=form.querySelectorAll("input[type='checkbox']");
			// console.log("inputs",inputs)
			var empArr = deptEmpMapData["employee_ids"].split(",");
			console.log("empArr", empArr);

			empArr.forEach(element => {
				var empId = "c_" + element;
				$("#" + empId).prop("checked", true);
			});
		}
	}
</script>
<?= $this->endSection() ?>