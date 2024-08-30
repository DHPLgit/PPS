<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php echo script_tag('js/jquery.min.js'); ?>
<?php echo script_tag('js/functions/Script.js'); ?>
<section>
        <div class="container">
                <div class="task-details">
                        <form action="<?= base_url('taskDetail/createTaskDetail') ?>" method="post" id="taskForm">
                                <div class="row">
                                        <div class="col-md-6">
                                                <label for="task_name">Task Name: </label></br>
                                                <input type="text" id="task_name" class="task-form-input"
                                                        name="task_name" value="<?php echo set_value('task_name'); ?>">
                                                <p style="color:red;" class="error" id="task_name_error" type="hidden">
                                                </p>
                                                <label for="hours_taken">Hours Taken:</label></br>
                                                <input type="text" name="hours_taken" class="task-form-input"
                                                        id="hours_taken" value="<?php echo set_value('hours_taken'); ?>"
                                                        placeholder="Enter hours taken">
                                                <p style="color:red;" class="error" id="hours_taken_error"
                                                        type="hidden"></p>

                                                <label for="department">Select Department: </label></br>
                                                <select id="department" class="task-form-input" name="department">
                                                        <?php if (isset($departmentList)) ?>
                                                        <?php foreach ($departmentList as $department) { ?>
                                                                <option value="<?= $department["dept_id"] ?>" <?php if ($department["dept_id"] == set_value('supervisor')): ?>selected="selected" <?php endif; ?>>
                                                                        <?= $department["dept_name"] ?>
                                                                </option>
                                                        <?php } ?>


                                                </select>
                                                <p style="color:red" class="error" id="supervisor_error" type="hidden">
                                                </p><br>
                                        </div>
                                        <div class="col-md-6">


                                                <label for="task_name">Is this a QA task: </label><br>&nbsp;</br>
                                                <input type="radio" id="is_qa" name="is_qa" value="1"> Yes&nbsp;
                                                <input type="radio" id="is_qa1" name="is_qa" value="0"> NO
                                                <p style="color:red" class="error" id="is_qa_error" type="hidden"></p>
                                                <br>

                                                <div style="display: none;" class="dropdown" id="dropdownMenu">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                                                id="QualityDropdown" data-bs-toggle="dropdown"
                                                                aria-haspopup="true" aria-expanded="false">
                                                                Quality Check:
                                                        </button>
                                                        

                                                        <div class="dropdown-menu" aria-labelledby="QualityDropdown">
                                                                <?php if (isset($qcList)) { ?>
                                                                        <?php foreach ($qcList as $qc) { ?>
                                                                                <div class="form-check">
                                                                                        <input class="form-check-input" type="checkbox"
                                                                                                id="qc<?= $qc["qc_id"] ?>" name="quality_analyst[]"
                                                                                                value="<?= $qc["qc_id"] ?>">
                                                                                        <label class="form-check-label" for="qc<?= $qc["qc_id"] ?>">
                                                                                                <?= $qc['qc_name'] ?>
                                                                                        </label>
                                                                                </div>
                                                                        <?php }
                                                                } ?>


                                                        </div>
                                                </div>
                                                <p style="color:red" class="error" id="quality_analyst_error"
                                                        type="hidden"></p>
                                        </div>
                                </div>
                                <button class="taskdetails-btn" type="submit">Submit</button>
                        </form>

                        <!-- Dropdown menu -->

                </div>
        </div>
</section>

<!-- JavaScript to prevent form submission when dropdown is clicked -->
<script>
        document.getElementById('dropdownMenu').addEventListener('click', function (event) {
                event.stopPropagation();
        });
        $("#taskForm").submit(function (event) {
                event.preventDefault();
                InsertTaskDetail($(this))
        });

        $("#is_qa").on('change', function () {
                $("#dropdownMenu").show();
        })
        $("#is_qa1").on('change', function () {
                $("#dropdownMenu").hide();
        })

</script>


<?= $this->endSection() ?>