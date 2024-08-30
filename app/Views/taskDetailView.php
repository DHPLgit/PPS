<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>


<section class="view-data">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="view-data-details">
                    <p class="title">Task Name:</p>
                    <p class="data">

                        <?php echo stripslashes($taskDetail['task_name']); ?>
                    </p>
                </div>
                <div class="view-data-details">
                    <p class="title">Department Name:</p>
                    <p class="data">
                        <?php if (isset($taskDetail['dept_name'])) {
                            echo stripslashes($taskDetail['dept_name']);
                        } ?>
                    </p>
                </div>
                <div class="view-data-details">
                    <p class="title">Time Required:</p>
                    <p class="data">
                        <?php echo stripslashes($taskDetail['time_taken']); ?>
                    </p>
                </div>

                <div class="view-data-details">
                    <p class="title">Quality Check:</p>
                    <p class="data">
                        <?php if (isset($taskDetail["qc"])) {
                            foreach ($taskDetail["qc"] as $qc) {
                                echo $qc["qc_name"];
                            } ?>
                        <?php } ?>
                    </p>
                </div>
            </div>

        </div>
        <div class="row mt-5">
            <div class="hair-inspection">
            <?php if (isset($taskDetailList)) {
            foreach ($taskDetailList as $taskDetail1) {  ?>



                <h3><?php if ($taskDetail1['task_detail_id'] == $taskDetail['task_detail_id']) {
                        echo $taskDetail1["task_name"];
                    } else { ?> </h3>
                <?php echo $taskDetail1["task_name"]; ?>
        <?php  }
                } ?>

    <?php }  ?>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>