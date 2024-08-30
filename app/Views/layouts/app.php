<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="canonical" href="https://www.haircompounds.com/">
  <link rel="shortcut icon" href="//www.haircompounds.com/cdn/shop/files/HC_Logo_Small_Cropped_22ae09f4-034f-4fdc-b7de-57fe506b7c8f_32x32.jpg?v=1615319651" type="image/png"> <!----======== CSS ======== -->
  <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
  <link rel="stylesheet" href="<?= base_url('css/front/Mediaquery.css') ?>">
  <!----===== Boxicons CSS ===== -->
  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" />
  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <title>Dashboard Sidebar Menu</title>
</head>

<body class="pps-body" style="">
  <nav class="navbar navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="#"><img src="<?= base_url() ?>images/logo/dhpl-logo-1.png" class="img-fluid" /></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel"><img src="<?= base_url() ?>images/logo/dhpl-logo-1.png" class="img-fluid" /></h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="<?php echo site_url('/task/list'); ?>">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo site_url('/order/orderList'); ?>">Order List</a>
            </li>
            <!-- <li class="nav-item">
              <a class="nav-link" href="<?php echo site_url('/order/createOrder'); ?>">Order Creation</a>
            </li> -->
            <!-- <li class="nav-item">
              <a class="nav-link" href="<?php echo site_url('/task/list'); ?>">Task list</a>
            </li> -->
            <li class="nav-item">
              <a class="nav-link" href="<?php echo site_url('/task/createTask'); ?>">Work order initialization</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo site_url('/taskDetail/list'); ?>">Task Details</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo site_url('/stock/upload'); ?>">Stock Management</a>
            </li>

            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Employee Management
              </a>
              <ul class="dropdown-menu dropdown-menu-dark">
                <li><a class="dropdown-item" href="<?php echo site_url('/employee/upload'); ?>">Employee List</a></li>
                <li><a class="dropdown-item" href="<?php echo site_url('/department/list'); ?>">Department List</a></li>
                <!-- <li>
                  <hr class="dropdown-divider">
                </li> -->
                <!-- <li><a class="dropdown-item" href="#">Something else here</a></li> -->
              </ul>
            </li>
            <li class="nav-item">
              <button class="nav-link" data-bs-target="#InfoModal" data-bs-toggle="modal">How it works</button>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo site_url('/logout'); ?>">Logout</a>
            </li>

          </ul>
          <!-- <form class="d-flex mt-3" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-success" type="submit">Search</button>
        </form> -->
        </div>
      </div>
    </div>
  </nav>
  <div class="container">


    <div class="modal fade" id="InfoModal">
      <div class="modal-dialog">


        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4>How it works?</h4>
            <button type="button" class="close top-close" data-bs-dismiss="modal">&times;</button>
          </div>

          <div class="modal-body" style="padding:40px 50px 20px;">
            <!-- <p>HI  !!</p> -->
            <img class="img-fluid" src="<?= base_url() ?>images/PPS_Workflow.PNG">
          </div>


        </div>
      </div>
    </div>

  </div>
  <?= $this->renderSection("body") ?>

</body>

<?php //echo script_tag('vendor/jquery/jquery.min.js');   
?>
<?php //echo script_tag('vendor/bootstrap/js/bootstrap.bundle.min.js');   
?>
<?php //echo script_tag('vendor/jquery-easing/jquery.easing.min.js');   
?>
<?php //echo script_tag('js/sb-admin.js');   
?>
<?php //echo script_tag('vendor/chart.js/Chart.min.js');   
?>
<?php echo script_tag('js/script.js'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
<!-- Development version -->
<script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>

<!-- Production version -->
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script>
  const button = document.querySelector('#button');
  const tooltip = document.querySelector('#tooltip');

  // Pass the button, the tooltip, and some options, and Popper will do the
  // magic positioning for you:
  Popper.createPopper(button, tooltip, {
    placement: 'right',
  });
</script>


</html>