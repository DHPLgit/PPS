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
    <div class="text-center"><a class="crt-sur float-end" href="<?php echo site_url('/taskDetail/createTaskDetail'); ?>">Create Task detail</a>
    </div>
    <input type="checkbox" id="toggle">
    <?php if (!empty($taskDetailList)) { ?>

      <div class="mt-5" id="task_table" style="display: block;">
        <table class="table mt-6 table-striped table-bordered">
          <thead>
            <tr class="sur-lis-bd">
              <th scope="col">S.No</th>
              <th scope="col" style="display:none;"> Id </th>
              <th scope="col">Task Name</th>
              <th scope="col">Next Task</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php $count = 0;
            foreach ($taskDetailList as $taskDetail) {
              $count++; ?>
              <tr id="task-det-row">
                <td scope="row">
                  <?php echo $count; ?>
                </td>
                <td style="display:none;">
                  <?php echo stripslashes($taskDetail['task_detail_id']); ?>
                  <button type="button" onclick="generateItemId()"></button>
                </td>
                <td>
                  <?php echo stripslashes($taskDetail['task_name']); ?>
                  <!-- <div id="item-button">
                    <button type="button" class="addItem" >AddItem +</button>
                  </div> -->
                </td>
                <td>
                  <?php
                  // if ($taskDetail['parent_task'] == $taskDetail1["task_detail_id"]) {
                  echo $taskDetail["qa_task_name"];
                  // }
                  ?>

                </td>
                <td class="actions">
                  <form action="<?= base_url('taskDetail/getTaskDetail') ?>" method="post">
                    <input type="text" name="task_detail_id" value="<?php echo stripslashes($taskDetail['task_detail_id']); ?>" hidden>

                    <button class="btn task-list-btn" type="submit">View</button>

                  </form>
                  <!-- <button class="change_prev_task" onclick="addPrevTask(<?= $taskDetail['task_detail_id'] ?>, <?= $taskDetail['parent_task'] ?>)">a</button>
                  <select class="parent-task-sel">
                    <?php foreach ($taskDetailList as $taskDetail2) {
                      if ($taskDetail2["task_detail_id"] != $taskDetail["task_detail_id"]) { ?>
                        <option value="<?= $taskDetail2["task_detail_id"] ?>">
                          <?= $taskDetail2["task_name"] ?>
                        </option>
                    <?php }
                    } ?>
                  </select>
                  <button type="button" class="btn deleteTaskDetail"><img src="<?php echo base_url(); ?>images/icons/remove.png" class="img-centered img-fluid"></button> -->
                </td>
                <!-- <td>
                  <button type="button" class="btn editOrder"> <img src="<?php echo base_url(); ?>images/icons/Create.png" class="img-centered img-fluid"></a>
                    <button type="button" class="btn  deleteOrder"><img src="<?php echo base_url(); ?>images/icons/remove.png" class="img-centered img-fluid"></a>

                </td> -->
                <!-- <td>
            </td> -->
              </tr>

            <?php } ?>
          </tbody>

        </table>
      </div>
    <?php } else { ?>

      <div class="text-center">
        <p class="fs-3"> <span class="text-danger">Oops!</span>No records found.</p>
      </div>


    <?php } ?>

    <?php if (!empty($qaTaskList)) { ?>

      <div class="mt-5" id="qa_table" style="display: none;">
        <table class="table mt-6 table-striped table-bordered">
          <thead>
            <tr class="sur-lis-bd">
              <th scope="col">S.No</th>
              <th scope="col" style="display:none;"> Id </th>
              <th scope="col">Task Name</th>
              <th scope="col">Parent Task</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php $count = 0;
            foreach ($qaTaskList as $qaTask) {
              $count++; ?>
              <tr id="task-det-row">
                <td scope="row">
                  <?php echo $count; ?>
                </td>
                <td style="display:none;">
                  <?php echo stripslashes($qaTask['task_detail_id']); ?>
                  <button type="button" onclick="generateItemId()"></button>
                </td>
                <td>
                  <?php echo stripslashes($qaTask['task_name']); ?>
                  <!-- <div id="item-button">
                    <button type="button" class="addItem" >AddItem +</button>
                  </div> -->
                </td>
                <td>
                  <?php
                  // if ($taskDetail['parent_task'] == $taskDetail1["task_detail_id"]) {
                  echo $qaTask["parent_task_name"];
                  // }
                  ?>

                </td>
                <td class="actions">
                  <form action="<?= base_url('taskDetail/getTaskDetail') ?>" method="post">
                    <input type="text" name="task_detail_id" value="<?php echo stripslashes($qaTask['task_detail_id']); ?>" hidden>

                    <button class="btn task-list-btn" type="submit">View</button>

                  </form>
                  <!-- <button class="change_prev_task" onclick="addPrevTask(<?= $qaTask['task_detail_id'] ?>, <?= $qaTask['parent_task'] ?>)">a</button>
                  <select class="parent-task-sel">
                    <?php foreach ($taskDetailList as $taskDetail2) {
                      if ($taskDetail2["task_detail_id"] != $taskDetail["task_detail_id"]) { ?>
                        <option value="<?= $taskDetail2["task_detail_id"] ?>">
                          <?= $taskDetail2["task_name"] ?>
                        </option>
                    <?php }
                    } ?>
                  </select>
                  <button type="button" class="btn deleteTaskDetail"><img src="<?php echo base_url(); ?>images/icons/remove.png" class="img-centered img-fluid"></button> -->
                </td>
                <!-- <td>
                  <button type="button" class="btn editOrder"> <img src="<?php echo base_url(); ?>images/icons/Create.png" class="img-centered img-fluid"></a>
                    <button type="button" class="btn  deleteOrder"><img src="<?php echo base_url(); ?>images/icons/remove.png" class="img-centered img-fluid"></a>

                </td> -->
                <!-- <td>
            </td> -->
              </tr>

            <?php } ?>
          </tbody>

        </table>
      </div>
    <?php } else { ?>

      <div class="text-center">
        <p class="fs-3"> <span class="text-danger">Oops!</span>No records found.</p>
      </div>


    <?php } ?>
  </div>
  <div class="modal fade" id="prev-task-choose-modal">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:15px 50px;">
          <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
          <h4> Tag as:</h4>
        </div>
        <form action="<?= base_url('taskDetail/updateParentTask') ?>" method="post">
          <div class="modal-body" style="padding:40px 50px 20px;">
            <input type="hidden" id="task_detail_id" name="task_detail_id">
            <?php if (!empty($taskDetailList)) {
              foreach ($taskDetailList as $taskDetail) { ?>
                <div id="div<?= $taskDetail['task_detail_id']; ?>">
                  <input type="radio" id="task<?= $taskDetail['task_detail_id']; ?>" name="prev_task_id" value="<?= $taskDetail['task_detail_id'];  ?>" />
                  <label for="task<?= $taskDetail['task_detail_id']; ?>"><?= $taskDetail['task_name']; ?></label>
                </div>

              <?php } ?>
            <?php } ?>
            <br />
            <div class="d-grid">
              <button type="submit" class="btn btn-danger confirm pull-right"><span class="fa fa-add"></span>
                Confirm</button>
              <button type="button" class="btn btn-outline-secondary Cancel pull-left" data-bs-dismiss="modal"><span class="fa fa-remove"></span> Cancel</button>
            </div>
          </div>
        </form>


      </div>
    </div>
  </div>
  </div>
