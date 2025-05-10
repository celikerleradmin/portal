<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) {
  header('Location: giris');
  exit;
}
?>
<!doctype html>
<html lang="tr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title><?=$SiteName;?></title>
  <link href="<?=$SiteURL;?>dist/css/tabler.css" rel="stylesheet" />
  <link href="<?=$SiteURL;?>dist/css/tabler-themes.css" rel="stylesheet" />
  <link href="<?=$SiteURL;?>dist/css/toastr.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
  <style>
    @import url("https://rsms.me/inter/inter.css");
  </style>
</head>

<body>
  <script src="<?=$SiteURL;?>dist/js/tabler-theme.min.js"></script>
  <div class="page">
    <?php include 'temaparts/header.php'; ?>

    <?php
    $page = 'dashboard';
    if (isset($_GET['page']) && !empty($_GET['page'])) {
      $requested_page = basename($_GET['page']);
      $file_path = "pages/{$requested_page}.php";
      if (file_exists($file_path)) {
        $page = $requested_page;
      }
    }
    include "pages/{$page}.php";
    ?>
    <?php include 'temaparts/footer.php'; ?>
  </div>
  <?php include 'temaparts/settings.php'; ?>
</body>
</html>