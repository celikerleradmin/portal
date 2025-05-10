<?php
// *******************************************************************
// BURASI: Veritabanı bağlantınızın yapıldığı yer olmalıdır.
// Genellikle index.php'de config.php include ediliyorsa, $db objesi
// zaten tanımlı ve kullanıma hazır olabilir. Eğer değilse, burada
// tanımlandığından emin olun.
//
// ÖNEMLİ: $db nesnenizin ait olduğu sınıfın Sor() metodu,
// hem SQL sorgu stringini hem de sorgu parametreleri için bir diziyi
// ikinci argüman olarak alacak şekilde DÜZELTİLMİŞ olmalıdır.
// Ayrıca Sor() metodundaki hata ayıklama (hataAyiklama = true) geçici olarak AÇIK bırakılmıştır
// ki olası yeni hataları görebilelim.
// *******************************************************************

// require_once 'path/to/database/class.php';
// $db = new DatabaseClass(); // Veya mevcut veritabanı nesnenizi buradan alın.

// Hata raporlamayı geçici olarak açmak için (sorunu giderdikten sonra kaldırın veya kapatın)
// error_reporting(E_ALL);
// ini_set('display_errors', 1);


// --- Konfigürasyon ---
$itemsPerPage = 10; // Sayfa başına gösterilecek kayıt sayısı

// --- URL'den Gelen Filtre Slug'ını Alma (htaccess tarafından eklendi) ---
// Eğer URL /portal/teklifler/slug ise, htaccess bunu $_GET['filter_slug'] olarak ekler.
$urlFilterSlug = filter_input(INPUT_GET, 'filter_slug', FILTER_UNSAFE_RAW); // filter_slug parametresini al

// --- GET Parametrelerini Alma (Arama, Sayfa, Diğer Filtreler) ---
$searchTerm = filter_input(INPUT_GET, 'arama', FILTER_UNSAFE_RAW); // Ham string olarak al
$searchTerm = $searchTerm ?? ''; // null gelirse boş string yap

$currentPage = filter_input(INPUT_GET, 'sayfa', FILTER_VALIDATE_INT);
$currentPage = $currentPage ?: 1; // Varsayılan sayfa 1

// Formdan gelebilecek Durum ID filtresi (Sadece URL'den durum slug'ı gelmiyorsa geçerli)
$getDurumId = null;
if ($urlFilterSlug === null) { // URL'de durum slug'ı yoksa
  $getDurumId = filter_input(INPUT_GET, 'durum_id', FILTER_VALIDATE_INT);
  // Eğer GET'ten gelen durum_id 0 ise (Tümü seçeneği), filtreyi kaldır
  if ($getDurumId === 0) {
    $getDurumId = null;
  }
}

// --- Durumları Veritabanından Çekme (Form Filtresi ve URL Slug Eşleştirme İçin) ---
// Formdaki Durum select box'ını doldurmak ve URL'den gelen slug'ın ID'sini bulmak için
// Güvenlik: Sorguda kullanılan 'durum' sütununun tipi ve içeriği (sayı olması beklenir) önemlidir.
// Durumlar tablosundaki 'durum' sütununu da filtreleyelim (aktif durumlar için)
$durumlar = $db->Sor("SELECT id, adi, slug, renk_kodu FROM durumlar WHERE durum = 1 ORDER BY sira ASC"); // Aktif durumları çek

$urlFilteredDurumId = null;
$urlFilteredDurumAdi = null;
$urlFilteredDurumRenk = null;

if ($urlFilterSlug && $durumlar) {
  foreach ($durumlar as $d) {
    if ($d['slug'] === $urlFilterSlug) {
      $urlFilteredDurumId = $d['id'];
      $urlFilteredDurumAdi = $d['adi'];
      $urlFilteredDurumRenk = $d['renk_kodu'];
      break;
    }
  }
  // Eğer URL'deki slug geçerli bir durum slug'ı değilse
  if ($urlFilteredDurumId === null) {
    // Geçersiz URL filtresi varsa, filtreyi iptal et.
    // Loglama yapmak da iyi bir fikir olabilir
    $urlFilterSlug = null; // Geçersiz slug'ı yok say
  }
}


