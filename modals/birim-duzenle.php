<!-- Modal Yapısı: Birim Düzenle -->
<!-- Modal ID'si: modal-birim-duzenle -->
<div class="modal modal-blur fade" id="modal-birim-duzenle" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!-- Modal Başlığı (Düzenlenecek birimin kodu/adı ile dinamik dolacak) -->
        <!-- JavaScript bu başlığı güncelleyecek -->
        <h5 class="modal-title">Birim Düzenle</h5>
        <!-- Kapatma Butonu -->
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Form Başlangıcı -->
        <!-- Form ID'si: form-birim-duzenle-modal (JavaScript'te kullanılıyor) -->
        <!-- action işlem dosyasını, method POST olacak -->
        <form id="form-birim-duzenle-modal" method="POST" action="/portal/islemler/birim/duzenle.php">
            <!-- Gizli Birim ID Alanı -->
            <!-- Bu alana düzenlenecek birimin ID'si JavaScript ile doldurulacak -->
            <input type="hidden" name="id" id="birim-duzenle-id-modal" value="">

            <!-- Kod Input -->
            <div class="mb-3">
                <label class="form-label" for="birim-duzenle-kod-modal">Birim Kodu <span class="text-danger">*</span></label>
                <!-- Input disabled veya readonly yapılabilir eğer kodun düzenlenmesi istenmiyorsa -->
                <input type="text" class="form-control" id="birim-duzenle-kod-modal" name="kod" placeholder="Örn: KG" required>
            </div>
            <!-- Adı Input -->
            <div class="mb-3">
                <label class="form-label" for="birim-duzenle-adi-modal">Birim Adı <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="birim-duzenle-adi-modal" name="adi" placeholder="Örn: Kilogram" required>
            </div>
             <!-- Durum Select -->
             <div class="mb-3">
                <label class="form-label" for="birim-duzenle-durum-modal">Durum</label>
                <select class="form-select" id="birim-duzenle-durum-modal" name="durum">
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
        <!-- Güncelle Butonu (form="form-birim-duzenle-modal" ile formu tetikler) -->
        <button type="submit" class="btn btn-success" form="form-birim-duzenle-modal">Güncelle</button>
         <!-- Sil Butonu (İsteğe bağlı olarak düzenleme modalına da eklenebilir, silme onay modalını tetikler) -->
         <!-- Eğer buraya eklerseniz, JavaScript'te bu buton için de olay dinleyicisi gerekir -->
         <!-- <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-birim-sil-onay">Sil</button> -->
      </div>
    </div>
  </div>
</div>
<!-- Modal Yapısı Sonu -->