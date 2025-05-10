<?php
session_start();
require_once 'config.php';

if ($_POST) {
  try {
    $kullanici = $_POST['kullanici_adi'] ?? '';
    $sifre = $_POST['sifre'] ?? '';

    if (empty($kullanici) || empty($sifre)) {
      throw new Exception('Kullanıcı adı ve şifre gereklidir!');
    }

    // Kullanıcıyı kontrol et
    $kosul = "username = '$kullanici' AND durum = 1";
    $kullanici_data = $db->islemYap('getir', 'users', null, $kosul);

    if (!$kullanici_data) {
      throw new Exception('Kullanıcı bulunamadı!');
    }

    // Şifreyi kontrol et   
    if (!password_verify($sifre, $kullanici_data[0]['password'])) {
      throw new Exception('Şifre hatalı!');
    }

    // Giriş başarılı
    $_SESSION['user_id'] = $kullanici_data[0]['id'];
    $_SESSION['username'] = $kullanici_data[0]['username'];
    $_SESSION['ad'] = $kullanici_data[0]['ad'];
    $_SESSION['soyad'] = $kullanici_data[0]['soyad'];
    $_SESSION['adsoyad'] = $kullanici_data[0]['ad'] . ' ' . $kullanici_data[0]['soyad'];
    $_SESSION['gorev'] = $kullanici_data[0]['gorev'];
    $_SESSION['email'] = $kullanici_data[0]['email'];
    $_SESSION['yetki'] = $kullanici_data[0]['yetki'];


    // Son giriş tarihini güncelle
    $db->islemYap(
      'guncelle',
      'users',
      ['son_giris' => date('Y-m-d H:i:s')],
      "id = " . $kullanici_data[0]['id']
    );

    // Yönlendirme kontrolü
    if (isset($_SESSION['redirect_url']) && !empty($_SESSION['redirect_url'])) {
      $redirect_url = $_SESSION['redirect_url'];
      unset($_SESSION['redirect_url']);
      header("Location: $SiteURL$redirect_url");
    } else {
      header("Location: {$SiteURL}");
    }
    exit;

  } catch (Exception $e) {
    $_SESSION['mesaj'] = [
      'tur' => 'danger',
      'icerik' => $e->getMessage()
    ];
  }
}
?>

<!doctype html>
<html lang="tr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Giriş - Çelikerler Portal</title>
  <link href="<?=$SiteURL;?>dist/css/tabler.min.css" rel="stylesheet" />
  <link href="<?=$SiteURL;?>dist/css/tabler-themes.min.css" rel="stylesheet" />
  <style>
    @import url("https://rsms.me/inter/inter.css");
  </style>
</head>

<body>
  <script src="<?=$SiteURL;?>dist/js/tabler-theme.js"></script>
  <div class="page page-center">
    <div class="container container-normal py-4">
      <div class="row align-items-center g-4">
        <div class="col-lg">
          <div class="container-tight">
            <div class="text-center mb-4">
              <a href="." class="navbar-brand navbar-brand-autodark"><img src="<?=$SiteURL;?>static/logo_celikerler.svg" alt="Logo" class="navbar-brand-image"></a>
            </div>
            <div class="card card-md">
              <div class="card-body">
                <h2 class="h2 text-center mb-4">Hesabınıza giriş yapın</h2>
                <?php
                // Hata mesajı varsa göster
                if (isset($_SESSION['mesaj'])) {
                  echo '<div class="alert alert-' . $_SESSION['mesaj']['tur'] . '" role="alert">';
                  echo $_SESSION['mesaj']['icerik'];
                  echo '</div>';
                  unset($_SESSION['mesaj']);
                }
                ?>
                <form action="" method="post" autocomplete="off" novalidate>
                  <div class="mb-3">
                    <label class="form-label">Kullanıcı Adı</label>
                    <input type="text" name="kullanici_adi" class="form-control" autocomplete="off" />
                  </div>
                  <div class="mb-2">
                    <label class="form-label">
                      Şifre
                      <span class="form-label-description">
                        <a href="<?=$SiteURL;?>fp"  tabindex="-1">Şifre hatırlat</a>
                      </span>
                    </label>
                    <div class="input-group input-group-flat">
                      <input type="password" name="sifre" class="form-control" autocomplete="off" />
                    </div>
                  </div>
                  <div class="mb-2">
                    <label class="form-check">
                      <input type="checkbox" class="form-check-input" />
                      <span class="form-check-label">Bu cihazı hatırla</span>
                    </label>
                  </div>
                  <div class="form-footer">
                  <button type="submit" class="btn w-100" style="background-color: #762824; color: white;">
                            Giriş Yap
                        </button>
                  </div>
                </form>
              </div>
              <div class="hr-text">yada</div>
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <a href="#" class="btn btn-4 w-100">
                      <img src="<?=$SiteURL;?>static/brands/google.svg" alt="Google logo" class="icon">
                      Google
                    </a>
                  </div>
                  <div class="col">
                    <a href="#" class="btn btn-4 w-100">
                      <img src="<?=$SiteURL;?>static/brands/facebook.svg" alt="Facebook logo" class="icon">
                      Facebook
                    </a>
                  </div>
                </div>
              </div>
            </div>
            <div class="text-center text-secondary mt-3">Henüz hesabınız yok mu? <a href="#" tabindex="-1">Kayıt ol</a>
            </div>
          </div>
        </div>
        <div class="col-lg d-none d-lg-block">
        <img src="<?=$SiteURL;?>static/1.png">
      </div>
      </div>
    </div>
  </div>
  <script src="<?=$SiteURL;?>dist/js/tabler.min.js" defer></script>
</body>

</html>