// --- Veritabanı Sorgusu Oluşturma (Ana Teklifler Sorgusu) ---
$sql = "
    SELECT
        t.id,
        t.pk,
        t.musteri,
        t.proje,
        t.satis_temsilcisi, -- Satış Temsilcisi ID'si
        d.adi AS durum_adi,
        d.slug AS durum_slug,
        d.renk_kodu AS durum_renk_kodu,
        st.ad AS temsilci_ad, -- Satış Temsilcisi Adı
        st.soyad AS temsilci_soyad -- Satış Temsilcisi Soyadı
    FROM
        teklifler t
    LEFT JOIN
        durumlar d ON t.durum = d.id
    LEFT JOIN -- << YENİ JOIN: Satış Temsilcileri tablosu ile
        satis_temsilcileri st ON t.satis_temsilcisi = st.id
    WHERE
        1=1
        AND d.durum = 1 -- Sadece aktif durumdaki teklifleri göster (durumlar tablosundaki aktiflik)
        ";

$params = []; // Sorgu parametreleri

// Durum filtresini ekle (URL slug'ından gelen ID öncelikli)
$filterDurumIdToUse = null;
if ($urlFilteredDurumId !== null) { // URL'den geçerli durum ID'si bulundu (slug eşleşti)
  $filterDurumIdToUse = $urlFilteredDurumId;
} elseif ($getDurumId !== null) { // URL'den durum slug'ı gelmedi, GET'ten durum ID'si geldi
  $filterDurumIdToUse = $getDurumId;
}

if ($filterDurumIdToUse !== null) {
  // Durum ID'si sayısal bir değer olduğu için tırnak içine almayın, Prepared Statements halledecek.
  $sql .= " AND t.durum = ?"; // << KOŞUL EKLENDİ
  $params[] = $filterDurumIdToUse; // << PARAMETRE EKLENDİ
}

// Arama terimini ekle
if ($searchTerm !== '') { // Boş string olmadığından emin olalım
  // Teklif tablosunda arama yapılacak sütunları belirtin
  $sql .= " AND (t.musteri LIKE ? OR t.proje LIKE ?)"; // << KOŞUL EKLENDİ
  $params[] = '%' . $searchTerm . '%'; // << PARAMETRE EKLENDİ
  $params[] = '%' . $searchTerm . '%'; // << PARAMETRE EKLENDİ
}

// --- Sayfalama İçin Toplam Kayıt Sayısını Bulma ---
// Ana sorgu ile aynı JOIN ve WHERE koşullarını kullanırız.
$countSql = "SELECT COUNT(*) AS total_count
             FROM teklifler t
             LEFT JOIN durumlar d ON t.durum = d.id
             WHERE 1=1 AND d.durum = 1"; // Başlangıç koşulları

// COUNT sorgusu için filtre koşullarını ve parametrelerini tekrar oluştur
$countParams = [];
if ($filterDurumIdToUse !== null) {
  $countSql .= " AND t.durum = ?";
  $countParams[] = $filterDurumIdToUse;
}
if ($searchTerm !== '') {
  $countSql .= " AND (t.musteri LIKE ? OR t.proje LIKE ?)";
  $countParams[] = '%' . $searchTerm . '%';
  $countParams[] = '%' . $searchTerm . '%';
}

// COUNT sorgusunu çalıştır
// $db->Sor'un parametreleri ikinci argüman olarak aldığını varsayıyoruz
$totalItemsResult = $db->Sor($countSql, $countParams);
// $db->Sor() false döndürebilir, bu durumda totalItems 0 olmalı.
$totalItems = ($totalItemsResult && isset($totalItemsResult[0]['total_count'])) ? intval($totalItemsResult[0]['total_count']) : 0;


// --- Sayfalama Hesapları ---
$totalPages = ($totalItems > 0) ? ceil($totalItems / $itemsPerPage) : 1; // Kayıt yoksa 1 sayfa
// Geçerli sayfa toplam sayfa sayısından büyükse, son sayfaya git
if ($currentPage > $totalPages) {
  $currentPage = $totalPages;
}
// Geçerli sayfa 1'den küçükse, 1. sayfaya git
if ($currentPage < 1) {
  $currentPage = 1;
}
$offset = ($currentPage - 1) * $itemsPerPage;
// Offset negatif olmamalı (currentPage 1 iken 0 olmalı)
if ($offset < 0)
  $offset = 0;


