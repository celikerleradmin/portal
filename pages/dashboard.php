<?php
// *******************************************************************
// BURASI: Veritabanı bağlantınızın yapıldığı yer olmalıdır.
// $db adında bir nesnenizin (sınıf örneğinizin) burada tanımlı ve
// veritabanına bağlı olduğunu varsayıyorum.
// $db->Sor() metodunuzun Prepared Statements'ı desteklediğini ve
// ikinci parametre olarak sorgu parametrelerini (array) aldığını varsayıyorum.
// Ayrıca Sor() metodundaki hata ayıklama (hataAyiklama = true) geçici olarak AÇIK bırakılmıştır
// ki olası yeni hataları görebilelim.
// *******************************************************************

// require_once 'path/to/database/class.php';
// $db = new DatabaseClass(); // Veya mevcut veritabanı nesnenizi buradan alın.

// Hata raporlamayı geçici olarak açmak için (sorunu giderdikten sonra kaldırın veya kapatın)
// error_reporting(E_ALL);
// ini_set('display_errors', 1);


// --- Durumları Veritabanından Çekme ve Sayıları Hesaplama ---
$durum_sayilari = $db->Sor("
SELECT
    d.id,
    d.adi,
    d.slug,
    d.renk_kodu,
    d.ikon,
    COUNT(t.id) as sayi
FROM
    durumlar d
    LEFT JOIN teklifler t ON t.durum = d.id -- Doğru JOIN koşulu
WHERE
    d.durum = 1 -- Aktif durumları filtrele
GROUP BY
    d.id, d.adi, d.renk_kodu, d.ikon, d.sira -- Group By'a d.slug eklenebilir
ORDER BY
    d.sira ASC
");

$cards = [];
if ($durum_sayilari) {
  foreach ($durum_sayilari as $durum) {
    // Her durum için kart bilgilerini hazırla
    $card = [
      'title' => $durum['adi'],
      'slug'=> $durum['slug'],
      'count' => intval($durum['sayi'] ?? 0),
      'color' => $durum['renk_kodu'],
      'icon' => $durum['ikon'],
      'id' => $durum['id'],
      'latest_teklifler' => [] // Son 3 kayıt için boş dizi ekliyoruz
    ];

    // --- Her Durum İçin Son 3 Teklifi Çekme ---
    // Sadece o duruma ait, en son 3 teklifi çek
    $latestSql = "
        SELECT
            t.id,
            t.pk,
            t.musteri,
            t.proje
        FROM
            teklifler t
        WHERE
            t.durum = ? -- Sadece bu duruma ait teklifler
        ORDER BY
            t.id DESC -- En son kayıtlar için id'ye göre azalan sıralama
        LIMIT 3 -- Sadece son 3 kayıt
    ";
    $latestParams = [$card['id']]; // Durum ID'sini parametre olarak ver

    // $db->Sor() metodunun parametre alacak şekilde düzeltildiğinden emin olun!
    $latestTeklifler = $db->Sor($latestSql, $latestParams);

    // Çekilen kayıtları kart verisine ekle
    if (!empty($latestTeklifler)) {
        $card['latest_teklifler'] = $latestTeklifler;
    }

    $cards[] = $card; // Hazırlanan kartı cards dizisine ekle
  }
}
?>

<div class="page-wrapper">
  <div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <div class="page-pretitle">Portal</div>
          <h2 class="page-title">Dashboard</h2>
        </div>
      </div>
    </div>
  </div>
  <div class="page-body">
    <div class="container-xl">
      <div class="row row-deck row-cards">
        <?php foreach ($cards as $card): ?>
          <div class="col-sm-6 col-lg-4">
            <!-- Daha etkileyici bir kart yapısı -->
            <div class="card card-link card-link-pop" onclick="window.location.href='<?= $SiteURL; ?>teklifler/<?= htmlspecialchars($card['slug'] ?? ''); ?>'"> <!-- Kartın tamamını tıklanabilir yapalım -->
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="subheader" style="color: <?= htmlspecialchars($card['color'] ?? ''); ?>">
                      <?= htmlspecialchars($card['title'] ?? ''); ?> <!-- Durum Adı -->
                  </div>
                   <!-- İkon -->
                  <div class="ms-auto lh-1">
                      <span class="avatar avatar-sm" style="background-color: <?= htmlspecialchars($card['color'] ?? ''); ?>; color: var(--tblr-card-bg);">
                          <?php if (!empty($card['icon'])): ?>
                              <i class="<?= htmlspecialchars($card['icon']); ?>"></i>
                          <?php else: ?>
                               <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 11l6 0" /><path d="M9 15l6 0" /></svg>
                          <?php endif; ?>
                      </span>
                  </div>
                </div>
                <!-- Teklif Sayısı -->
                <div class="h1 mb-3">
                  <?= htmlspecialchars($card['count'] ?? 0); ?>
                </div>

                <?php if (!empty($card['latest_teklifler'])): ?>
                 <!-- Kayıtlar için daha düzenli bir liste -->
                 <div class="list-group list-group-flush">
                    <?php foreach ($card['latest_teklifler'] as $latest): ?>
                         <div class="list-group-item px-0"> <!-- List item'ın padding'ini sıfırla -->
                            <!-- Müşteri Adı (Üstte, Kalın) -->
                            <div class="text-reset fw-medium text-truncate mb-1">
                                 <?= htmlspecialchars($latest['musteri'] ?? 'Müşteri Yok'); ?>
                            </div>
                            <!-- Proje Adı (Altta, Küçük, Gri) -->
                            <div class="text-secondary text-truncate small">
                                 <?= htmlspecialchars($latest['proje'] ?? 'Proje Yok'); ?>
                            </div>
                            <!-- İsteğe bağlı: Detay linki (Kartın tamamı tıklanabilir olduğu için gerekmeyebilir) -->
                             <!-- <a href="<?= $SiteURL; ?>teklifler/detay/<?= htmlspecialchars($latest['id'] ?? ''); ?>" class="stretched-link"></a> -->
                        </div>
                    <?php endforeach; ?>
                 </div>
                <?php endif; ?>

                <!-- Tümünü Listele Butonu (Artık kartın kendisi tıklanabilir olduğu için butona gerek kalmayabilir) -->
                 <!-- İsteğe bağlı olarak saklayabilirsiniz -->
                 <!--
                <div class="mt-3">
                  <a href="<?= $SiteURL; ?>teklifler/<?= htmlspecialchars($card['slug'] ?? ''); ?>" class="btn w-100"
                    style="color: <?= htmlspecialchars($card['color'] ?? ''); ?>; border-color: <?= htmlspecialchars($card['color'] ?? ''); ?>; --tblr-btn-color: <?= htmlspecialchars($card['color'] ?? ''); ?>; --tblr-btn-border-color: <?= htmlspecialchars($card['color'] ?? ''); ?>;">
                    Tümünü Listele
                  </a>
                </div>
                -->
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>