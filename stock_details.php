<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>


<section class="view-data">
    <div class="container">
    <h1>Stock details</h1>
        <div class="row">
            <div class="col-md-6">
                <!-- <div class="view-data-details">
                    <p class="title">Stock Id:</p>
                    <p class="data">
                        <?php echo stripslashes($stock['stock_list_id']); ?>
                    </p>
                </div> -->
                <div class="view-data-details">
                    <p class="title">Stock ID:</p>
                    <p class="data">

                        <?php echo stripslashes($stock['stock_id']); ?>
                    </p>
                </div>
                <div class="view-data-details">
                    <p class="title">Color:</p>
                    <p class="data">
                        <?php echo stripslashes($stock['colour']); ?>
                    </p>
                </div>
                <div class="view-data-details">
                    <p class="title">Size:</p>
                    <p class="data">
                        <?php echo stripslashes($stock['length']); ?>
                    </p>
                </div>
                <div class="view-data-details">
                    <p class="title">Texture:</p>
                    <p class="data">
                        <?php echo stripslashes($stock['texture']); ?>
                    </p>
                </div>
                <!-- <div class="view-data-details">
                    <p class="title">Out:</p>
                    <p class="data">
                    <?php echo stripslashes($stock['out']); ?>
                    </p>
                </div> -->
            </div>
            <div class="col-md-6">
                <div class="view-data-details">
                    <p class="title">Unit:</p>
                    <p class="data">
                        <?php echo stripslashes($stock['unit']); ?>
                    </p>
                </div>
                <div class="view-data-details">
                    <p class="title">Quantity:</p>
                    <p class="data">
                        <?php echo stripslashes($stock['quantity']); ?>
                    </p>
                </div>
                <div class="view-data-details">
                    <p class="title">Status:</p>
                    <p class="data">
                        <?php echo stripslashes($stock['status']); ?>
                    </p>
                </div>
                <!-- <div class="view-data-details">
                    <p class="title">Type:</p>
                    <p class="data">
                        <?php echo stripslashes($stock['type']); ?>
                    </p>
                </div> -->
                <div class="view-data-details">
                    <p class="title">Date:</p>
                    <p class="data">
                    <?php echo stripslashes($stock['date']); ?>
                    </p>
                </div>
                <!-- <div class="view-data-details">
                    <p class="title">In:</p>
                    <p class="data">
                    <?php echo stripslashes($stock['in']); ?>
                    </p>
                </div> -->
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>