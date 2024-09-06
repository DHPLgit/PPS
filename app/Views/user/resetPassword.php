<?= $this->extend("layouts/app_before"); ?>
<?= $this->section("body") ?>
    <div class="container ">
        <div class="row mt-5 forget-password">
            <?php if (session()->getFlashdata('response') !== NULL): ?>
                <p style="color:green; font-size:18px;" align="center">
                    <?php echo session()->getFlashdata('response'); ?>
                </p>
            <?php endif; ?>

            <!-- <?php if (isset($validation)): ?>
            <p style="color:red; font-size:18px;" align="center"><?= $validation->showError('validatecheck') ?></p>
            <?php endif; ?>
            <?php if (isset($valid)): ?>
            <p style="color:red; font-size:18px;" align="center"><?= $valid ?></p>
            <?php endif; ?> -->
            <?php $action = base_url() . "resetpwd/" . $encrptVal . "/" . $randomKey ?>
            <div class="col-md-6 col-sm-12 col-xs-12 forget-password">
                <h2 class="login-head mt-5 mb-4">Reset Password</h2>
                <form class="form-Centered form-forget sign-in" action="<?= $action; ?>" method="post">


                    <div class="mb-4">
                    <!-- <label class="login-label">Enter Password</label> -->
                        <input type="password" class="form-control input-style" name="password" id="password"
                            placeholder="Enter Password" value="<?php echo set_value('password'); ?>">
                        <?php if (isset($validation)): ?>
                            <div style="color:red">
                                <?= $validation->showError('password') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-4">
                    <!-- <label class="login-label">Confirm Password</label> -->
                        <input type="password" class="form-control input-style" name="confirm_password"
                            id="confirm_password" placeholder="Confirm Password"
                            value="<?php echo set_value('confirm_password'); ?>">
                        <?php if (isset($validation)): ?>
                            <div style="color:red">
                                <?= $validation->showError('confirm_password') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row m-0">
                        <button type="submit" class="btn btn-primary btn-style Centered mb-3">Submit</button>
                        <a class="bck-to-login" href="<?php echo base_url("/") ?>">Back to
                            Login</a>
                    </div>
                </form>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12" style="padding:0px;">
                <div class="login-banner">
                    <img src="<?= base_url() ?>images/banner/login.png" class="img-fluid">
                </div>
            </div>
        </div>
    </div>


<?= $this->endSection() ?>