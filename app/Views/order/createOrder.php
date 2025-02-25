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
                    <div id="ord_gen_btn">
                        <button type="button" onclick="generateOrderId('<?= base_url('order/generateOrderId') ?>',$('#order_id').val())">Generate Id</button>
                    </div>

                </div>
                <div id="item-form" style="display: none;">

                    <div class="form-list">
                        <label class="login-label">Customer</label>
                        <div class="mb-3">
                            <select placeholder="Customer id" id="customer_id" name="customer_id">

                                <?php foreach ($customerList as $customer) { ?>
                                    <option value="<?php echo $customer["customer_id"] ?>">
                                        <?php echo $customer["customer_name"] ?>
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
                    </div>
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
        })
        $("#type").on("change", function() {

            $("#is_bundle").prop("checked", false);
            $("#is_bundle1").prop("checked", false);
            $("#bundle-count-div").hide();
            $("#quantity-div").hide();
            $("#bundle_count").val("");
        })
        $("#colour").on("change", function() {

            var colour = $(this).val();
            if (colour == "Others") {
                $("#other_colour").val("");
                $("#other_colour").show();
            } else {
                $("#other_colour").hide();
            }
            console.log("colour", colour)
        })
    });

    function generateOrderId(url, order_id) {

        if (order_id.length > 0) {
            $("#order_id_error").text("");
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    order_id: order_id
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        console.log("successentry");

                        $("#order_id").prop("readonly", true);
                        $("#item-form").show();

                        $("#ord_gen_btn").hide();
                    } else {
                        $("#order_id_error").text(response.error['order_id'])
                        console.log(response.error);
                        console.log("failure");

                    }
                },
                error: function(response) {
                    console.log(response);
                }

            });
        } else {
            $("#order_id_error").text("Please enter order id.")
        }
    }
</script>
<?= $this->endSection() ?>