// --- Ana Teklif Sorgusuna ORDER BY, LIMIT ve OFFSET Ekle ---
$sql .= " ORDER BY t.id DESC"; // Varsayılan sıralama

// Sadece toplam kayıt sayısı 0'dan büyükse LIMIT/OFFSET ekle
// LIMIT ve OFFSET değerlerinin INTEGER olduğundan emin olalım!
if ($totalItems > 0) {
  $sql .= " LIMIT ?, ?"; // << KISIM EKLENDİ
  // Parametreleri INTEGER tipine dönüştürerek ekliyoruz!
  $params[] = (int) $offset; // << PARAMETRELER INTEGER TİPİNE DÖNÜŞTÜRÜLDÜ
  $params[] = (int) $itemsPerPage; // << PARAMETRELER INTEGER TİPİNE DÖNÜŞTÜRÜLDÜ
}


// --- Ana Teklif Sorgusunu Çalıştır ---
// $db->Sor metodunun parametreleri ikinci argüman olarak aldığı varsayılır.
// Eğer totalItems 0 ise LIMIT/OFFSET parametreleri eklenmedi, Sorgu buna uygun çalışmalı.
$teklifler = $db->Sor($sql, $params);


// --- Mevcut URL ve GET Parametrelerini Koruma ---
// Base URL'yi htaccess'in yönlendirdiği ana path olarak başlatıyoruz
$baseUrl = '/portal/teklifler'; // Sizin RewriteBase'inize ve page=teklifler'e göre

// URL'den gelen filter_slug'ı base URL'ye ekle
if ($urlFilterSlug !== null) {
  $baseUrl .= '/' . urlencode($urlFilterSlug); // Sadece slug'ı ekliyoruz
}

// GET parametrelerini al (page, sayfa ve filter_slug hariç)
$currentQueryParams = $_GET;
unset($currentQueryParams['page']); // page parametresini çıkar
unset($currentQueryParams['sayfa']); // sayfa parametresini çıkar
unset($currentQueryParams['filter_slug']); // filter_slug parametresini çıkar

// Eğer URL'den durum slug'ı gelmediyse, GET'teki durum_id'yi koru, ama değeri 0 ise çıkar
if ($urlFilterSlug === null) {
  if (isset($currentQueryParams['durum_id']) && ($currentQueryParams['durum_id'] === '' || $currentQueryParams['durum_id'] === '0' || $currentQueryParams['durum_id'] === 0)) {
    unset($currentQueryParams['durum_id']);
  }
} else {
  // URL'den durum slug'ı geldiyse, GET'teki durum_id'yi mutlaka çıkar
  unset($currentQueryParams['durum_id']);
}

// --- Satış Temsilcilerini Veritabanından Çekme ---
// users tablosu yerine satis_temsilcileri tablosunu kullanıyoruz.
// Durumu 1 olan aktif satış temsilcilerini çekelim (tablo yapısında durum tinyint var).
$satisTemsilcileri = $db->Sor("SELECT id, ad, soyad FROM satis_temsilcileri WHERE durum = 1 ORDER BY ad ASC, soyad ASC"); // Aktifleri çek, alfabetik sırala

// Sorgu stringini oluştur
$queryString = http_build_query($currentQueryParams);
$queryString = $queryString ? '&' . $queryString : ''; // Query string varsa '&' ekle

?>

<!-- SADECE PAGE HEADER VE PAGE BODY KISIMLARI -->
<!-- Bu kod, temaparts/header.php'nin açtığı <div class="page-wrapper">'ın içine dahil edilecek -->

<!-- Page Header -->
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <h2 class="page-title">Teklifler
          <?php
          // URL'den gelen durum filtresini başlığa ekle
          if ($urlFilteredDurumAdi) {
            echo '<small class="text-muted ms-2">';
            echo '(' . htmlspecialchars($urlFilteredDurumAdi) . ')';
            echo '</small>';
          }
          ?>
        </h2>
      </div>
      <!-- pages/teklifler.php içindeki buton -->
      <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
          <!-- data-bs-target modalın ID'si ile eşleşmeli -->
          <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal"
            data-bs-target="#modal-teklif-ekle">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
              stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M12 5l0 14" />
              <path d="M5 12l14 0" />
            </svg>
            Yeni Teklif Ekle
          </a>
          <a href="#" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal"
            data-bs-target="#modal-teklif-ekle">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
              stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M12 5l0 14" />
              <path d="M5 12l14 0" />
            </svg>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Page Body -->
