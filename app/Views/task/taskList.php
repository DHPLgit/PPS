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
        <button type="button" id="reset">Reset</button>
      </div>
      <div class="text-center">
        <a class="crt-sur float-end" href="<?= site_url('/task/createTask'); ?>">Create Task</a>
      </div>
    </div>

    <div class="mt-3">
      <?php if (!empty($taskDetailList)) { ?>
        <table class="table mt-6 table-striped table-bordered">
          <thead>
            <tr class="sur-lis-bd">
              <th scope="col" style="display:none;">Id</th>
              <th scope="col">Task Name<br />
                <select id="filterTaskName" onchange="filterTable()">
                  <option value="">Filter Task Name</option>
                  <?php foreach ($taskDetailList as $taskDetail) { ?>
                    <option value="<?= stripslashes($taskDetail['task_name']); ?>"><?= stripslashes($taskDetail['task_name']); ?></option>
                  <?php } ?>
                </select>
              </th>
              <th scope="col">Overall<br />
                <select id="filterOverallCount" onchange="filterTable()">
                  <option value="">Filter Overall Count</option>
                  <?php foreach (array_unique(array_column($taskDetailList, 'overAllCount')) as $count) { ?>
                    <option value="<?= $count; ?>"><?= $count; ?></option>
                  <?php } ?>
                </select>
              </th>
              <th scope="col">To do<br />
                <select id="filterToDoCount" onchange="filterTable()">
                  <option value="">Filter To Do Count</option>
                  <?php foreach (array_unique(array_column($taskDetailList, 'toDoCount')) as $count) { ?>
                    <option value="<?= $count; ?>"><?= $count; ?></option>
                  <?php } ?>
                </select>
              </th>
              <th scope="col">In progress<br />
                <select id="filterInProgressCount" onchange="filterTable()">
                  <option value="">Filter In Progress Count</option>
                  <?php foreach (array_unique(array_column($taskDetailList, 'inProgressCount')) as $count) { ?>
                    <option value="<?= $count; ?>"><?= $count; ?></option>
                  <?php } ?>
                </select>
              </th>
              <th scope="col">View</th>
            </tr>
          </thead>
          <tbody id="taskTableBody">
            <?php foreach ($taskDetailList as $taskDetail) { ?>
              <tr>
                <td style="display:none;"><?php echo stripslashes($taskDetail['task_detail_id']); ?></td>
                <td><?php echo stripslashes($taskDetail['task_name']); ?></td>
                <td><?php echo stripslashes($taskDetail['overAllCount']); ?></td>
                <td><?php echo stripslashes($taskDetail['toDoCount']); ?></td>
                <td><?php echo stripslashes($taskDetail['inProgressCount']); ?></td>
                <td>
                  <a class="view-button" href="<?= base_url("task/orderList/" . $taskDetail['task_detail_id']) . (isset($orderItemId) ? "?order_item_id=" . $orderItemId : ''); ?>" style="color: white; background-color: black; padding: 5px 10px; text-decoration: none; border-radius: 3px;">View</a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      <?php } else { ?>
        <div class="text-center">
          <p class="fs-3"><span class="text-danger">Oops!</span> No records found.</p>
        </div>
      <?php } ?>
    </div>
  </div>
</section>

<script>
  function filterTable() {
    const taskNameFilter = document.getElementById('filterTaskName').value.toLowerCase();
    const overallCountFilter = document.getElementById('filterOverallCount').value;
    const todoCountFilter = document.getElementById('filterToDoCount').value;
    const inProgressCountFilter = document.getElementById('filterInProgressCount').value;

    const tableBody = document.getElementById('taskTableBody');
    const rows = tableBody.getElementsByTagName('tr');

    for (let i = 0; i < rows.length; i++) {
      const cells = rows[i].getElementsByTagName('td');

      const taskName = cells[1].textContent.toLowerCase();
      const overallCount = cells[2].textContent;
      const todoCount = cells[3].textContent;
      const inProgressCount = cells[4].textContent;

      const taskNameMatch = taskName.includes(taskNameFilter);
      const overallCountMatch = overallCount.includes(overallCountFilter);
      const todoCountMatch = todoCount.includes(todoCountFilter);
      const inProgressCountMatch = inProgressCount.includes(inProgressCountFilter);

      if (taskNameMatch && 
          (overallCountFilter === "" || overallCountMatch) && 
          (todoCountFilter === "" || todoCountMatch) && 
          (inProgressCountFilter === "" || inProgressCountMatch)) {
        rows[i].style.display = ""; // Show the row
      } else {
        rows[i].style.display = "none"; // Hide the row
      }
    }
  }

  $("#reset").on("click", function() {
    var currentUrl = window.location.href;
    window.location.href = currentUrl.split('?')[0]; // Reset the page to its base URL
  });
</script>
<?= $this->endSection() ?>
