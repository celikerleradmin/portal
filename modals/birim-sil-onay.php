<!-- Modal Yapısı: Birim Silme Onayı -->
<!-- Modal ID'si: modal-birim-sil-onay -->
<div class="modal modal-blur fade" id="modal-birim-sil-onay" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-status bg-danger"></div> <!-- Kırmızı durum çubuğu -->
      <div class="modal-body text-center py-4">
        <!-- İkon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.84 2.75" /></svg>
        <!-- Başlık -->
        <h3>Silme İşlemi Onayı</h3>
        <!-- Onay Mesajı (Silinecek birimin adı burada gösterilecek) -->
        <div class="text-secondary">
          "<span id="modal-sil-birim-adi" class="fw-bold"></span>" birimini silmek istediğinize emin misiniz? Bu işlem geri alınamaz.
        </div>
      </div>
      <div class="modal-footer">
        <!-- Vazgeç Butonu -->
        <div class="w-100">
          <div class="row">
            <div class="col">
              <a href="#" class="btn w-100" data-bs-dismiss="modal">Vazgeç</a>
            </div>
            <!-- Silme Onay Butonu -->
            <!-- data-id ile silinecek birimin ID'si JavaScript ile atanacak -->
            <div class="col">
              <a href="#" class="btn btn-danger w-100" id="btn-modal-sil-onay">Sil</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal Yapısı Sonu -->