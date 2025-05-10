<?php
session_start();
// config.php dosyasını include edin (islemler/birim/duzenle.php'den 3 dizin yukarıda olabilir)
require_once dirname(__DIR__, 2) . '/config.php';
// Veritabanı sınıfınızın include edildiğinden ve $db objesinin tanımlı olduğundan emin olun.

// Sadece POST isteklerini işle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- Formdan Gelen Veriyi Alma ve Güvenli Hale Getirme ---
    // Düzenlenen birimin ID'sini de alıyoruz
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT); // ID integer olmalı
    $kod = filter_input(INPUT_POST, 'kod', FILTER_UNSAFE_RAW);
    $adi = filter_input(INPUT_POST, 'adi', FILTER_UNSAFE_RAW);
    $durum = filter_input(INPUT_POST, 'durum', FILTER_UNSAFE_RAW);

    // Güvenlik: String alanları temizleyin
    $kod = trim($kod ?? '');
    $adi = trim($adi ?? '');
    $durum = ($durum === '0' || $durum === '1') ? $durum : '1';


    // --- Validasyon ---
    $errors = [];

    if ($id === false || $id <= 0) {
         $errors[] = "Düzenlenecek Birim ID'si eksik veya geçersiz.";
    }
    if (empty($kod)) {
        $errors[] = "Birim Kodu boş bırakılamaz.";
    }
     // İsteğe bağlı: Kodun benzersizliğini kontrol et (kendisi hariç)
     // $existingBirim = $db->Sor("SELECT id FROM birimler WHERE kod = ? AND id != ?", [$kod, $id]);
     // if ($existingBirim) { $errors[] = "Bu Birim Kodu zaten başka bir birimde mevcut."; }

    if (empty($adi)) {
        $errors[] = "Birim Adı boş bırakılamaz.";
    }

    // --- Hatalar Varsa Session Mesajı ve Yönlendirme ---
    if (!empty($errors)) {
        $_SESSION['error_message'] = implode("<br>", $errors);
        // Yönlendirme: Birimler listeleme sayfasına
        header('Location: /portal/birimler'); // Veya hata durumunda düzenleme sayfasına geri yönlendirilebilir
        exit;
    }

    // --- Veritabanında Güncelleme ---
    $veri = [
        'kod' => $kod,
        'adi' => $adi,
        'durum' => $durum
    ];
     // Güncelleme koşulu (WHERE id = ?)
    $kosul = "id = " . (int)$id; // ID'yi integer olarak kullanıyoruz

    try {
        // islemYap metodunu kullanarak güncelleme
        $guncellemeSonuc = $db->islemYap('guncelle', 'birimler', $veri, $kosul);

        if ($guncellemeSonuc) {
             // Başarı mesajı gösterip yönlendirme yap
             $_SESSION['success_message'] = "Birim başarıyla güncellendi.";
             // Yönlendirme: Birimler listeleme sayfasına
             header('Location: /portal/birimler');
             exit;
        } else {
            // Güncelleme başarısız oldu (islemYap false döndürdüyse)
            $_SESSION['error_message'] = "Birim güncellenirken bir hata oluştu (DB İşlem Hatası).";
            header('Location: /portal/birimler');
            exit;
        }

    } catch (Exception $e) {
        // Veritabanı veya islemYap metodundan gelen hatalar (exception fırlattıysa)
        $_SESSION['error_message'] = "Bir hata oluştu: " . $e->getMessage();
        header('Location: /portal/birimler');
        exit;
    }

} else {
    // POST isteği değilse, birimler listeleme sayfasına yönlendir
     header('Location: /portal/birimler');
     exit;
}
?>