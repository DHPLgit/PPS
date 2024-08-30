<?= $this->extend("layouts/app_before") ?>

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
    <div class="col-md-7 col-sm-12 col-xs-12">

    </div>
    <div class="col-md-5 col-sm-12 col-xs-12">
        <!-- <img src="images/login.png"  class="img-centered img-fluid" alt="login-image"> -->
        <form action="<?= base_url('forgetPassword') ?>" method="post">
            <h5 class="Login-title">Forget Password</h5>
            <div class="mb-4">
                <input type="email" class="form-control input-style" name="mail_id" id="mail_id" placeholder="Email Address" value="<?php echo set_value('mail_id'); ?>">
                <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('email') ?></div><?php endif; ?>

            </div>
            <div class="row m-3">
                <button type="submit" class="btn btn-primary btn-style Centered mb-3">Submit</button>
                <a class="btn btn-primary btn-style Centered" href="<?php echo site_url('login'); ?>">Back to Login</a>
            </div>
        </form>
    </div>
</div>



<?= $this->endSection() ?>