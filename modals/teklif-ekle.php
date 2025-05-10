<!-- Modal Yapısı: Yeni Teklif Ekle -->
<!-- Modal ID'si (data-bs-target ile eşleşmeli) -->
<div class="modal modal-blur fade" id="modal-teklif-ekle" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!-- Modal Başlığı -->
        <h5 class="modal-title">Yeni Teklif Ekle</h5>
        <!-- Kapatma Butonu -->
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Form Başlangıcı -->
        <!-- Form ID'si (modal-footer'daki submit butonu için form="form-teklif-ekle") -->
        <!-- action ve method şimdilik placeholders, PHP kısmında güncellenecek -->
        <form id="form-teklif-ekle" method="POST" action="/portal/islemler/teklif/ekle.php">
            <!-- Müşteri Adı Input -->
            <div class="mb-3">
                <label class="form-label" for="teklif-musteri">Müşteri Adı <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="teklif-musteri" name="musteri" placeholder="Müşteri adını giriniz" required>
            </div>
            <!-- Proje Adı Input -->
            <div class="mb-3">
                <label class="form-label" for="teklif-proje">Proje Adı <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="teklif-proje" name="proje" placeholder="Proje adını giriniz" required>
            </div>
             <!-- Durum Select Box -->
             <div class="mb-3">
                <label class="form-label" for="teklif-durum">Durum <span class="text-danger">*</span></label>
                <select class="form-select" id="teklif-durum" name="durum_id" required>
                    <option value="">-- Durum Seçiniz --</option>
                    <!-- Durum seçenekleri buraya PHP ile gelecek -->
                    <option value="1">Maliyet Bekliyor</option> <!-- Örnek Statik Değerler -->
                    <option value="2">Onaylandı</option>
                    <option value="3">Tamamlandı</option>
                    <option value="4">Reddedildi</option>
                </select>
            </div>
            <!-- Satış Temsilcisi Select (Opsiyonel) -->
             <div class="mb-3">
                <label class="form-label" for="teklif-satis-temsilcisi">Satış Temsilcisi</label>
                <select class="form-select" id="teklif-satis-temsilcisi" name="satis_temsilcisi">
                    <option value="">-- Seçiniz --</option>
                     <?php
                     // Satış Temsilcisi seçeneklerini veritabanından doldur
                     // $satisTemsilcileri değişkeninin pages/teklifler.php dosyasında çekildiğini varsayarız.
                     if (isset($satisTemsilcileri) && !empty($satisTemsilcileri)) {
                         foreach ($satisTemsilcileri as $temsilci) {
                              // ad ve soyad sütunlarını birleştiriyoruz
                              $adsoyad = htmlspecialchars(($temsilci['ad'] ?? '') . ' ' . ($temsilci['soyad'] ?? ''));
                              echo '<option value="' . htmlspecialchars($temsilci['id'] ?? '') . '">' . $adsoyad . '</option>';
                         }
                     }
                     ?>
                </select>
            </div>
            <!-- Diğer form alanları (örneğin açıklama, maliyet vb.) buraya eklenebilir -->

        </form>
        <!-- Form Sonu -->
      </div>
      <div class="modal-footer">
        <!-- Kapatma Butonu -->
        <button type="button" class="btn me-auto" data-bs-dismiss="modal">Kapat</button>
        <!-- Kaydet Butonu (form="form-teklif-ekle" ile formu tetikler) -->
        <button type="submit" class="btn btn-primary" form="form-teklif-ekle">Kaydet</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal Yapısı Sonu -->