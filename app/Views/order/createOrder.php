<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php echo script_tag('js/jquery.min.js'); ?>
<?php echo script_tag('js/functions/Script.js'); ?>
<section class="create-order">
    <div class="container">
        <div class="d-flex justify-content-between">
            <h2 id="Action-type">Create order</h2>
            <div class="text-center"><a class="task-bck-btn" href="<?php echo site_url('/order/orderList'); ?>">Back</a>
            </div>
        </div>

        <div class="col-md-12" style="text-align:center;">
            <form id="orderForm" action="<?= base_url('order/createOrder') ?>" method="post">
                <div class="">
                    <div class="form-list">
                        <label class="login-label">Order id</label>
                        <div class="mb-3">
                            <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Order id" id="order_id" name="order_id" />

                            <p style="color:red" class="error" id="order_id_error" type="hidden"></p>
                            <?php if (isset($validation)) : ?>
                                <div style="color:red">
                                    <?= $validation->showError('order_id') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div id="ord-item-button">
                        <button type="button" onclick="checkAndGenerateId('<?= base_url('order/checkAndGenerateId') ?>',$('#order_id').val())">Generate Id</button>
                    </div>

                </div>
                <div id="item-form" style="display: none;">
                    <div class="form-list">
                        <label class="login-label">Item number</label>
                        <div class="mb-3">

                            <input type="text" readonly class="form-control" aria-describedby="emailHelp" placeholder="Item id" id="item_id" name="item_id" />

                        </div>
                    </div>
                    <div class="form-list">
                        <!-- <label class="login-label">Reference Id</label>
                        <div class="mb-3">

                            <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Reference Id" id="reference_id" name="reference_id" />
                            <p style="color:red" class="error" id="reference_id_error" type="hidden"></p>
                        </div> -->
                        <!-- <div class="form-list">
              <label class="login-label">Customer id</label>
              <div class="mb-3">
                 <input type="select" class="form-control" aria-describedby="emailHelp" placeholder="Customer id" id="Customer_id" name="Customer_id" /> 
                 <select placeholder="Customer id" id="customer_id" name="customer_id">

                  <?php foreach ($json->Colours as $colour) { ?>
                    <option value="<?php echo $colour ?>">
                      <?php echo $colour ?>
                    </option>
                  <?php } ?>
                </select>
                <p style="color:red" class="error" id="customer_id_error" type="hidden"></p>
                <?php if (isset($validation)) : ?>
                  <div style="color:red">
                    <?= $validation->showError('Customer_id') ?>
                  </div>
                <?php endif; ?>
              </div>
            </div> -->
                        <div class="form-list">
                            <label class="login-label">Order date</label>
                            <div class="mb-3">
                                <input type="date" class="form-control" aria-describedby="emailHelp" placeholder="Order date" id="order_date" name="order_date" />
                                <p style="color:red" class="error" id="order_date_error" type="hidden"></p>
                                <?php if (isset($validation)) : ?>
                                    <div style="color:red">
                                        <?= $validation->showError('Order_date') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
                        <!-- <label class="login-label">Phone No:</label>
              <div class="mb-3">
                <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Phone" id="phone"  name="phone"/>
              </div> -->
                        <div class="form-list">
                            <label class="login-label">Type</label>
                            <div class="mb-3">
                                <!-- <input type="select" class="form-control" placeholder="Type" id="Type" name="Type"> -->
                                <select placeholder="Type" id="type" name="type">
                                    <?php foreach ($json->Types as $type) { ?>
                                        <option value="<?php echo $type ?>">
                                            <?php echo $type ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <p style="color:red" class="error" id="type_error" type="hidden"></p>
                                <?php if (isset($validation)) : ?>
                                    <div style="color:red">
                                        <?= $validation->showError('Type') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-list">
                            <label class="login-label">Colour</label>
                            <div class="mb-3">
                                <!-- <input type="select" class="form-control" placeholder="Colour" id="Colour" name="Colour"> -->
                                <select placeholder="Colour" id="colour" name="colour">
                                    <?php foreach ($json->Colours as $colour) { ?>
                                        <option value="<?php echo $colour ?>">
                                            <?php echo $colour ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <input type="text" name="other_colour" id="other_colour" style="display: none;"> 
                                <p style="color:red" class="error" id="colour_error" type="hidden"></p>
                                <?php if (isset($validation)) : ?>
                                    <div style="color:red">
                                        <?= $validation->showError('Colour') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-list">
                            <label class="login-label">Length</label>
                            <div class="mb-3">
                                <!-- <input type="select" class="form-control" aria-describedby="emailHelp" placeholder="Length" id="Length" name="Length" /> -->
                                <select placeholder="Length" id="length" name="length">
                                    <?php foreach ($json->Length as $length) { ?>
                                        <option value="<?php echo $length ?>">
                                            <?php echo $length ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <p style="color:red" class="error" id="length_error" type="hidden"></p>
                                <?php if (isset($validation)) : ?>
                                    <div style="color:red">
                                        <?= $validation->showError('Length') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-list">
                            <label class="login-label">Texture</label>
                            <div class="mb-3">
                                <!-- <input type="select" class="form-control" aria-describedby="emailHelp" placeholder="Texture" id="Texture" name="Texture" /> -->
                                <select placeholder="Texture" id="texture" name="texture">
                                    <?php foreach ($json->Textures as $texture) { ?>
                                        <option value="<?php echo $texture ?>">
                                            <?php echo $texture ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <p style="color:red" class="error" id="texture_error" type="hidden"></p>
                                <?php if (isset($validation)) : ?>
                                    <div style="color:red">
                                        <?= $validation->showError('Texture') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-list">
                            <label class="login-label">Extension size</label>
                            <div class="mb-3">
                                <!-- <input type="select" class="form-control" aria-describedby="emailHelp" placeholder="Extension size" id="Ext_size" name="Ext_size" /> -->
                                <select placeholder="Extension size" id="ext_size" name="ext_size">
                                    <?php foreach ($json->Ext_sizes as $ext_size) { ?>
                                        <option value="<?php echo $ext_size ?>">
                                            <?php echo $ext_size ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <p style="color:red" class="error" id="ext_size_error" type="hidden"></p>
                                <?php if (isset($validation)) : ?>
                                    <div style="color:red">
                                        <?= $validation->showError('Ext_size') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-list">
                            <label class="login-label">Unit: </label>
                            <input type="radio" id="is_bundle" name="unit" value="1"> Bundle&nbsp;
                            <input type="radio" id="is_bundle1" name="unit" value="0"> grams
                            <p style="color:red" class="error" id="unit_error" type="hidden"></p>
                        </div>
                        <!-- <div id="bundle-qty-div" style="display: none;"> -->
                        <div class="form-list" id="bundle-count-div" style="display: none;">
                            <label class="login-label">Quantity(Bundles)</label>
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Bundle count" id="bundle_count" name="bundle_count">
                                <p style="color:red" class="error" id="bundle_count_error" type="hidden"></p>
                                <?php if (isset($validation)) : ?>
                                    <div style="color:red">
                                        <?= $validation->showError('count') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-list" id="quantity-div" style="display: none;">
                            <label class="login-label">Weight(gms)</label>
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Quantity" id="quantity" name="quantity">
                                <p style="color:red" class="error" id="quantity_error" type="hidden"></p>
                                <?php if (isset($validation)) : ?>
                                    <div style="color:red">
                                        <?= $validation->showError('Quantity') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- </div> -->
                        <div class="form-list">
                            <label class="login-label">Due date</label>
                            <div class="mb-3">
                                <input type="date" class="form-control" placeholder="Due_date" id="due_date" name="due_date">
                                <p style="color:red" class="error" id="due_date_error" type="hidden"></p>
                                <?php if (isset($validation)) : ?>
                                    <div style="color:red">
                                        <?= $validation->showError('Due_date') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <button id="submitBtn" type="submit" class="order-submit">submit</button>
                    </div>
                </div>
            </form>

            <div class="container">


                <div class="modal fade" id="AlertModal">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">

                            <div class="modal-body" style="padding:40px 50px 20px;">
                                <p id="notification"> </p>

                                <br />
                                <div class="d-grid">
                                    <a id="redirect" class="btn btn-primary edit-sur" href=""> Okay</a>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function() {

        console.log("submit");
        $("#orderForm").submit(function(event) {
            event.preventDefault();
            orderUpdate($(this));
        });

        //order details mapping script
        var editOrderData = <?php if (isset($editOrderData)) {
                                echo json_encode($editOrderData);
                            } else {
                                echo json_encode(array());
                            } ?>;
        mapEditOrderData(editOrderData, "<?php echo base_url('order/editOrder/') ?>");

        //Item Id mapping script
        var $item_ord_Id = <?php if (isset($item_ord_Id)) {
                                echo json_encode($item_ord_Id);
                            } else {
                                echo json_encode(array());
                            } ?>;
        mapItemId($item_ord_Id);



        $("#is_bundle").on('change', function() {
            $("#bundle_count").val("");
            $("#bundle-count-div").show();
            $("#quantity-div").hide();
            $("bundle_count_error").hide();
            $("quantity_error").hide();
            var qty_element = document.getElementById("quantity_error")
            qty_element.style.display = "none";
            var count_element = document.getElementById("bundle_count_error")
            count_element.style.display = "none";
        })
        $("#is_bundle1").on('change', function() {
            $("#bundle-count-div").hide();

            $("#quantity-div").show();
            $('#quantity').prop('readonly', false);
            $('#quantity').val("");
            var element = document.getElementById("quantity_error")
            element.style.display = "none";
            // $("quantity_error").hide();
        })
        $("#bundle_count").keyup(function() {
            // $("#order_list_id_error").text("")
            var length = $("#length").val();
            var count = $(this).val();
            var type = $("#type").val();
            if (count.length > 0) {
                var url = "<?= base_url('order/getWeight') ?>"
                calculateWeight(url, length, count, type);
            }
        });

        $("#length").on("change", function() {

            $("#is_bundle").prop("checked", false);
            $("#is_bundle1").prop("checked", false);
            $("#bundle-count-div").hide();
            $("#quantity-div").hide();
            $("#bundle_count").val("");
            console.log("hi");
        })

        $("#colour").on("change", function() {

            var colour = $(this).val();
            if (colour == "Others") {
                $("#other_colour").val("");
               $("#other_colour").show();
            }
            else{
                $("#other_colour").hide();
            }
            console.log("colour", colour)
        })
    });
</script>
<?= $this->endSection() ?>