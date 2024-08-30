<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php echo script_tag('js/jquery.min.js'); ?>
<?php echo script_tag('js/functions/Script.js'); ?>
<section class="home">
  <div class="container">
    <?php if (session()->getFlashdata('response') !== NULL) : ?>
      <p style="color:green; font-size:18px;">
        <?php echo session()->getFlashdata('response'); ?>
      </p>
    <?php endif; ?>
    <div class="title-head">
      <div class="search-title dflex-title">
        <form action="<?= base_url("task/list") ?>" method="get">
          <input type="text" placeholder="Enter order and item id" id="query" name="order_item_id" value="<?= isset($orderItemId) ? $orderItemId : ''; ?>">
          <button type="submit" id="search">Search</button>
        </form>
        <button type="button" id="reset"> Reset</button>
      </div>
      <div class="text-center"><a class="crt-sur float-end" href="<?php echo site_url('/task/createTask'); ?>">Create Task</a>
      </div>
    </div>
    <div class="mt-3">
      <?php if (!empty($taskDetailList)) { ?>
        <table class="table mt-6 table-striped table-bordered">
          <thead>
            <tr class="sur-lis-bd">
              <th scope="col" style="display:none;"> Id </th>
              <th scope="col">Task Name</th>
              <th scope="col">Over all</th>
              <th scope="col">To do</th>
              <th scope="col">In progress </th>
              <th scope="col">View </th>
            </tr>
          </thead>
          <tbody>
            <?php $count = 0;
            foreach ($taskDetailList as $taskDetail) {
              $count++; ?>
              <tr id="task-det-row">

                <td style="display:none;">
                  <?php echo stripslashes($taskDetail['task_detail_id']); ?>
                </td>
                <td>
                  <?php echo stripslashes($taskDetail['task_name']); ?>
                </td>
                <td>
                  <?php echo stripslashes($taskDetail['overAllCount']); ?>
                </td>
                <td>
                  <?php echo stripslashes($taskDetail['toDoCount']); ?>
                </td>
                <td>
                  <?php echo stripslashes($taskDetail['inProgressCount']); ?>

                </td>
                <td>
                  <a href="<?= base_url("task/orderList/" . $taskDetail['task_detail_id']) ?> <?php if (isset($orderItemId)) {
                                                                                                echo ("?order_item_id=" . $orderItemId);
                                                                                              } ?>">view</a>
                </td>

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


</section>
<script>
  //  $("#search").on("click", function() {
  //   var query = $("#query").val();
  //   $.ajax({
  //     url: "<?= base_url("task/list") ?>",
  //     type: "get",
  //     data: {
  //       order_item_id: query
  //     },
  //     dataType: "json",
  //     success: function(response) {

  //       if (response.success) {
  //         var data = response.output;
  //         var table_body = document.getElementById('table_body');

  //         if (data.length > 0) {
  //           var table = document.getElementById('order_table');
  //           table_body.innerHTML = "";
  //           var header = true;
  //           var index = 0;
  //           data.forEach(function(item) {

  //             index++;


  //             console.log(item);
  //             var row = table_body.insertRow();
  //             var count = 0;

  //             var s_no = row.insertCell(count);
  //             s_no.innerHTML = index;
  //             for (let key in item) {
  //               count++

  //               console.log(`${key}: ${item[key]}`);
  //               var cell = row.insertCell(count);

  //               if (key == "task_detail_id") {

  //                 cell.style.display = 'none';
  //               }
  //               else {
  //                 cell.innerHTML = item[key];
  //               }

  //             }
  //             var cell = row.insertCell(count);

  //             cell.innerHTML = '<a href="`<?= base_url("task/orderList/") ?> ${item}`">view</a>'
  //               //  var cell2 = row.insertCell(1);
  //             // cell2.innerHTML = item.column2; // Update with new data for column2
  //           });
  //         } else {
  //           table_body.innerHTML = "No matching order found";


  //         }
  //       }

  //     },
  //     error: function(response) {

  //     }
  //   })


  // })

  $("#reset").on("click", function() {

    var currentUrl = window.location.href;
    window.location.href = currentUrl.split('?')[0];
  })
</script>
<?= $this->endSection() ?>