<?= $this->extend("layouts/app") ?>
<?= $this->section("body") ?>
<?php echo script_tag('js/jquery.min.js'); ?>
<?php echo script_tag('js/functions/Script.js'); ?>
<section class="home">
  <div class="container">
    <?php if (session()->getFlashdata('response') !== NULL): ?>
      <p style="color:green; font-size:18px;">
        <?php echo session()->getFlashdata('response'); ?>
      </p>
    <?php endif; ?>
    <div class="title-head">
      <div class="search-title">
        <!-- <form id="filter_order_form" action="<?= base_url("order/filter") ?>" method="get"> -->
        <input type="text" id="query" name="query">
        <button type="submit" id="search"> Search</button>
      </div>
      <!-- </form> -->
      <div class="text-center"><a class="crt-sur" href="<?php echo site_url('/order/createOrder'); ?>">Create Order</a>
      </div>
    </div>
    <div class="order-list-table">
      <?php if (!empty($orderList)) { ?>
        <table id="order_table" class="table mt-6 table-striped table-bordered">
          <thead>
            <tr class="sur-lis-bd">
              <th scope="col">S.No</th>
              <th scope="col" style="display:none;"> Id </th>
              <th scope="col">Order Id</th>
              <th scope="col">Item number</th>
              <th scope="col">Customer Id</th>
              <th scope="col">Order date</th>
              <th scope="col">Item description</th>
              <th scope="col">Bundle count</th>
              <th scope="col">Quantity</th>
              <th scope="col">Status</th>
              <th scope="col">Due date</th>
              <th scope="col">Action</th>

            </tr>
          </thead>
          <tbody id="table_body">
            <?php $count = 0;
            foreach ($orderList as $order) {
              $count++; ?>
              <tr id="orderRow">
                <td scope="row">
                  <?php echo $count; ?>
                </td>
                <td style="display:none;">
                  <?php echo stripslashes($order['order_list_id']); ?>
                </td>
                <td>
                  <?php echo stripslashes($order['order_id']); ?><br />
                  <div id="item-button">
                    <button type="button" class="addItem" onclick="addItem('<?= $order['order_id'] ?>')">AddItem +</button>
                  </div>
                </td>
                <td>
                  <?php echo stripslashes($order['item_id']); ?>
                </td>
               
                <td>
                  <?php echo stripslashes($order['customer_id']); ?>
                </td>
                <td>
                  <?php echo stripslashes($order['order_date']); ?>
                </td>
                <td>
                  <?php echo $order['type'] . " " . $order['colour'] . " " . $order['length'] . " " . $order['texture'] . " " . $order['ext_size']; ?>
                </td>
                <!-- <td>
              <?php echo stripslashes($order['colour']); ?>  
            </td>
            <td>
              <?php echo stripslashes($order['length']) ?>  
            </td>
            <td>
              <?php echo stripslashes($order['texture']); ?>  
            </td>
            <td>
              <?php echo stripslashes($order['ext_size']); ?>  
            </td> -->
                <td>
                  <?php echo stripslashes($order['bundle_count']) ?>
                </td>
                <td>
                  <?php echo stripslashes($order['quantity']) ?>
                </td>
                <td>
                  <?php echo stripslashes($order['status']); ?>
                </td>
                <td>
                  <?php echo stripslashes($order['due_date']); ?>
                </td>
                <td class="action">

                  <button type="button" <?php if ($order['status'] != "Not started") {
                    echo 'style="pointer-events:none"';
                  } ?>
                    class="btn editOrder"> <img src="<?php echo base_url(); ?>images/icons/Create.png"
                      class="img-centered img-fluid"></a>
                    <button type="button" <?php if ($order['status'] != "Not started") {
                      echo 'style="pointer-events:none"';
                    } ?>class="btn  deleteOrder"><img
                        src="<?php echo base_url(); ?>images/icons/remove.png" class="img-centered img-fluid"></a>
                </td>
                <!-- <td>
            </td> -->
              </tr>

            <?php } ?>
          </tbody>

        </table>
      <?php } else { ?>
      </div>
      <div class="text-center">
        <p class="fs-3"> <span class="text-danger">Oops!</span>No records found.</p>
      </div>
    <?php } ?>
  </div>
  <div class="container">
    <div class="modal fade" id="orderDeleteModal">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header" style="padding:15px 50px;">
            <h4> Delete order</h4>
            <button type="button" class="close top-close" data-bs-dismiss="modal">&times;</button>

          </div>
          <form action="<?= base_url('order/deleteOrder') ?>" class="form" id="DeleteSegmentForm" name="DeleteSegment"
            method="post">
            <div class="modal-body ctr-segment-body" style="padding:20px;">
              <p> Are you sure you want to delete the order?</p>
              <div class="form-group">
                <input type="hidden" class="form-control" id="orderListId" name="orderListId">
              </div>
              <br />
              <div class="d-grid">
                <button type="submit" class="btn btn-danger confirm pull-right"><span class="fa fa-trash"></span>
                  Confirm</button>
                <button type="button" class="btn btn-outline-secondary Cancel pull-left close"
                  data-bs-dismiss="modal"><span class="fa fa-remove"></span> Cancel</button>
              </div>
            </div>
          </form>


        </div>
      </div>
    </div>

  </div>
