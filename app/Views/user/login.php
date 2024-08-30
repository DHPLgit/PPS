<?= $this->extend("layouts/app_before") ?>
<?= $this->section("body") ?>
<?php echo script_tag('js/jquery.min.js'); ?>
<?php echo script_tag('js/functions/Script.js'); ?>

<section class="login">
  <div class="container">
    <?php if (session()->getFlashdata('response') !== NULL) : ?>
      <p style="color:green; font-size:18px;">
        <?php echo session()->getFlashdata('response'); ?>
      </p>
    <?php endif; ?>
    <p id="alert-msg" style="display:none; color:green; font-size:18px; text-align:center"></p>
    <div class="login-input">
      <div class="row">

        <div class="col-md-6" id="login-form">
          <h2 class="login-head mt-5">Login</h2>
          <p class="login-head-title">Welcome back! <br />
            Please login
            to your account </p>
          <div class="card mt-2">
            <form id="log_in" action="<?= base_url('login') ?>" method="post">

              <label class="login-label">Email Address</label>
              <div class="mb-3">
                <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Mail Id" id="mail_id" name="mail_id" />
                <p style="color:red" class="error" id="mail_id_error" type="hidden"></p>
              </div>

              <label class="login-label">Password</label>
              <div class="mb-3">

                <input type="password" class="form-control" placeholder="Password" id="password" name="password">
                <p style="color:red" class="error" id="password_error" type="hidden"></p>
              </div>
              <!-- <div id="emailHelp" class="form-text forget"><a href="<?= base_url("/forgetPassword") ?>" class=""> Forget Password ?</a>
              </div> -->
              <div id="emailHelp" class="form-text forget"><button id="fp" type="button">Forgot Password ?</button>
              </div>
              <div class="text-center"><button type="submit" class="btn btn-color px-5 w-100">Login</button></div>
              &nbsp;
              <div class="text-center"><a class="btn btn-color px-5 w-100" href="<?= base_url("/signup") ?>"> Signup</a></div>
            </form>


          </div>

        </div>
        <div class="col-md-6" id="otp-check-form" style="display: none;">
          <div class="card card-otp mt-2">
            <h2>Enter OTP</h2>
            <form id="otp-form" action="<?= base_url('otpcheck') ?>">
              <input type="hidden" name="user_id" id="user_id">
              <div class="mb-3">
                <label class="login-label">Enter 6 digit otp</label>
                <input class="form-control" type="num" name="otp" id="otp">
              </div>
              <p style="color:red" class="error" id="otp_error"></p>
              <div class="text-center"><button type="submit" class="btn btn-color px-5 w-100">Submit</button></div>
            </form>
          </div>
        </div>
        <div class="col-md-6" id="fp-div" style="display: none;">
        <h2 class="login-head mt-5">Forgot Password</h2>
          <div class="card mt-2">
            <form id="fp-form" action="<?= base_url('forgetPassword') ?>">

              <label class="login-label">Email Address</label>
              <div class="mb-3">
                <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Mail Id" id="fp_mail_id" name="fp_mail_id" />
                <p style="color:red" class="error" id="fp_mail_id_error" type="hidden"></p>
              </div>
              <div class="text-center"><button type="submit" class="btn btn-color px-5 w-100">Submit</button></div>
              <div class="row p-3">
                <a class="bck-to-login" href="<?php echo base_url("/") ?>">Back to Login</a>
              </div>
            </form>
          </div>
        </div>
        <div class="col-md-6">
          <div class="login-banner">
            <img src="<?= base_url() ?>images/banner/login.png" class="img-fluid" />
          </div>
        </div>
      </div>
    </div>
    <div id='loader' style='display:none' class="animate__fadeInDownBig">
      <img src="<?php echo base_url(); ?>images/icons/PPS-Spinner.png"
        class="loader-image animate__animated animate__rotateIn animate__infinite" />
    </div>
  </div>

</section>


<script>
  $(document).ready(function() {

    login();

    $('#fp').on('click', function() {
      $("#login-form").hide();
      $("#fp-div").show();

    });
    $("#fp-form").submit(function(event) {
      event.preventDefault();
      console.log("hi");
      console.log($(this));
      forgetPassword($(this));
    })
    $("#otp-form").submit(function(event) {
      event.preventDefault();

      otpCheck($(this));
    });
  });
</script>

<?= $this->endSection() ?>