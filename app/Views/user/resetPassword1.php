<?= $this->extend("layouts/app_before"); ?>
<?= $this->section("body") ?>
<div class="row m-3">
    <?php if (session()->getFlashdata('response') !== NULL) : ?>
        <p style="color:green; font-size:18px;" align="center"><?php echo session()->getFlashdata('response'); ?></p>
    <?php endif; ?>

    <!-- <?php if (isset($validation)) : ?>
            <p style="color:red; font-size:18px;" align="center"><?= $validation->showError('validatecheck') ?></p>
            <?php endif; ?>
            <?php if (isset($valid)) : ?>
            <p style="color:red; font-size:18px;" align="center"><?= $valid ?></p>
            <?php endif; ?> -->
    <?php $action = base_url() . "resetpwd/" . $encrptVal . "/" . $randomKey ?>
    <div class="col-md-6 col-sm-12 col-xs-12">
        <form class="form-Centered sign-in" action="<?= base_url($action); ?>" method="post">

            <h5 class="Login-title">Reset Password</h5>
            <div class="mb-4">
                <input type="password" class="form-control input-style" name="password" id="password" placeholder="Enter Password" value="<?php echo set_value('password'); ?>">
                <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('password') ?></div><?php endif; ?>
            </div>
            <div class="mb-4">
                <input type="password" class="form-control input-style" name="confirm_password" id="confirm_password" placeholder="Confirm Password" value="<?php echo set_value('confirm_password'); ?>">
                <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('confirm_password') ?></div><?php endif; ?>
            </div>
            <div class="row m-3">
                <button type="submit" class="btn btn-primary btn-style Centered mb-3">Submit</button>
                <a class="btn btn-primary btn-style Centered" href="<?php echo base_url("/") ?>">Back to Login</a>
            </div>
        </form>
    </div>
    <div class="col-md-6 col-sm-12 col-xs-12">
        <img src="<?= base_url()?>images/banner/login.png" class="img-centered img-fluid" alt="login-image">
    </div>
</div>


<?= $this->endSection() ?>