<?php
session_start();
// config.php dosyasını include edin (islemler/birim/sil.php'den 3 dizin yukarıda olabilir)
require_once dirname(__DIR__, 2) . '/config.php';
// Veritabanı sınıfınızın include edildiğinden ve $db objesinin tanımlı olduğundan emin olun.

// Sadece POST isteklerini işle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- Formdan Gelen Birim ID'sini Alma ve Güvenli Hale Getirme ---
    // Silinecek birimin ID'si gizli form inputundan gelir
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT); // ID integer olmalı

    // --- Validasyon ---
    $errors = [];

    if ($id === false || $id <= 0) {
         $errors[] = "Silinecek Birim ID'si eksik veya geçersiz.";
    }

    // --- Hatalar Varsa Session Mesajı ve Yönlendirme ---
    if (!empty($errors)) {
        $_SESSION['error_message'] = implode("<br>", $errors);
        // Yönlendirme: Birimler listeleme sayfasına
        header('Location: /portal/birimler');
        exit;
    }

    // --- Veritabanından Silme ---
     // Silme koşulu (WHERE id = ?)
    $kosul = "id = " . (int)$id; // ID'yi integer olarak kullanıyoruz

    try {
        // islemYap metodunu kullanarak silme
        $silmeSonuc = $db->islemYap('sil', 'birimler', null, $kosul); // << islemYap metodunu kullan

        if ($silmeSonuc) {
             // Silme başarılı oldu
             $_SESSION['success_message'] = "Birim başarıyla silindi.";
             // Yönlendirme: Birimler listeleme sayfasına
             header('Location: /portal/birimler');
             exit;
        } else {
            // Silme başarısız oldu (islemYap false döndürdüyse)
            $_SESSION['error_message'] = "Birim silinirken bir hata oluştu (DB İşlem Hatası).";
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