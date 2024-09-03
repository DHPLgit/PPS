<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>

<section>
  <div class="container">
    <div id="taskinitialize" action="<?= base_url('') ?>" method="post">
      <div class="orderid">
        <label for="countryInput" class="form-label">Order ID:</label></br>
        <input type="hidden" id="orderListId">
        <input type="hidden" id="ordItemId">
        <input type="text" id="orderidsearch" autocomplete="off" class="form-control" placeholder="Search Order ID" class="taskini-input">
        <button id="clear" type="button" onclick="clearInput()" class="d-none">x</button>
        <p style="color:red" class="error" id="order_list_id_error" type="hidden"></p>
        <div>
          <ul id="autocompleteList" class="d-none">

          </ul>

        </div>
        <div class="row mt-5" id="output-data">
          <h4>Output</h4>
          <div class="col-md-6">
            <div class="orderid">
              <label>Color:</label></br>
              <input type="search" class="taskini-input form-control" id="colour" readonly />
            </div>
            <div class="orderid">
              <label>Texture:</label></br>
              <input type="search" class="taskini-input form-control" id="texture" readonly />
            </div>
          </div>
          <div class="col-md-6">
            <div class="orderid">
              <label>Length:</label></br>
              <input type="search" class="taskini-input form-control" id="length" readonly />
            </div>

            <div class="orderid">
              <label>Quantity(gms):</label></br>
              <input type="search" class="taskini-input form-control" id="quantity" readonly />
            </div>
          </div>

        </div>
        <form id="stock-search" action="<?= base_url('stock/search') ?>" class="mt-5">

          <div class="row">
            <h4>Input</h4>
            <div class="col-md-6">
              <div class="orderid">
                <label for="dropdown">Color:</label>
                <select id="dropdown" class="taskini-input" name="colour">
                  <option value="">Select an option...</option>
                  <?php foreach ($drpdwnData->Colours as $colour) { ?>
                    <option value="<?php echo $colour ?>">
                      <?php echo $colour ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
              <div class="orderid mt-4">
                <label for="dropdown">Length:</label>
                <select id="dropdown" class="taskini-input" name="length">
                  <option value="">Select an option...</option>
                  <?php foreach ($drpdwnData->Length as $length) { ?>
                    <option value="<?php echo $length ?>">
                      <?php echo $length ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-md-6 input_search">
              <div class="orderid ">
                <label for="dropdown">Texture:</label>
                <select id="dropdown" class="taskini-input" name="texture">
                  <option value="">Select an option...</option>
                  <?php foreach ($drpdwnData->Textures as $texture) { ?>
                    <option value="<?php echo $texture ?>">
                      <?php echo $texture ?>
                    </option>
                  <?php } ?>
                </select>
                <div class="inpt-search-btn">
                  <div class="mt-5">
                    <label for=""></label>
                    <button class="btn taskini-searchbtn" type="submit">Search</button>
                  </div>
                </div>
              </div>

            </div>
            <p style="color:red" class="error" id="stock_error" type="hidden"></p>
          </div>
        </form>
        <p style="color:red" class="error" id="AddStock-div-error"></p>
        <div id="AddStock-div" class="d-none">
          <form id="AddStock-form">
            <div class="row mt-5">
              <div class="col-md-6">
                <div class="stockid">
                  <div class="stockselect">
                    <h4>Select Stock</h4>
                    <select id="dropdown-stock" class="taskini-input" name="stock_id">
                      <option value="">Select an option...</option>

                    </select>
                    <p style="color:red" class="error" id="stock_id_error" type="hidden"></p>
                  </div>

                </div>
              </div>
              <div class="col-md-6">
                <label for="quantity">Quantity(gms):</label><br>
                <input type="number" id="quantity" name="quantity" placeholder="Please enter the Quantity "
                  class="taskini-input form-control" step="any">
                <p style="color:red" class="error" id="quantity_error" type="hidden"></p>
                <div class="">
                  <div class="mt-5">
                    <label for=""></label>
                    <button type="submit" class=" btn taskini-searchbtn">Add</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div id="preview"></div>


        <form action="<?= base_url("task/createTask") ?>" id="create-task-form" method="post">
          <input type="hidden" id="stock" name="stock">
          <div class="row mt-5">
            <div class="taskselect">
              <h4>Select Task</h4>
              <select id="dropdown-task" class="taskini-input">
                <?php foreach ($taskDetailList as $taskDetail) { ?>
                  <option value="<?= $taskDetail["task_detail_id"] ?>"><?= $taskDetail["task_name"] ?></option><?php } ?>

              </select>
            </div>

          </div>
          <div class="button-row">
            <button class="btn taskini-btn" id="submit-create-task" type="button">Submit</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</section>

<?php echo script_tag('js/jquery.min.js'); ?>
<?php echo script_tag('js/functions/Script.js'); ?>
<script>
  $("#stock-search").submit(function (event) {
    event.preventDefault();
    searchStocks($(this))
  });
  $("#orderidsearch").keyup(function () {
    $("#order_list_id_error").text("")
    var query = $(this).val();
    var url = "<?= base_url('order/search') ?>"
    searchOrder(url, query);
  });
  $("#AddStock-form").submit(function (event) {
    event.preventDefault();
    console.log($('#AddStock-form').serializeArray());
    saveStockInput($(this))
  });
  $("#submit-create-task").on("click", function () {
    var url = "<?= base_url("task/createTask") ?>";
    createTask(url);
  })
</script>
<?= $this->endSection() ?>