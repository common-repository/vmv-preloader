<?php
if (!defined('ABSPATH')) {
  exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Demo VMV preloader</title>
  <?php wp_head(); ?>
  <style>
    .vmv_demo_template {
      text-align: center !important;
      margin-top: 20% !important;
    }
  </style>
</head>
<body>
<div>
  <div class="vmv_demo_template">
    <h4>Demo preloader page</h4>
  </div>
  <?php wp_footer(); ?>
</div>
</body>
</html>