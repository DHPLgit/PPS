<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php echo script_tag('js/jquery.min.js'); ?>
<?php echo script_tag('js/functions/Script.js'); ?>


<section class="upload-files">
        <div class="container">
                <!-- Flash message -->
                <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success" id="flash-success">
                                <?= session()->getFlashdata('success') ?>
                        </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger" id="flash-error">
                                <?= session()->getFlashdata('error') ?>
                        </div>
                <?php endif; ?>
                <!-- Flash message gone in 5 sec -->
                <script>
                        document.addEventListener("DOMContentLoaded", function() {
                                var timeoutDuration = 5000;

                                function hideFlashMessage(id) {
                                        var element = document.getElementById(id);
                                        if (element) {
                                                setTimeout(function() {
                                                        element.style.opacity = '0';
                                                        setTimeout(function() {
                                                                element.style.display = 'none';
                                                        }, 600);
                                                }, timeoutDuration);
                                        }
                                }
                                hideFlashMessage('flash-success');
                                hideFlashMessage('flash-error');
                        });
                </script>

                <div class="card">
                        <h3>Upload Files</h3>
                        <div class="drop_box">
                                <header>
                                        <h4>Select CSV File here</h4>
                                        <!-- Updated text for CSV file selection -->
                                </header>
                                <p>Files Supported: CSV</p>
                                <!-- Updated to include CSV -->
                                <form method="post" class="csvuploadfile" action="<?= base_url('employee/upload') ?>"
                                        enctype="multipart/form-data">
                                        <input type="file" accept=".csv" id="uploadfile"
                                                name="formData">
                                        <!-- Removed 'hidden' attribute -->
                                        <button type="submit" class="btn">Submit</button>
                                </form>
                                <a href="<?php echo base_url(); ?>uploads/customer_template.csv" class="pull-left mb-3"
                                        id="output" download><span class="description">(click here to download CSV
                                                template)</span></a>
                                <?php if (session()->getFlashdata('response') !== NULL): ?>
                                        <p style="color:green; font-size:18px;">
                                                <?php echo session()->getFlashdata('response'); ?>
                                        </p>
                                <?php endif; ?>
                        </div>
                </div>
        </div>
</section>
<section>
        <div class="container">
                <?php if (!empty($emplist)) { ?>
                        <table class="employee-table">
                                <td>
                                        <b>Customer ID</b>
                                </td>
                                <td>
                                        <b>Customer Name</b>
                                </td>

                                <td>
                                        <b>Action</b>
                                </td>
                                <?php $count = 0;
                                foreach ($customerList as $customer) {
                                        $count++; ?>
                                        <tr id="employee-det-row">
                                                <td class="d-none">
                                                        <?php echo stripslashes($customer['customer_id']); ?>
                                                </td>

                                                <td>
                                                        <?php echo stripslashes($customer['name']); ?>
                                                </td>

                                                <td class="actions">
                                                        <button class="btn-view deleteEmployeeDetail" type="buttom">Delete</button>
                                                </td>

                                        </tr>
                                <?php } ?>
                        </table>
                <?php } else { ?>
                        <p>No data found!</p>
                <?php } ?>
        </div>

       

</section>
<script>
        $('.deleteEmployeeDetail').on('click', function() {
                //getting data of selected row using id.

                console.log("ENtry");
                $id = document.getElementById('employee-det-row');
                console.log($id);
                $tr = $(this).closest('tr');
                console.log($tr);
                var data = $tr.children().map(function() {
                        return $(this).text();
                }).get();
                console.log(data[0].trim());
                var taskDetId = data[0].trim();
                var url = "<?= base_url('employee/delete') ?>";
                console.log(url);
                employeeDetailDelete(url, taskDetId);
        });
</script>

<?= $this->endSection() ?>