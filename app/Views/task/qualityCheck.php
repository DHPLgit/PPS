<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php echo script_tag('js/jquery.min.js'); ?>
<?php echo script_tag('js/functions/Script.js'); ?>

<section class="home">
    <div class="container">
        <div class="empmap-container">
            <h3>Task: <?= $taskDetail["task_name"] ?></h3>
            <label class="login-label">Order id</label>
            <di class="mb-3">
                <input type="text" readonly class="form-control" aria-describedby="emailHelp" placeholder="Order id" id="order_id" name="order_id" value="<?= $task["order_id"] . "-" . $task["item_id"] ?>" />
                <br />
                <h4 class="title">Order details:</h4>
                <div class="para-input">
                    <p class="para"><span>Texture:</span><?= $order["texture"] ?></p>
                    <p class="para"><span>Type:</span><?= $order["type"] ?></p>
                    <p class="para"><span>Extn size:</span><?= $order["ext_size"] ?></p>
                    <p class="para"><span>Colour:</span><?= $order["colour"] ?></p>

                    <p class="para"><span>Quantity:</span><?= $order["quantity"] ?></p>
                    <p class="para"><span>Length:</span><?= $order["length"] ?></p>
                </div>
                <h4 class="title">Input details:</h4>
                <div class="para-input">
                    <?php foreach ($inputDetails as $key => $input) { ?>
                        <p class="para"><span>Type:</span> <?= $input["in_type"] ?></p>
                        <p class="para"><span>Extn Size:</span><?= $input["in_ext_size"] ?></p>
                        <p class="para"><span>Colour:</span><?= $input["in_colour"] ?></p>
                        <p class="para"><span>Quantity:</span><?= $input["in_quantity"] ?></p>
                        <p class="para"><span>Length:</span><?= $input["in_length"] ?></p>
                    <?php } ?>
                </div>

                <h5 class="title">Select supervisor to QA:</h5>
                <div id="supervisor_div">
                    <select id="supervisor_id" name="supervisor">
                        <?php foreach ($supervisorList as $key => $supervisor) { ?>
                            <option value="<?= $supervisor["id"] ?>"><?= $supervisor["name"] ?></option> <?php } ?>
                    </select>
                    <button id="start_time" style="display:block" type="button"> Start Analyzing</button>
                </div>
                <div id="qc_list_div" style="border:0px">
                    <ul id="qc_checklist" style="display:none" class="checkbox-menu allow-focus" aria-labelledby="dropdownMenu1">

                        <?php foreach ($qcList as $key => $qc) { ?>
                            <li>
                                <label>
                                    <input type="checkbox" value="<?= $qc["qc_id"] ?>" name="qc[]"><span class="qc-name"><?= $qc["qc_name"] ?></span>
                                </label>
                            </li>
                        <?php } ?>
                    </ul>
                </div>

                <div id="next_task_div" style="display: none;">

                    <form id="next_task_form" action="<?= base_url("task/qualityCheck/" . $task['task_id']) ?>" method="post">
                        <input type="hidden" id="parent_task" name="parent_task" value="<?= $task["task_id"] ?>">
                        <input type="hidden" id="qa_task" name="qa_task" value="<?= $qaTask["task_id"] ?>">

                        <div id="next_task_detail_div" style="display: block;">
                            <h5 class="title" style="padding:0px">Next task:</h5>

                            <select id="next_task_detail_id" name="next_task_detail_id">
                                <?php foreach ($taskDetailList as $detail) { ?>
                                    <option value="<?= $detail['task_detail_id'] ?>"> <?= $detail['task_name'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <button class="button" id="ok"> Ok</button>

                    </form>
                </div>
                <button class="button" id="not_ok" style="display: none;"> Not ok</button>

                <?php if ($task["split_from"] != 0 && $qaTask["task_detail_id"] != 101) { ?>
                    <span> Separate task<input type="checkbox" id="separateTask" name="separateTask"></span>
                <?php } ?>
        </div>
    </div>
    </div>
</section>
<script>
    <?php $flag = ($qaTask["status"] == "In progress") ? true : false ?>
    flag = false;
    flag = <?php if (isset($flag)) {
                echo json_encode($flag);
            } ?>

    if (flag) {
        $("#qc_checklist").show();
        $("#start_time").hide();

    }
    var qcCount = <?= count($qcList) ?>;
    var checkedCount = 0;
    var separate_task = 0;
    $("#separateTask").on("change", function() {

        if ($("#separateTask").prop("checked")) {
            separate_task = 1;
        } else {
            separate_task = 0;
        }
    })

    $(".checkbox-menu").on("change", "input[type='checkbox']", function() {
        if ($(this).prop("checked") == true) {
            checkedCount += 1;
        } else {
            checkedCount -= 1;
        }
        if (checkedCount == qcCount) {


            $("#not_ok").hide();
            console.log(<?= $qaTask["task_detail_id"] ?>)

            if ((<?= $qaTask["task_detail_id"] ?> != 101)) {

                $("#next_task_div").show();
                console.log("ok");
            } else {
                $("#next_task_div").show();

                $("#next_task_detail_div").hide();
            }
        } else {

            $("#not_ok").show();
            $("#next_task_div").hide();

            console.log("not ok");
        }
    });

    $(".allow-focus").on("click", function(e) {
        e.stopPropagation();
    });

    $("#start_time").on("click", function() {
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: {
                qa_task: <?= $qaTask["task_id"] ?>
            },
            url: "<?= base_url("task/startQC") ?>",

            success: function(response) {
                if (response.success) {

                    $("#qc_checklist").show();
                    $("#start_time").hide();
                }

            },
            error: function(response) {

                console.log("failure", response);
            }
        })
    })
    $("#next_task_form").submit(function(event) {
        event.preventDefault();


        var current_task_detail_id = <?= $qaTask["task_detail_id"] ?>;

        var input = {
            parent_task: <?= $task["task_id"] ?>,
            qa_task: <?= $qaTask["task_id"] ?>,
            current_task_detail_id: current_task_detail_id,
            // separate_task: separate_task,
            is_last_task: <?= $qaTask["isLastTask"] ?>,
            order_list_id: <?= $task["order_list_id"] ?>,
            order_id: <?= $task["order_id"] ?>,
            supervisor_id: $("#supervisor_id").val()
        };
        var splitFrom = <?= $task["split_from"] ?>;
        if (splitFrom != 0) {
            input.separate_task = separate_task;

        }
        if (current_task_detail_id != 101) {
            input.next_task_detail_id = $("#next_task_detail_id").val()
        }

        $.ajax({
            type: "post",
            url: "<?= base_url("task/qualityCheck/" . $task['task_id']) ?>",
            data: input,
            dataType: "json",

            success: function(response) {
                window.location.href = response.url;

            },
            error: function(response) {

            }


        })
    })
    $("#not_ok").on("click", function() {

        $.ajax({
            url: "<?= base_url("task/restartTask") ?>",
            type: "post",
            data: {
                parent_task: "<?= $task["task_id"] ?>",
                qa_task: "<?= $qaTask["task_id"] ?>"
            },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    window.location.href = response.url;
                }
            },
            error: function(response) {
                console.log("failure", response);
            }

        })
    })
</script>
<?= $this->endSection() ?>