</section>
<script>
  $(document).ready(function () {
    window.onpopstate = function (event) {
      // Reset or empty the input element
      $('#query').val('');
    };
  });
  $("#search").on("click", function () {

    // const searchInput = document.getElementById('query');
    // const dataTable = document.getElementById('order_table');
    // const rows = dataTable.getElementsByTagName('tr');

    // //searchInput.addEventListener('keyup', function() {
    // const filter = searchInput.value.toUpperCase();

    // for (let i = 1; i < rows.length; i++) {
    //   // const name = rows[i].getElementsByTagName('td')[2].innerText;
    //   // console.log("n", name);
    //   var parentElement = rows[i].getElementsByTagName('td')[2];
    //  // console.log("p", parentElement);
    //   var parentText = parentElement.cloneNode(true);
    //    parentText.removeChild(parentText.firstElementChild);
    //   // console.log("pr", parentText);
    //    var orderId = parentText.innerText.trim();
    //    var referenceId=rows[i].getElementsByTagName('td')[4].innerText;
    //    //console.log("orderId", orderId, "referenceId", referenceId);
    //   if (orderId.includes(filter) || referenceId.includes(filter)) {
    //     rows[i].style.display = '';
    //   } else {
    //     rows[i].style.display = 'none';
    //   }
    // }
    // });
    var query = $("#query").val();
    // if (query.length > 0) {
    $.ajax({
      url: "<?= base_url("order/filter") ?>",
      type: "get",
      data: {
        query: query
      },
      dataType: "json",
      success: function (response) {

        if (response.success) {
          var data = response.output;
          var table_body = document.getElementById('table_body');

          if (data.length > 0) {
            var table = document.getElementById('order_table');
            table_body.innerHTML = "";
            //  table.innerHTML = ''; // Clear existing table data
            var header = true;
            var index = 0;
            data.forEach(function (item) {

              index++;

              // if(header){
              //   var headerRow = table.insertRow();
              //   var cell = headerRow.insertCell(count);
              //   cell.innerHTML = item[key];
              //   header=false;
              // }
              console.log(item);
              var row = table_body.insertRow();
              var count = 0;

              var s_no = row.insertCell(count);
              s_no.innerHTML = index;
              for (let key in item) {
                count++

                console.log(`${key}: ${item[key]}`);
                var cell = row.insertCell(count);

                if (key == "order_list_id") {

                  cell.style.display = 'none';
                }
                if (key == "order_id") {
                  cell.innerHTML = item[key] + '<div id="item-button"><button type="button" class="addItem" onclick="addItem('+item[key]+')">AddItem +</button></div>'; // Update with new data
                } else {
                  cell.innerHTML = item[key];
                }

              }
              var cell = row.insertCell(count + 1);

              cell.innerHTML = '<button type="button" class="btn editOrder"> <img src="<?php echo base_url(); ?>images/icons/Create.png" class="img-centered img-fluid"></a><button type="button" class="btn  deleteOrder"><img src="<?php echo base_url(); ?>images/icons/remove.png" class="img-centered img-fluid"></a>';
              //  var cell2 = row.insertCell(1);
              // cell2.innerHTML = item.column2; // Update with new data for column2
            });
          } else {
            table_body.innerHTML = "No matching order found";
          }
          $('.editOrder').on('click', function () {
            //getting data of selected row using id.

            console.log("ENtry");
            // $id = document.getElementById('orderRow');
            // console.log($id);
            $tr = $(this).closest('tr');
            var data = $tr.children().map(function () {
              return $(this).text();
            }).get();
            console.log(data[1].trim());
            var orderId = data[1].trim();
            var url = "<?= base_url('order/editOrder/') ?>" + orderId;
            window.location.href = url;

          });
          $('.deleteOrder').on('click', function () {
            //getting data of selected row using id.
            console.log("ENtry");
            // $id = document.getElementById('orderRow');
            // console.log($id);
            $tr = $(this).closest('tr');
            var data = $tr.children().map(function () {
              return $(this).text();
            }).get();
            console.log(data[1].trim());
            var orderId = data[1].trim();
            $('#orderListId').val(orderId);
            $("#orderDeleteModal").modal('show');

            // var url = "<?= base_url('order/deleteOrder') ?>";



            //orderDelete(url, orderId);
          });

          // $('.addItem').on('click', function () {
          //   //getting data of selected row using id.

          //   console.log("ENtry");
          //   // $id = document.getElementById('orderRow');
          //   // console.log($id);
          //   $tr = $(this).closest('tr');
          //   var data = $tr.children().map(function () {
          //     return $(this).text();
          //   }).get();
          //   console.log(data[2].trim());
          //   var orderId = data[2].trim();
          //   var url = "<?= base_url('order/generateItemId/') ?>" + orderId;;
          //   window.location.href = url;
          // });
        }

      },
      error: function (response) {

      }
    })
    // }
  })
  $('.editOrder').on('click', function () {
    //getting data of selected row using id.

    console.log("ENtry");
    // $id = document.getElementById('orderRow');
    // console.log($id);
    $tr = $(this).closest('tr');
    var data = $tr.children().map(function () {
      return $(this).text();
    }).get();
    console.log(data[1].trim());
    var orderId = data[1].trim();
    var url = "<?= base_url('order/editOrder/') ?>" + orderId;
    window.location.href = url;

  });

  $('.deleteOrder').on('click', function () {
    //getting data of selected row using id.
    console.log("ENtry");
    // $id = document.getElementById('orderRow');
    // console.log($id);
    $tr = $(this).closest('tr');
    var data = $tr.children().map(function () {
      return $(this).text();
    }).get();
    console.log(data[1].trim());
    var orderId = data[1].trim();
    $('#orderListId').val(orderId);
    $("#orderDeleteModal").modal('show');

    // var url = "<?= base_url('order/deleteOrder') ?>";



    //orderDelete(url, orderId);
  });


  // $('.addItem').on('click', function () {
  //   //getting data of selected row using id.

  //   console.log("ENtry");
  //   $id = document.getElementById('orderRow');
  //   console.log($id);
  //   $tr = $(this).closest('tr');
  //   var data = $tr.children().map(function () {
  //     return $(this).text();
  //   }).get();
  //   console.log(data[2].trim());
  //   var orderId = data[2].trim();
  //   var url = "<?= base_url('order/generateItemId/') ?>" + orderId;;
  //   window.location.href = url;
  // });

  function addItem(orderId){
    var url = "<?= base_url('order/generateItemId/') ?>" + orderId;;
    window.location.href = url;
  }
</script>
<?= $this->endSection() ?>