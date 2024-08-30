<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>


<section class="view-data">
    <div class="container">
    <h1>Employee details</h1>
        <div class="row">
            <div class="col-md-6">
                <div class="view-data-details">
                    <p class="title">Employee Name:</p>
                    <p class="data">
                    
                    <?php echo stripslashes($employee['name']); ?>
                    </p>
                </div>
                <div class="view-data-details">
                    <p class="title">Employee Code:</p>
                    <p class="data">
                    <?php echo stripslashes($employee['emp_code']); ?>
                    </p>
                </div>
                <div class="view-data-details">
                    <p class="title">Employee Phone:</p>
                    <p class="data">
                    <?php echo stripslashes($employee['phone_no']); ?>
                    </p>
                </div>
                <div class="view-data-details">
                    <p class="title">Date of Joining:</p>
                    <p class="data">
                    <?php echo stripslashes($employee['doj']); ?>
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="view-data-details">
                    <p class="title">Date of birth:</p>
                    <p class="data">
                    <?php echo stripslashes($employee['dob']); ?>
                    </p>
                </div>
                <div class="view-data-details">
                    <p class="title">Designation:</p>
                    <p class="data">
                    <?php echo stripslashes($employee['designation']); ?>
                    </p>
                </div>
                <div class="view-data-details">
                    <p class="title">Address:</p>
                    <p class="data">
                    <?php echo stripslashes($employee['address']); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>