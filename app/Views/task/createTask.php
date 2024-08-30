<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php echo script_tag('js/jquery.min.js'); ?>
<?php echo script_tag('js/functions/Script.js'); ?>
<div class="col-md-12" style="text-align:center;">
  <form id="orderForm" action="<?= base_url('order/createOrder') ?>" method="post">
    <div class="col-md-4">
      <label>Order id</label>
      <div class="mb-3">
        <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Order id" id="order_id" name="order_id" />

        <p style="color:red" class="error" id="Order_unique_id_error" type="hidden"></p>
        <?php if (isset($validation)) : ?>
          <div style="color:red">
            <?= $validation->showError('Order_unique_id') ?>
          </div>
        <?php endif; ?>
      </div>
      <label>Item id</label>
      <div class="mb-3">

        <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Item id" id="item_id" name="item_id" />

      </div>
      <label>Order List id</label>
      <div class="mb-3">

        <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Item id" id="order_list_id" name="order_list_id" />

      </div>

      <label>Order List id</label>
      <div class="mb-3">

        <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Item id" id="order_list_id" name="order_list_id" />

      </div>

      <div>
        

        <label>Type</label>
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
          <p style="color:red" class="error" id="colour_error" type="hidden"></p>
          <?php if (isset($validation)) : ?>
            <div style="color:red">
              <?= $validation->showError('Colour') ?>
            </div>
          <?php endif; ?>
        </div>

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


        <!-- <label class="login-label">Phone No:</label>
              <div class="mb-3">
                <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Phone" id="phone"  name="phone"/>
              </div> -->

        <label class="login-label">Quantity</label>
        <div class="mb-3">
          <input type="text" class="form-control" placeholder="Quantity" id="quantity" name="quantity">
          <p style="color:red" class="error" id="quantity_error" type="hidden"></p>
          <?php if (isset($validation)) : ?>
            <div style="color:red">
              <?= $validation->showError('Quantity') ?>
            </div>
          <?php endif; ?>
        </div>

        <label class="login-label">Due_date</label>
        <div class="mb-3">
          <input type="date" class="form-control" placeholder="Due_date" id="due_date" name="due_date">
          <p style="color:red" class="error" id="due_date_error" type="hidden"></p>
          <?php if (isset($validation)) : ?>
            <div style="color:red">
              <?= $validation->showError('Due_date') ?>
            </div>
          <?php endif; ?>
        </div>

        <button type="submit">submit</button>
      </div>
    </div>
  </form>

  <div class="container">


    <div class="modal fade" id="AlertModal">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <!-- <div class="modal-header" style="padding:15px 50px;">
          <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
          <h4> Delete Customer</h4>
        </div> -->
          <!-- <form action="<?= base_url('DeleteCustomer') ?>" method="post"> -->
          <div class="modal-body" style="padding:40px 50px 20px;">
            <p id="notification"> </p>

            <br />
            <div class="d-grid">
              <a id="redirect" class="btn btn-primary edit-sur" href=""> Okay</a>
              <!-- <button type="button" href="" class="btn btn-danger confirm pull-right"><span class="fa fa-trash"></span>
                                Confirm</button> -->
              <!-- <button type="button" class="btn btn-outline-secondary Cancel pull-left" data-bs-dismiss="modal"><span class="fa fa-remove"></span> Ok</button> -->
            </div>
          </div>
          <!-- </form> -->


        </div>
      </div>
    </div>

  </div>
</div>

<script>
  $(document).ready(function() {

    console.log("submit");
    $("#orderForm").submit(function(event) {
      event.preventDefault();
      orderUpdate($(this));
    });
    var editOrderData = <?php if (isset($editOrderData)) {
                          echo json_encode($editOrderData);
                        } else {
                          echo json_encode(array());
                        } ?>;
    mapEditOrderData(editOrderData, "<?php echo base_url('order/editOrder/') ?>");
    var $item_ord_Id = <?php if (isset($item_ord_Id)) {
                    echo json_encode($item_ord_Id);
                  } else {
                    echo json_encode(array());
                  } ?>;
    mapItemId($item_ord_Id);
  });
</script>
<?= $this->endSection() ?>