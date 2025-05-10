<?php
session_start();
// config.php dosyasını include edin (islemler/birim/ekle.php'den 3 dizin yukarıda olabilir)
require_once dirname(__DIR__, 2) . '/config.php';
// Veritabanı sınıfınızın include edildiğinden ve $db objesinin tanımlı olduğundan emin olun.

// Yanıtın JSON olacağını belirt
header('Content-Type: application/json');

// Sadece POST isteklerini işle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- Formdan Gelen Veriyi Alma ve Güvenli Hale Getirme ---
    $kod = filter_input(INPUT_POST, 'kod', FILTER_UNSAFE_RAW);
    $adi = filter_input(INPUT_POST, 'adi', FILTER_UNSAFE_RAW);
    // Durum '0' veya '1' string olarak gelir, validate int kullanabiliriz veya string olarak alıp kontrol ederiz
    $durum = filter_input(INPUT_POST, 'durum', FILTER_UNSAFE_RAW); // String olarak alalım '0' veya '1'

    // Güvenlik: String alanları temizleyebilirsiniz (trim gibi)
    $kod = trim($kod ?? '');
    $adi = trim($adi ?? '');
    $durum = ($durum === '0' || $durum === '1') ? $durum : '1'; // Geçersiz gelirse varsayılan '1' (Aktif)


    // --- Validasyon ---
    $errors = [];

    if (empty($kod)) {
        $errors[] = "Birim Kodu boş bırakılamaz.";
    }
     // İsteğe bağlı: Kodun benzersizliğini kontrol et
     // $existingBirim = $db->Sor("SELECT id FROM birimler WHERE kod = ?", [$kod]);
     // if ($existingBirim) { $errors[] = "Bu Birim Kodu zaten mevcut."; }

    if (empty($adi)) {
        $errors[] = "Birim Adı boş bırakılamaz.";
    }


    // --- Hatalar Varsa JSON Yanıtı Döndürme ---
    if (!empty($errors)) {
        echo json_encode(['success' => false, 'message' => 'Lütfen form hatalarını düzeltin.', 'errors' => $errors]);
        exit;
    }

    // --- Veritabanına Ekleme ---
    $veri = [
        'kod' => $kod,
        'adi' => $adi,
        'durum' => $durum // Enum '0' veya '1' string olarak kaydedilir
    ];

    try {
        // islemYap metodunu kullanarak ekleme
        // islemYap metodunuzun INSERT sorgusu çalıştırdığından ve parametre bağlama kullandığından emin olun.
        $eklemeSonuc = $db->islemYap('ekle', 'birimler', $veri); // << islemYap metodunu kullan

        if ($eklemeSonuc) {
             // Ekleme başarılı oldu
             // Eklenen birimin ID'sini al (islemYap metodu lastInsertId() döndürebilir veya sonra manuel çağırılabilir)
             // Eğer islemYap true/false döndürüyorsa, ID'yi sonradan çekmek gerekebilir:
             $yeniBirimId = $db->sonEklenenId(); // << sonEklenenId() metodunuzu kullanın

             // Eklenen birimin tam bilgilerini çek (JavaScript'te listeyi güncellemek için)
             $yeniBirim = $db->Sor("SELECT id, kod, adi, durum FROM birimler WHERE id = ?", [$yeniBirimId]);
             $yeniBirim = $yeniBirim ? $yeniBirim[0] : null; // Sonucun ilk elemanını al

             echo json_encode([
                 'success' => true,
                 'message' => 'Birim başarıyla eklendi.',
                 'birim' => $yeniBirim // Eklenen birimin verisini döndür
             ]);
             exit;

        } else {
            // Ekleme başarısız oldu (islemYap false döndürdüyse)
            echo json_encode(['success' => false, 'message' => 'Birim eklenirken bir hata oluştu (DB İşlem Hatası).']);
            exit;
        }

    } catch (Exception $e) {
        // Veritabanı veya islemYap metodundan gelen hatalar (exception fırlattıysa)
         // Hata ayıklama açıksa exception mesajı görünür.
        echo json_encode(['success' => false, 'message' => 'Bir hata oluştu: ' . $e->getMessage()]);
        exit;
    }

} else {
    // POST isteği değilse, JSON hata yanıtı döndür
     echo json_encode(['success' => false, 'message' => 'Geçersiz istek metodu.']);
     exit;
}
?>