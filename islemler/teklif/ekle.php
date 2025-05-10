<?php
session_start();
// config.php dosyasını include edin (index.php'ye göre 2 dizin yukarıda olabilir)
require_once dirname(__DIR__, 2) . '/config.php'; 
// Veritabanı sınıfınızın include edildiğinden ve $db objesinin tanımlı olduğundan emin olun
// require_once dirname(__DIR__, 2) . '/class/Database.php'; // Eğer config.php içinde include edilmiyorsa
// $db = new Database(); // Eğer config.php içinde new Database() yapılmıyorsa

// Sadece POST isteklerini işle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- Formdan Gelen Veriyi Alma ve Güvenli Hale Getirme ---
    // filter_input ile veriyi alıyoruz. Deprecated olan FILTER_SANITIZE_STRING yerine
    // string veriler için FILTER_UNSAFE_RAW kullanıp sonra manuel temizlik (isteğe bağlı)
    // veya duruma göre filtreler (email, url vb.) kullanabilirsiniz.
    // Burada temel olarak stringler için raw alıp, sayılar için validate edeceğiz.

    $musteri = filter_input(INPUT_POST, 'musteri', FILTER_UNSAFE_RAW);
    $proje = filter_input(INPUT_POST, 'proje', FILTER_UNSAFE_RAW);
    $durum_id = filter_input(INPUT_POST, 'durum_id', FILTER_VALIDATE_INT); // Sayı olmalı
    $satis_temsilcisi = filter_input(INPUT_POST, 'satis_temsilcisi', FILTER_VALIDATE_INT); // Sayı olmalı

    // Güvenlik: String alanları temizleyebilirsiniz (örneğin trim ile boşlukları kaldırma)
    $musteri = trim($musteri ?? ''); // null gelme ihtimaline karşı ?? ''
    $proje = trim($proje ?? '');

    // --- Validasyon ---
    $errors = []; // Hata mesajlarını tutacak dizi

    if (empty($musteri)) {
        $errors[] = "Müşteri Adı boş bırakılamaz.";
    }
    if (empty($proje)) {
        $errors[] = "Proje Adı boş bırakılamaz.";
    }
    // FILTER_VALIDATE_INT false döndürürse validasyon başarısız olmuştur
    if ($durum_id === false || $durum_id <= 0) { // Durum ID'si gelmediyse veya 0 (Tümü) ise hata ver
        $errors[] = "Lütfen bir durum seçiniz.";
    }
    // Satis temsilcisi zorunlu değilse kontrol etmeye gerek yok. Eğer zorunluysa benzer kontrol ekleyin.
    // if ($satis_temsilcisi === false || $satis_temsilcisi <= 0) { ... }


    // --- Hatalar Varsa Yönlendirme ---
    if (!empty($errors)) {
        // Hata mesajlarını session'a kaydet
        $_SESSION['error_message'] = implode("<br>", $errors);
        // Kullanıcıyı geldiği sayfaya yönlendir (güvenli bir yönlendirme adresi sağlamak daha iyidir)
        // $_SERVER['HTTP_REFERER'] her zaman doğru olmayabilir.
        $referer = $_SERVER['HTTP_REFERER'] ?? $SiteURL . 'teklifler'; // Varsayılan teklifler sayfası
        header('Location: ' . $referer);
        exit; // Yönlendirmeden sonra scripti durdur
    }

    // --- Veritabanına Ekleme ---
    $veri = [
        'pk' => 'TEK-' . time(), // Basit bir benzersiz PK oluşturma (Daha sağlam yöntemler kullanılabilir)
        'musteri' => $musteri,
        'proje' => $proje,
        'durum' => $durum_id, // Doğrudan int olarak kullanılabilir
        'satis_temsilcisi' => $satis_temsilcisi > 0 ? $satis_temsilcisi : null, // 0 gelirse NULL olarak kaydet
        // Diğer sütunlar (oluşturma_tarihi gibi) veritabanında DEFAULT olarak ayarlanmışsa burada eklemeye gerek yok
        // Eğer PHP tarafında eklenecekse buraya ekleyin ve INSERT sorgusunda kullanın
    ];

    try {
        // islemYap metodunu kullanarak ekleme (eğer ekleme case'i varsa)
        // islemYap metodunuzda parametre bağlama olduğunu varsayıyoruz.
        $eklemeSonuc = $db->islemYap('ekle', 'teklifler', $veri); // << islemYap metodunu kullan

        if ($eklemeSonuc) {
             // Başarı mesajı gösterip yönlendirme yap
             $_SESSION['success_message'] = "Teklif başarıyla eklendi.";
             // Teklifler listeleme sayfasına yönlendir
             header('Location: ' . $SiteURL . 'teklifler');
             exit;
        } else {
            // Ekleme başarısız oldu (islemYap false döndürdüyse)
            // islemYap metodunuz hata durumunda exception fırlatmıyorsa buraya düşer.
            $_SESSION['error_message'] = "Teklif eklenirken bir hata oluştu (DB İşlem Hatası).";
             $referer = $_SERVER['HTTP_REFERER'] ?? $SiteURL . 'teklifler';
            header('Location: ' . $referer);
            exit;
        }

    } catch (Exception $e) {
        // Veritabanı veya islemYap metodundan gelen hatalar (exception fırlattıysa)
        $_SESSION['error_message'] = "Bir hata oluştu: " . $e->getMessage();
         $referer = $_SERVER['HTTP_REFERER'] ?? $SiteURL . 'teklifler';
        header('Location: ' . $referer);
        exit;
    }

} else {
    // POST isteği değilse, teklifler listeleme sayfasına yönlendir
     header('Location: ' . $SiteURL . 'teklifler');
     exit;
}
?>