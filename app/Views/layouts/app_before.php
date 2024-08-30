<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="canonical" href="https://www.haircompounds.com/">
  <link rel="shortcut icon"
    href="//www.haircompounds.com/cdn/shop/files/HC_Logo_Small_Cropped_22ae09f4-034f-4fdc-b7de-57fe506b7c8f_32x32.jpg?v=1615319651"
    type="image/png">
  <link rel="stylesheet" href="<?= base_url('css/front/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
  <link rel="stylesheet" href="<?= base_url('css/responsive.css') ?>">
  <link rel="stylesheet" href="<?= base_url('css/front/Mediaquery.css') ?>">
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" /> -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <title>PPS Login page</title>
</head>

<body class="login-body">
  <section class="login-main-page">
    <?= $this->renderSection("body") ?>
  </section>
</body>

<?php echo script_tag('vendor/jquery/jquery.min.js'); ?>
<?php echo script_tag('vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>
<?php echo script_tag('vendor/jquery-easing/jquery.easing.min.js'); ?>
<?php echo script_tag('js/script.js'); ?>
<!-- Development version -->
<script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>

<!-- Production version -->
<script src="https://unpkg.com/@popperjs/core@2"></script>

</html>