<div class="page-body">
  <div class="container-xl">

    <!-- *** BURAYA TABLO VE FİLTRE İÇERİĞİ GELECEK (CARD İLE BAŞLIYOR) *** -->

    <!-- Direkt olarak card ile başlıyoruz, ekstra row veya col sarmalaması yok -->
    <div class="card">
      <div class="card-body">
        <!-- Arama ve Filtre Formu -->
        <!-- Formun action'ı, mevcut URL filtrelerini koruyacak şekilde ayarlanır -->
        <form action="<?php echo htmlspecialchars($baseUrl); ?>" method="GET" class="row g-3 mb-4">
          <div class="col-md-4 col-lg-3">
            <label for="inputSearch" class="form-label">Arama</label>
            <input type="text" class="form-control" id="inputSearch" name="arama" placeholder="Müşteri, Proje..."
              value="<?php echo htmlspecialchars($searchTerm); ?>">
          </div>

          <!-- Durum Filtresi (Sadece URL'den durum slug'ı gelmiyorsa göster) -->
          <?php if ($urlFilterSlug === null): ?>
            <div class="col-md-3 col-lg-2">
              <label for="filterDurum" class="form-label">Durum</label>
              <select name="durum_id" id="filterDurum" class="form-select">
                <option value="0">Tümü</option> <!-- Value 0, filtreyi kaldırır -->
                <?php if ($durumlar): ?>
                  <?php foreach ($durumlar as $durum): ?>
                    <option value="<?php echo htmlspecialchars($durum['id']); ?>" <?php echo ($getDurumId !== null && $getDurumId == $durum['id']) ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($durum['adi']); ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
          <?php endif; ?>

          <!-- Diğer GET filtrelerini gizli alan olarak taşı (arama, sayfa, filter_slug hariç, durum_id durumu yukarıda yönetildi) -->
          <?php
          // $currentQueryParams zaten page, sayfa, filter_slug ve (URL'den durum varsa) durum_id içermiyor
          foreach ($currentQueryParams as $key => $value):
            // Değerlerin dizi olup olmadığını kontrol et
            if (!is_array($value)):
              ?>
              <input type="hidden" name="<?php echo htmlspecialchars($key); ?>"
                value="<?php echo htmlspecialchars($value); ?>">
              <?php
            endif;
          endforeach;
          ?>

          <div class="col-md-auto align-self-end">
            <button type="submit" class="btn btn-primary">Filtrele</button>
          </div>
          <div class="col-md-auto align-self-end">
            <!-- Temizleme butonu: sadece temel teklifler sayfasına döner -->
            <a href="<?php echo htmlspecialchars('/portal/teklifler'); ?>" class="btn btn-outline-secondary">Temizle</a>
          </div>
        </form>

        <div class="table-responsive">
          <table class="table table-striped table-hover table-vcenter">
            <thead>
              <tr>
                <th>ID</th>
                <th>PK</th>
                <th>Müşteri</th>
                <th>Proje</th>
                <th>Satış Temsilcisi</th>
                <th>Durum</th>
                <th class="w-1"></th> <!-- Boş sütun veya aksiyonlar için -->
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($teklifler)): // $teklifler dizisi boş mu kontrol edin ?>
                <?php foreach ($teklifler as $teklif): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($teklif['id'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($teklif['pk'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($teklif['musteri'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($teklif['proje'] ?? ''); ?></td>
                    <td>
                        <?php
                            // Satış Temsilcisi ad ve soyadını birleştirerek göster
                            $temsilciAdSoyad = htmlspecialchars(($teklif['temsilci_ad'] ?? '') . ' ' . ($teklif['temsilci_soyad'] ?? ''));
                            echo $temsilciAdSoyad ? $temsilciAdSoyad : '-'; // Eğer temsilci yoksa '-' göster
                        ?>
                    </td>
                    <td>
                      <?php
                      // Duruma göre renkli etiket (Tabler Badge)
                      // $teklif['durum_renk_kodu'] veritabanından geldiği için kullanıyoruz.
                      // Renk kodlarının Tabler'ın bg-*-lt sınıflarına uygun olduğundan emin olun.
                      $badgeClass = ($teklif['durum_renk_kodu'] ?? '') ? htmlspecialchars($teklif['durum_renk_kodu']) : 'bg-secondary-lt';
                      $durumAdi = htmlspecialchars($teklif['durum_adi'] ?? 'Bilinmiyor');
                      ?>
                      <span class="badge <?php echo $badgeClass; ?>"><?php echo $durumAdi; ?></span>
                    </td>
                    <td>
                      <!-- Detay sayfasına link, teklif ID'sine göre link oluşturulmalı -->
                      <a href="/portal/teklifler/detay/<?php echo htmlspecialchars($teklif['id'] ?? ''); ?>">Detay</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center">Kayıt bulunamadı.</td> <!-- Sütun sayısını güncelledik -->
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer d-flex align-items-center">
        <?php if ($totalItems > 0): // Kayıt varsa sayfalama bilgilerini göster ?>
          <p class="m-0 text-secondary">Toplam <strong><?php echo $totalItems; ?></strong> kayıt | Sayfa
            <strong><?php echo $currentPage; ?></strong> / <strong><?php echo $totalPages; ?></strong></p>
          <ul class="pagination m-0 ms-auto">
            <li class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
              <a class="page-link"
                href="<?php echo ($currentPage <= 1) ? '#' : htmlspecialchars($baseUrl . '?sayfa=' . ($currentPage - 1) . $queryString); ?>"
                tabindex="-1" aria-disabled="<?php echo ($currentPage <= 1) ? 'true' : 'false'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                  stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M15 6l-6 6l6 6" />
                </svg>
                Önceki
              </a>
            </li>
            <?php
            // Sayfalama linkleri
            $numLinks = 5; // Gösterilecek sayfa linki sayısı
            $startPage = max(1, $currentPage - floor($numLinks / 2));
            $endPage = min($totalPages, $currentPage + floor($numLinks / 2));

            // Kenarlara yakınsa ayarlama
            if ($endPage - $startPage + 1 < $numLinks && $totalPages >= $numLinks) {
              $startPage = max(1, $endPage - $numLinks + 1);
            }
            if ($endPage - $startPage + 1 < $numLinks && $totalPages >= $numLinks) {
              $endPage = min($totalPages, $startPage + $numLinks - 1);
            }

            // İlk sayfa linki ve ...
            if ($startPage > 1) {
              echo '<li class="page-item"><a class="page-link" href="' . htmlspecialchars($baseUrl . '?sayfa=1' . $queryString) . '">1</a></li>';
              if ($startPage > 2)
                echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
            }

            // Sayfa numaraları linkleri
            for ($i = $startPage; $i <= $endPage; $i++):
              ?>
              <li class="page-item <?php echo ($i === $currentPage) ? 'active' : ''; ?>">
                <a class="page-link"
                  href="<?php echo htmlspecialchars($baseUrl . '?sayfa=' . $i . $queryString); ?>"><?php echo $i; ?></a>
              </li>
            <?php endfor; ?>

            <?php
            // Son sayfa linki ve ...
            if ($endPage < $totalPages) {
              if ($endPage < $totalPages - 1)
                echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
              echo '<li class="page-item"><a class="page-link" href="' . htmlspecialchars($baseUrl . '?sayfa=' . $totalPages . $queryString) . '">' . $totalPages . '</a></li>';
            }
            ?>

            <li class="page-item <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
              <a class="page-link"
                href="<?php echo ($currentPage >= $totalPages) ? '#' : htmlspecialchars($baseUrl . '?sayfa=' . ($currentPage + 1) . $queryString); ?>">
                Sonraki
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                  stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M9 6l6 6l-6 6" />
                </svg>
                Önceki
              </a>
            </li>
          </ul>
        <?php endif; ?>
        <?php if ($totalItems == 0 && ($searchTerm || $filterDurumIdToUse !== null)): // Filtre veya arama yapıldı ve sonuç yoksa ?>
          <p class="m-0 text-secondary ms-auto">Toplam <strong>0</strong> kayıt bulundu.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- *** TABLO VE FİLTRE İÇERİĞİ BİTER *** -->

  </div>
</div>