</section>
<script>
  // $('.view-task-det').on('click', function() {
  //   //getting data of selected row using id.

  //   console.log("ENtry");
  //   $id = document.getElementById('task-det-row');
  //   console.log($id);
  //   $tr = $(this).closest('tr');
  //   var data = $tr.children().map(function() {
  //     return $(this).text();
  //   }).get();
  //   console.log(data[1].trim());
  //   var taskDetId = data[1].trim();
  //   var url = "<?= base_url('taskDetail/getTaskDetail') ?>";
  //   viewTaskDetail(url, taskDetId)

  // });
  $('.deleteTaskDetail').on('click', function() {
    //getting data of selected row using id.

    console.log("ENtry");
    $id = document.getElementById('task-det-row');
    console.log($id);
    $tr = $(this).closest('tr');
    console.log($tr);
    var data = $tr.children().map(function() {
      return $(this).text();
    }).get();
    console.log(data[1].trim());
    var taskDetId = data[1].trim();
    var url = "<?= base_url('taskDetail/deleteTaskDetail') ?>";
    taskDetailDelete(url, taskDetId);
  });
  // Select all elements with class name 'select-options'
  const selectOptions = document.querySelectorAll('.parent-task-sel');
  console.log(selectOptions);
  // Add onchange event listener to each select element
  selectOptions.forEach(select => {
    console.log("select", select)
    console.log("selectnode", select.option)
    select.addEventListener('click', function() {
      console.log('Selected value:', this.value);
      parent_task_id = this.value;
      $tr = $(this).closest('tr');
      var data = $tr.children().map(function() {
        return $(this).text();
      }).get();
      console.log(data[1].trim());
      var taskDetId = data[1].trim();
      var url = "<?= base_url('taskDetail/updateParentTask') ?>";
      //parentTaskUpd(url, parent_task_id, taskDetId);
    });

  });
  // $('.change_prev_task').on('click', function() {
  function addPrevTask(task_detail_id, prev_task_id) {
    $("#prev-task-choose-modal").modal('show');
    $("#task_detail_id").val(task_detail_id);
    console.log("e");
    if (prev_task_id > 0) {
      document.getElementById("task" + prev_task_id).checked = true;
    }
    // Get the element by its ID
    var element = document.getElementById("div" + task_detail_id);

    // Hide the element by setting its display property to "none"
    element.style.display = "none";
  }
  // })

  $("#toggle").on("click", function() {
    if ($("#toggle").prop('checked')) {
      $("#qa_table").show();
      $("#task_table").hide();

    } else {
      $("#qa_table").hide();
      $("#task_table").show();
    }
  })
</script>
<?= $this->endSection() ?>