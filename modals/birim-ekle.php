<!-- Modal Yapısı: Yeni Birim Ekle -->
<!-- Modal ID'si: modal-birim-ekle -->
<div class="modal modal-blur fade" id="modal-birim-ekle" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!-- Modal Başlığı -->
        <h5 class="modal-title">Yeni Birim Ekle</h5>
        <!-- Kapatma Butonu -->
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Form Başlangıcı -->
        <!-- Form ID'si: form-birim-ekle-modal (JavaScript'te kullanılıyor) -->
        <!-- action işlem dosyasını, method POST olacak -->
        <form id="form-birim-ekle-modal" method="POST" action="/portal/islemler/birim/ekle.php">
            <!-- Kod Input -->
            <div class="mb-3">
                <label class="form-label" for="birim-ekle-kod-modal">Birim Kodu <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="birim-ekle-kod-modal" name="kod" placeholder="Örn: KG" required>
            </div>
            <!-- Adı Input -->
            <div class="mb-3">
                <label class="form-label" for="birim-ekle-adi-modal">Birim Adı <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="birim-ekle-adi-modal" name="adi" placeholder="Örn: Kilogram" required>
            </div>
             <!-- Durum Select -->
             <div class="mb-3">
                <label class="form-label" for="birim-ekle-durum-modal">Durum</label>
                <select class="form-select" id="birim-ekle-durum-modal" name="durum">
                    <option value="1">Aktif</option>
                    <option value="0">Pasif</option>
                </select>
            </div>
            <!-- Diğer form alanları buraya eklenebilir -->

        </form>
        <!-- Form Sonu -->
      </div>
      <div class="modal-footer">
        <!-- Kapatma Butonu -->
        <button type="button" class="btn me-auto" data-bs-dismiss="modal">Kapat</button>
        <!-- Kaydet Butonu (form="form-birim-ekle-modal" ile formu tetikler) -->
        <button type="submit" class="btn btn-primary" form="form-birim-ekle-modal">Kaydet</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal Yapısı Sonu -->