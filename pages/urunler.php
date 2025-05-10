<div class="page-wrapper">
  <div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <h2 class="page-title">Ürünler</h2>
        </div>
      </div>
    </div>
  </div>
  <div class="page-body">
    <div class="container-xl">
      <div class="row row-cards">
        <div class="col-lg-12">
          <div class="card card-lg">
            <div class="card-body">
            <table class="table table-vcenter card-table">
                <thead>
                <tr>
                <th>Ürün Kodu</th>
                <th>Ürün Adı</th>
                <th>Açıklama</th>
                <th>Stok</th>
                <th>Fiyat</th>
                <th>Kategori</th>
                <th class="w-1"></th>
                </tr>
                </thead>
                <tbody>
                <?php for ($i = 1; $i <= 10; $i++): ?>
                <tr>
                <td>URUN-<?php echo str_pad($i, 5, '0', STR_PAD_LEFT); ?></td>
                <td>Örnek Ürün <?php echo $i; ?></td>
                <td class="text-muted">
                Bu ürün <?php echo $i; ?> için örnek bir açıklamadır.
                </td>
                <td class="text-muted"><?php echo rand(10, 100); ?> adet</td>
                <td class="text-muted">
                <?php echo number_format(rand(50, 1500), 2, ',', '.'); ?> TL
                </td>
                <td>
                <?php
                  $categories = ['Elektronik', 'Giyim', 'Ev Eşyası', 'Kitap'];
                  echo $categories[array_rand($categories)];
                ?>
                </td>
                <td>
                <a href="#">Düzenle</a>
                </td>
                </tr>
                <?php endfor; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>