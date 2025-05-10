<?php
// Bu kısım, veritabanından durum sayılarını çeker ve $cards dizisini hazırlar
// Bu veriye ihtiyacınız varsa bu PHP bloğunu koruyun.
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
    LEFT JOIN teklifler t ON t.durum = d.id AND t.durum = '1'
WHERE
    d.durum = '1'
GROUP BY
    d.id, d.adi, d.renk_kodu, d.ikon, d.sira
ORDER BY
    d.sira ASC
");

$cards = [];
if ($durum_sayilari) {
    foreach ($durum_sayilari as $durum) {
        $cards[] = [
            'title' => $durum['adi'],
            'slug' => $durum['slug'],
            'count' => intval($durum['sayi']),
            'color' => $durum['renk_kodu'],
            'icon' => $durum['ikon'],
            'id' => $durum['id']
        ];
    }
}
?>
<ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link" href="<?= $SiteURL; ?>">
            <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-1">
                    <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                    <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                    <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                </svg></span>
            <span class="nav-link-title"> Dashboard </span>
        </a>
    </li>
    <li class="nav-item dropdown">
        <!-- TEKLİFLER MENÜSÜNÜN ANA LİNKİ GÜNCELLENDİ -->
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"
            data-bs-auto-close="outside" role="button" aria-expanded="false">
            <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-1">
                    <path
                        d="M4 7h3a1 1 0 0 0 1 -1v-1a2 2 0 0 1 4 0v1a1 1 0 0 0 1 1h3a1 1 0 0 1 1 1v3a1 1 0 0 0 1 1h1a2 2 0 0 1 0 4h-1a1 1 0 0 0 -1 1v3a1 1 0 0 1 -1 1h-3a1 1 0 0 1 -1 -1v-1a2 2 0 0 0 -4 0v1a1 1 0 0 1 -1 1h-3a1 1 0 0 1 -1 -1v-3a1 1 0 0 1 1 -1h1a2 2 0 0 0 0 -4h-1a1 1 0 0 1 -1 -1v-3a1 1 0 0 1 1 -1" />
                </svg></span>
            <span class="nav-link-title"> Teklifler </span>
        </a>
        <div class="dropdown-menu">
        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-teklif-ekle"> Yeni </a> <!-- Bu linkin de href'ini '#' olarak bırakabilir veya yeni teklif ekleme sayfasına yönlendirebilirsiniz -->
            <div class="dropdown-divider"></div>

            <?php foreach ($cards as $card): ?>
                <!-- Durum linkleri SiteURL ve slug kullanarak doğru şekilde oluşturuluyor -->
                <a class="dropdown-item" href="<?= $SiteURL; ?>teklifler/<?= $card['slug'] ?>"> <?= $card['title'] ?> </a>
            <?php endforeach; ?>
            <!-- Tümü linki de SiteURL ve temel teklifler yolu kullanılarak doğru şekilde oluşturuluyor -->
            <a class="dropdown-item" href="<?= $SiteURL; ?>teklifler"> Tümü </a>

        </div>
    </li>
    <li class="nav-item dropdown">
        <!-- ÜRÜNLER MENÜSÜNÜN ANA LİNKİ GÜNCELLENDİ -->
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"
            data-bs-auto-close="outside" role="button" aria-expanded="false">
            <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-1">
                    <path
                        d="M4 7h3a1 1 0 0 0 1 -1v-1a2 2 0 0 1 4 0v1a1 1 0 0 0 1 1h3a1 1 0 0 1 1 1v3a1 1 0 0 0 1 1h1a2 2 0 0 1 0 4h-1a1 1 0 0 0 -1 1v3a1 1 0 0 1 -1 1h-3a1 1 0 0 1 -1 -1v-1a2 2 0 0 0 -4 0v1a1 1 0 0 1 -1 1h-3a1 1 0 0 1 -1 -1v-3a1 1 0 0 1 1 -1h1a2 2 0 0 0 0 -4h-1a1 1 0 0 1 -1 -1v-3a1 1 0 0 1 1 -1" />
                </svg></span>
            <span class="nav-link-title"> Ürünler </span>
        </a>
        <div class="dropdown-menu">
        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-urun-ekle"> Yeni </a> <!-- Bu linkin de href'ini '#' olarak bırakabilir veya yeni ürün ekleme sayfasına yönlendirebilirsiniz -->
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="<?= $SiteURL; ?>urunler"> Tüm Ürünler </a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">
            <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-1">
                    <path d="M9 11l3 3l8 -8" />
                    <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" />
                </svg></span>
            <span class="nav-link-title"> Menü Öğesi </span>
        </a>
    </li>
</ul>