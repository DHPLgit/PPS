<?= $this->extend("layouts/app_before") ?>
<?= $this->section("body") ?>
<?php echo script_tag('js/jquery.min.js'); ?>
<?php echo script_tag('js/functions/Script.js'); ?>

<section class="login">
  <div class="container">
    <div class="login-input">
      <div class="row">
        <div class="col-md-6">
          <h2 class="login-head mt-5">Create an Account</h2>
          <!-- <p class="login-head-title">Welcome back! <br />
            Please login
            to your account </p> -->
          <div class="card mt-2">
            <div class="text-center">
            </div>
            <form id="signup" action="<?= base_url('signup')?>" method="get">
            <label class="login-label">First Name</label>
              <div class="mb-3">
                <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="First Name" id="first_name"  name="first_name"/>
                <p style="color:red" class="error" id="first_name_error" type="hidden"></p>
              </div>

              <label class="login-label">Last Name</label>
              <div class="mb-3">
                <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Last Name" id="last_name"  name="last_name"/>
                <p style="color:red" class="error" id="last_name_error" type="hidden"></p>
              </div>

              <label class="login-label">Email Address</label>
              <div class="mb-3">
                <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Email Address" id="mail_id"  name="mail_id"/>
                <p style="color:red" class="error" id="mail_id_error" type="hidden"></p>
              </div>

              
              <!-- <label class="login-label">Phone No:</label>
              <div class="mb-3">
                <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Phone" id="phone"  name="phone"/>
              </div> -->

              <label class="login-label">Create Password</label>
              <div class="mb-3">
                <input type="password" class="form-control" placeholder="Password" id="password" name="password">
                <p style="color:red" class="error" id="password_error" type="hidden"></p>
              </div>

              <label class="login-label">Confirm Password</label>
              <div class="mb-3">
                <input type="password" class="form-control" placeholder="Confirm Password" id="confirm_password" name="confirm_password">
                <p style="color:red" class="error" id="confirm_password_error" type="hidden"></p>
              </div>

              <div id="emailHelp" class="form-text forget"><a href="<?= base_url("/")?>" class=""> Back to Login</a>
              </div>
              <div class="text-center"><button type="submit" class="btn btn-color px-5 w-100">Create An Account</button></div>
            </form>
            &nbsp;

          </div>

        </div>
        <div class="col-md-6">
          <div class="login-banner">
            <img src="<?= base_url() ?>images/banner/login.png" class="img-fluid" />
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script >
    
    $(document).ready(function() {

     signup();
   
    });

    </script>

    <?= $this->endSection() ?>
