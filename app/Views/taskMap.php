<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php echo script_tag('js/jquery.min.js'); ?>
<?php echo script_tag('js/functions/Script.js'); ?>
<section class="home">
    <div class="container">
        <div class="empmap-container">
            <h3>Task:
                <?= $currentTaskDetail["task_name"] ?>
            </h3>
            <label class="login-label">Order id</label>
            <div class="mb-3">
                <input type="text" readonly class="form-control" aria-describedby="emailHelp" placeholder="Order id" id="order_id" name="order_id" value="<?= $task["order_id"] . "-" . $task["item_id"] ?>" />
                <br />
                <!-- <p style="color:red" class="error" id="Order_unique_id_error" type="hidden"></p> -->

                <h4 class="title">Order details:</h4>
                <div class="para-input">
                    <p class="para"><span>Texture:</span>
                        <?= $order["texture"] ?>
                    </p>
                    <p class="para"><span>Type:</span>
                        <?= $order["type"] ?>
                    </p>
                    <p class="para"><span>Extn size:</span></p>

                    <p class="para"><span>Length:</span>
                        <?= $order["length"] ?>
                    </p>
                </div>
                <h4 class="title">Input details:</h4>
                <div class="para-input-2">
                    <?php foreach ($inputDetails as $key => $input) { ?>
                        <div class="inpt-details-row">
                            <p class="para"><span>Size:</span>
                                <?= $input["in_ext_size"] ?>
                            </p>
                            <p class="para"><span>Colour:</span>
                                <?= $input["in_colour"] ?>
                            </p>
                            <p class="para"><span>Quantity:</span>
                                <?= $input["in_quantity"] ?>
                            </p>
                            <p class="para"><span>Type:</span>
                                <?= $input["in_type"] ?>
                            </p>
                        </div>
                    <?php } ?>
                </div>
                <div id="output_div">
                    <h4 class="title">Output details:</h4>
                    <div class="para-input">
                        <p class="para"><span>Length:</span>
                            <?= $currentTask["out_length"] ?>
                        </p>
                        <p class="para"><span>Colour:</span>
                            <?= $currentTask["out_colour"] ?>
                        </p>
                        <p class="para"><span>Quantity:</span>
                            <?= $currentTask["out_qty"] ?>
                        </p>
                        <p class="para"><span>Texture:</span>
                            <?= $currentTask["out_texture"] ?>
                        </p>
                        <p class="para"><span>Type:</span>
                            <?= $currentTask["out_type"] ?>
                        </p>
                    </div>
                </div>
                <div id="map_employee_div" style="display: block;">
                    <div>
                        <label for="split">Split Job</label>
                        <input type="radio" id="split" name="split"> Yes
                        <input type="radio" id="no_split" name="split"> No
                        <br />
                        <!-- <label for="">Quality Check:</label>
                        <a type="button" href="<?= base_url("task/qualityCheck/" . $task['task_id']) ?>"> Click here for QA</a> -->
                    </div>
                    <input id="split_count" style="display:none" type="number">

                    <div id="split_div" style="display:none">

                        <form id="add_split_form" style="display: block;">
                            <div id="split_inputs">
                                <?php for ($i = 1; $i <= count($inputDetails); $i++) { ?>
                                    <label for="in_qty">Qty:</label>
                                    <input type="hidden" id="in_qty_id_<?= $i ?> " name="in_qty_id[]" value="<?= $inputDetails[$i - 1]['input_id'] ?>">
                                    <input type="text" id="in_qty_<?= $i ?> " name="in_qty[]">
                                <?php } ?>
                                <br />
                                <label for="employee_id_split">Select Employee to work:</label>
                                <select id="employee_id_split" name="employee">
                                    <?php foreach ($employeeList as $key => $employee) { ?>
                                        <option value="<?= $employee["id"] ?>">
                                            <?= $employee["name"] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <p style="color:red" class="error" id="quantity_error" type="hidden"></p>
                            <p style="color:red" class="error" id="employee_eror" type="hidden"></p>
                            <div>
                                <button type="submit"> Add</button>
                            </div>

                        </form>
                        <br />
                        <button id="save_split"> Save</button>

                    </div>
                    <div id="preview" style="display: none;"></div>

                    <form id="emp_map_form" style="display: block;" action="<?= base_url('task/mapEmployee/' . $task['task_id']) ?>" method="post">

                        <input type="hidden" id="task_detail_id" name="taskDetailId" value="<?= $currentTaskDetail["task_detail_id"] ?>">
                        <label for="employee_id">Select Employee to work:</label>
                        <select id="employee_id" name="employee">

                            <?php foreach ($employeeList as $key => $employee) { ?>
                                <option value="<?= $employee["id"] ?>">
                                    <?= $employee["name"] ?>
                                </option>

                            <?php } ?>
                        </select>
                        <div class="button-row">
                            <button id="emp_map_form_btn" type="submit"> Start</button>
                        </div>
                    </form>
                </div>
                <div id="in_progress_div" style="display: none;">
                    <h4 class="title">Create a output field:</h4>
                    <div class="create-btn">

                        <input type="number" id="output_count" placeholder="Create a output field" name="output_count">
                        <button id="create_output_group">Create</button>
                    </div>
                    <form id="in_progress_form" action="" method="post">
                        <div id="form_inputs">
                        </div>
                        <p style="color:red" class="error" id="output_error" type="hidden"></p>

                        <button class="save-opt" type="submit"> Save Outputs</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="container">
        <div class="modal fade" id="confirmModal">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header" style="padding:15px 50px;">
                        <h4> Confirm selection</h4>
                        <button type="button" class="close top-close" data-bs-dismiss="modal">&times;</button>

                    </div>
                    <div class="modal-body ctr-segment-body" style="padding:20px;">
                        <p> Are you sure you want to change the product type?</p>

                        <div class="d-grid">
                            <button type="button" onclick="inProgressFormSubmit()" class="btn btn-danger confirm pull-right"><span class="fa fa-trash"></span>
                                Confirm</button>
                            <button type="button" class="btn btn-outline-secondary Cancel pull-left close" data-bs-dismiss="modal"><span class="fa fa-remove"></span> Cancel</button>
                        </div>
                    </div>


                </div>
            </div>
        </div>

    </div>
</section>
<script>
    var inputDetails = <?= json_encode($inputDetails) ?>;
    console.log("inputDetails", inputDetails[0].in_type)
    $("#type_dropdown").on("change", function() {
        console.log("hi")
    })
    var overAllQty = 0;
    var overAllReqQty = 0;

    inputDetails.forEach(element => {

        overAllQty += parseInt(element.in_quantity);
        console.log("overAllQty", overAllQty)
    });

    <?php $completed_flag = ($task["status"] == "Completed") ? true : false ?>
    completed_flag = false;
    console.log(completed_flag);
    completed_flag = <?php if (isset($completed_flag)) {
                            echo json_encode($completed_flag);
                        } ?>;
    console.log(completed_flag);

    if (completed_flag) {
        $("#map_employee_div").hide();
        $("#in_progress_div").hide();

    } else {
        $("#output_div").hide();
    }

    <?php $flag = ($task["status"] == "In progress") ? true : false ?>
    flag = false;
    flag = <?php if (isset($flag)) {
                echo json_encode($flag);
            } ?>;

    if (flag) {
        $("#map_employee_div").hide();
        $("#in_progress_div").show();

    }
    $("#create_output_group").on("click", function() {
        var count = $("#output_count").val();

        $("#form_inputs").html("");
        for (let index = 0; index < count; index++) {
            var opening_div = '<div class="output_group">'

            var colour = '<label for="colour">Colour:</label><br/><select id="dropdown" class="colour_select" name="colour"><?php if (isset($drpdwnData)) {
                                                                                                                                foreach ($drpdwnData->Colours as $colour) { ?><option value="<?php echo $colour ?>"><?php echo $colour ?></option><?php }
                                                                                                                                                                                                                                            } ?></select><br/>';
            var length = '<label for="Length">Length:</label><br/><input type="number" class="length" placeholder="length"><p style="color:red;display:none" class="error length_error"></p> <br/>';
            var texture = '<label for="texture">Texture:</label><br/><select id="dropdown" class="texture_select" name="texture"><?php if (isset($drpdwnData)) {
                                                                                                                                        foreach ($drpdwnData->Textures as $texture) { ?><option value="<?php echo $texture ?>"><?php echo $texture ?></option><?php }
                                                                                                                                                                                                                                                        } ?></select><br/>';
            var weight = '<label for="weight">Weight (gm):</label><br/><input type="number" class="weight" placeholder="weight"><p style="color:red;display:none" class="error weight_error"></p> <br/>';
            
            var type = '<label for="Type">Type:</label><br/><select id="type_dropdown" class="type_select" name="type"><?php if (isset($drpdwnData)) {
                                                                                                                            foreach ($drpdwnData->Types as $type) { ?><option value="<?php echo $type ?>"><?php echo $type ?></option><?php }
                                                                                                                                                                                                                                } ?></select>';
            var closing_div = '</div>';

            var output_form = opening_div + colour + length + texture + weight + type + closing_div;

            $("#form_inputs").append(output_form);


        }

    })

    $("#emp_map_form").submit(function() {

        $("#emp_map_form_btn").prop("disabled", true);

    })

    //In progress form submit
    $("#in_progress_form").submit(function(event) {

        event.preventDefault();
        inputDetails[0].in_type

        var typeArr = [];
        var count = 0;


        $('.output_group').each(function(index) {

            var typeSelect = $(this).find('.type_select');

            var type = typeSelect[0].options[typeSelect[0].selectedIndex].value;
            typeArr[count] = type;
            count++;
        });

        var isTypeMismatch = 0;
        console.log("isTypeMismatch", isTypeMismatch)
        typeArr.forEach(type => {
            inputDetails[0].in_type
            for (let index = 0; index < inputDetails.length; index++) {
                const element = inputDetails[index];
                if (type != element.in_type) {
                    console.log("isTypeMismatch inside")

                    isTypeMismatch = 1;
                    break;
                }
                return true;
            }
            console.log("isTypeMismatch", isTypeMismatch)

        });
        if (isTypeMismatch == 1) {

            $("#confirmModal").modal("show");
        } else {
            inProgressFormSubmit();
        }
    });

    function inProgressFormSubmit() {
        $("#confirmModal").modal("hide");
        var formData = [];
        overAllReqQty = 0;
        var isError = false;
        //  removeError();
        $(".error").hide();
        $(".error").text("");
        $('.output_group').each(function(index) {
            //var isError = false;
            var colourSelect = $(this).find('.colour_select');
            var colour = colourSelect[0].options[colourSelect[0].selectedIndex].value;
            var length = $(this).find('.length').val();
            // var length = lengthSelect[0].options[lengthSelect[0].selectedIndex].value;
            var textureSelect = $(this).find('.texture_select');
            var texture = textureSelect[0].options[textureSelect[0].selectedIndex].value;
            var typeSelect = $(this).find('.type_select');

            var type = typeSelect[0].options[typeSelect[0].selectedIndex].value;
            var weight = parseInt($(this).find('.weight').val());

            overAllReqQty += isNaN(parseInt(weight)) ? 0 : parseInt(weight);
            console.log("overAllReqQty", overAllReqQty);
            console.log("colour", colour);
            console.log("texture", texture);
            console.log("length", length);
            console.log("weight", parseInt(weight));
            console.log("type", type)
            // if (colour.length == 0 || length.length == 0 || texture.length == 0 || weight.length == 0 || type.length == 0) {
            //     $("#output_error").text("Please enter valid values.")
            //     formData = [];
            //     return false;

            // } else 
            if (isNaN(weight)) {

                $(this).find(".weight_error").text("Please enter valid weight.")
                isError = true;
            } else if (weight > overAllQty) {

                $(this).find(".weight_error").text("Please enter weight less than or equal to input quantity.")

                isError = true;

            } else if (overAllReqQty > overAllQty) {

                $("#output_error").text("Entered quantities is greater than input quantity.")
                isError = true;
            }
            if (length <= 0) {
                console.log("here1");
                $(this).find(".length_error").text("Please enter valid length.")
                isError = true;

            } 

            if (!isError) {
                var data = {
                    colour: colour,
                    length: length,
                    texture: texture,
                    weight: weight,
                    type: type
                };
                formData.push(data);
            }
            else{
                formData = [];

            }

        });
        $(".error").show();

        console.log("form", formData);
        if (formData.length > 0) {

            var nextId = $("#next_task_detail_id").val();
            $.ajax({
                url: "<?php echo base_url('task/splitTaskOnOutput/' . $task["task_id"]) ?>",
                type: 'post',
                dataType: 'json',
                data: {
                    req: formData,
                    taskDetailId: <?= $currentTaskDetail["task_detail_id"] ?>,
                    nextTaskDetailId: nextId

                },
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        console.log("successentry");

                        console.log("response.output", response.output);
                        // var inputs = form.querySelectorAll("input");
                        window.location.href = response.url;

                    } else {
                        // $('#loader').hide();
                        console.log(response.error);
                        console.log("failure");

                    }
                },
                error: function(response) {
                    console.log(response);
                }

            });
        }
    }



    $("#split").on("click", function() {
        $("#preview").show();
        $("#split_div").show();
        // $("#split_count").show();
        $("#emp_map_form").hide();
        $("#employee_eror").text("");

        emptyPreview();
        //  var count = 2;
        // $("#split_inputs").append('< label for="in_qty">Qty:</label><input type="text" id="in_qty" name="in_qty" ><label for="employee_id">Select Employee to work:</label>  <select id="employee_id" name="employee[]"> <?php foreach ($employeeList as $key => $employee) { ?><option value="<?= $employee["id"] ?>"><?= $employee["name"] ?></option> <?php } ?>  </select>')

    });

    $("#no_split").on("click", function() {

        $("#split_div").hide();
        //  $("#split_count").hide();
        $("#emp_map_form").show();
        $("#preview").hide();
    });
    // $("#split_count").on("keyup", function() {

    //     var count = $(this).val();
    //     $("#split_inputs").html("");

    //     if (count > 0) {

    //     }
    //     for (let index = 0; index < count; index++) {
    //         $("#split_inputs").append('<label for="in_qty' + index + '">Qty:</label><input type="text" id="in_qty' + index + '" name="in_qty[]" ><label for="employee_id' + index + '">Select Employee to work:</label>  <select id="employee_id' + index + '" name="employee[]"> <?php foreach ($employeeList as $key => $employee) { ?><option value="<?= $employee["id"] ?>"><?= $employee["name"] ?></option> <?php } ?>  </select>')

    //     }
    // });



    var qty_emp_input = new Object();
    var overAllAccQty = 0;

    $("#add_split_form").submit(function(event) {
        overAllReqQty = 0;
        console.log("overAllReqQty", overAllReqQty);
        event.preventDefault();
        console.log($('#add_split_form').serializeArray());
        //  saveSplitInput($(this))
        var form = $(this);

        $("#stock_id_error").text("");
        $("#quantity_error").text("");
        //if (qty_emp_input) {
        var in_qty_elements = document.querySelectorAll('input[name="in_qty[]"]');
        var in_qty_id_elements = document.querySelectorAll('input[name="in_qty_id[]"]');
        //  var in_qty_list =new Object();
        var in_qty_list = [];
        var in_qty = 0;
        for (let index = 0; index < in_qty_elements.length; index++) {
            in_qty = in_qty_elements[index].value;

            overAllReqQty += parseInt(in_qty);
            console.log("overAllReqQty", overAllReqQty);
            const in_qty_id = in_qty_id_elements[index].value;
            var qty_pair = {
                in_qty: in_qty,
                in_qty_id: in_qty_id
            };
            //in_qty_list[in_qty_id] = in_qty;
            in_qty_list.push(qty_pair);
            console.log(in_qty_list);
        }

        //var qty = form.find('input[name="in_qty[]"]').val();
        var employee = form.find('select[name="employee"]').val();
        //  console.log(qty);
        console.log(employee);
        var qtyElement = document.getElementById("quantity");
        var selectElement = document.getElementById("employee_id_split");
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        console.log("selectedOption", selectedOption);

        console.log(qty_emp_input)

        var selectedOptionText = selectedOption.innerText;
        console.log(selectedOptionText);

        //     console.log("qe", typeof(qty_emp_input));
        //    var arr= Object.keys(qty_emp_input).map((key) => [key, qty_emp_input[key]]);
        //    console.log("r", arr);
        //    arr.forEach(element => {
        //     console.log("ele", element);
        //     console.log("ele", element['1']);
        //     console.log("ele", element['1']['0'].in_qty);
        //     });


        //Adding the preview of a entered quantity if condition satisfies.
        console.log("overAllReqQty", overAllReqQty);
        console.log("overAllAccQty", overAllAccQty);
        console.log("overAllQty", overAllQty);
        if ((overAllReqQty + overAllAccQty) <= overAllQty) {

            if (employee) {
                overAllAccQty += parseFloat(in_qty);
                console.log("overAllAccQty", overAllAccQty);
                var input = {
                    qty: in_qty_list,
                };
                console.log(input);
                document.querySelectorAll("#employee_id_split option").forEach(opt => {
                    //console.log(opt)
                    if (opt.value == employee) {
                        opt.disabled = true;
                    }
                });

                var previewContainer = document.getElementById('preview');
                var employee_div = document.createElement('div');
                employee_div.className = 'employee_div';
                employee_div.innerText = selectedOptionText;
                var quantity_div = document.createElement('div');
                quantity_div.className = "quantity_div";

                var qty_inner_text = "";
                count = 0;
                in_qty_list.forEach(element => {
                    count++;
                    console.log("element", element);
                    if (count >= 2) {
                        qty_inner_text += "\n" + "quantity " + count + ": " + element.in_qty;
                    } else {
                        qty_inner_text += "quantity " + count + ": " + element.in_qty;
                    }

                });
                quantity_div.innerText = qty_inner_text;
                employee_div.appendChild(quantity_div);
                var removeButton = document.createElement('button');
                removeButton.innerText = 'X';
                removeButton.onclick = function() {

                    document.querySelectorAll("#employee_id_split option").forEach(opt => {
                        //console.log(opt)

                        if (opt.value == employee) {
                            opt.disabled = false;
                            console.log("removed", qty_emp_input[employee][0].in_qty);
                            overAllAccQty -= parseFloat(qty_emp_input[employee][0].in_qty);
                            console.log("overAllAccQty", overAllAccQty);
                            delete qty_emp_input[employee];
                        }
                    });

                    previewContainer.removeChild(employee_div);
                }
                employee_div.appendChild(removeButton);
                previewContainer.appendChild(employee_div);
                //qty_emp_input.push(input);
                console.log("employee", employee);
                qty_emp_input[employee] = in_qty_list;
                console.log("2", qty_emp_input)
                console.log("here2", JSON.stringify(qty_emp_input));

            } else {
                $("#quantity_error").text("Please select different employee.")
            }

        } else {
            $("#quantity_error").text("Please enter valid quantity.")
        }
    });

    $("#save_split").on("click", function() {
        var a = qty_emp_input.length;
        console.log("len", a);
        $("#save_split").prop("disabled", true);
        if (Object.keys(qty_emp_input).length > 0) {
            $.ajax({
                url: "<?php echo base_url('task/splitTask/' . $task["task_id"]) ?>",
                type: 'post',
                dataType: 'json',
                data: {
                    req: JSON.stringify(qty_emp_input),
                    taskDetailId: <?= $currentTaskDetail["task_detail_id"] ?>
                },
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        console.log("successentry");

                        console.log("response.output", response.output);
                        // var inputs = form.querySelectorAll("input");
                        window.location.href = response.url;

                    } else {
                        $("#save_split").prop("diabled", false);
                        // $('#loader').hide();
                        console.log(response.error);
                        console.log("failure");
                    }
                },
                error: function(response) {
                    console.log(response);
                }

            });
        } else {
            $("#save_split").prop("disabled", false);
            $("#employee_eror").text("Please enter quantity and select employee to map.")
            console.log("no dat a found.");
        }
    });

    var removeByAttr = function(arr, attr, value) {
        var i = arr.length;
        while (i--) {
            if (arr[i] &&
                arr[i].hasOwnProperty(attr) &&
                (arguments.length > 2 && arr[i][attr] === value)) {

                arr.splice(i, 1);

            }
        }
        return arr;
    }

    function emptyPreview() {

        //resetting the quantity input.
        var inputs = document.getElementsByName('in_qty[]');

        for (var i = 0; i < inputs.length; i++) {
            inputs[i].value = '';
        }

        //removing the preview and reset to default values.
        qty_emp_input = new Object();
        document.querySelectorAll("#employee_id_split option").forEach(opt => {

            opt.disabled = false;
        })
        $("#preview").html("");
        overAllAccQty = 0

    }
</script>
<?= $this->endSection() ?>