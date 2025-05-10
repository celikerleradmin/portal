<?php
// Veritabanı bağlantısı ve $db objesi index.php tarafından sağlanıyor

// Birimleri veritabanından çekme
$sql = "SELECT id, kod, adi, durum FROM birimler ORDER BY id ASC";
$params = [];
$birimler = $db->Sor($sql, $params);
if ($birimler === false || $birimler === null) {
     $birimler = [];
}
?>

<!-- Tabler Sayfa Yapısı Başlangıcı -->
<!-- Bu kod, index.php'nin include ettiği pages/{$page}.php dosyası için içeriktir -->
<!-- temaparts/header.php'de <div class="page-wrapper">'ın açıldığını varsayar -->

  <!-- Page Header -->
  <div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <div class="page-pretitle">Yönetim</div>
          <h2 class="page-title">Birimler</h2>
        </div>
        <!-- Yeni Birim Ekleme Butonu (Şimdilik modal tetiklemeyecek) -->
         <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <!-- data-bs-target Birim Ekle modalının ID'si ile eşleşmeli -->
                <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-birim-ekle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                    Yeni Birim Ekle
                </a>
                 <a href="#" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal" data-bs-target="#modal-birim-ekle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                 </a>
            </div>
         </div>
      </div>
    </div>
  </div>

  <!-- Page Body -->
  <div class="page-body">
    <div class="container-xl">

      <!-- Arama/Filtre Formu Buraya Gelebilir (Şimdilik Atlıyoruz) -->
      <!--
      <div class="card mb-3">
        <div class="card-body">
          <form action="#" method="GET" class="row g-3">
            <div class="col-md-4">
              <label for="inputSearch" class="form-label">Arama</label>
              <input type="text" class="form-control" id="inputSearch" name="arama" placeholder="Kod, Adı..." value="">
            </div>
            <div class="col-md-3">
               <label for="filterDurum" class="form-label">Durum</label>
                <select name="durum" id="filterDurum" class="form-select">
                    <option value="">Tümü</option>
                    <option value="1">Aktif</option>
                    <option value="0">Pasif</option>
                </select>
            </div>
            <div class="col-md-auto align-self-end">
                <button type="submit" class="btn btn-primary">Filtrele</button>
            </div>
             <div class="col-md-auto align-self-end">
                <a href="/portal/birimler" class="btn btn-outline-secondary">Temizle</a>
             </div>
          </form>
        </div>
      </div>
      -->

      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-hover table-vcenter">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Kod</th>
                  <th>Adı</th>
                  <th>Durum</th>
                  <th class="w-1"></th> <!-- Aksiyonlar için sütun -->
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($birimler)): ?>
                <?php foreach ($birimler as $birim): ?>
                <tr>
                  <td><?php echo htmlspecialchars($birim['id'] ?? ''); ?></td>
                  <td><?php echo htmlspecialchars($birim['kod'] ?? ''); ?></td>
                  <td><?php echo htmlspecialchars($birim['adi'] ?? ''); ?></td>
                  <td>
                    <?php
                        $durum_value = $birim['durum'] ?? '0';
                        $durum_text = ($durum_value === '1') ? 'Aktif' : 'Pasif';
                        $badgeClass = ($durum_value === '1') ? 'bg-green-lt' : 'bg-red-lt';
                    ?>
                    <span class="badge <?php echo $badgeClass; ?>"><?php echo $durum_text; ?></span>
                  </td>
                  <td>
                    <div class="btn-list flex-nowrap">
                        <!-- Düzenle Butonu (Birim Düzenle Modalını Tetikleyecek) -->
                        <!-- data-bs-target Birim Düzenle modalının ID'si ile eşleşmeli -->
                        <!-- data-* öznitelikleri ile birim bilgilerini modala taşıyacağız -->
                        <a href="#" class="btn btn-icon btn-sm" aria-label="Düzenle"
                            data-bs-toggle="modal" data-bs-target="#modal-birim-duzenle"
                            data-id="<?php echo htmlspecialchars($birim['id'] ?? ''); ?>"
                            data-kod="<?php echo htmlspecialchars($birim['kod'] ?? ''); ?>"
                            data-adi="<?php echo htmlspecialchars($birim['adi'] ?? ''); ?>"
                            data-durum="<?php echo htmlspecialchars($birim['durum'] ?? '0'); ?>"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                        </a>
                        <!-- Sil Butonu (Birim Silme Onay Modalını Tetikleyecek) -->
                         <!-- data-bs-target Birim Sil modalının ID'si ile eşleşmeli -->
                         <!-- data-* öznitelikleri ile birim bilgilerini modala taşıyacağız -->
                        <a href="#" class="btn btn-icon btn-sm text-danger btn-birim-sil" aria-label="Sil"
                             data-bs-toggle="modal" data-bs-target="#modal-birim-sil-onay"
                             data-id="<?php echo htmlspecialchars($birim['id'] ?? ''); ?>"
                             data-adi="<?php echo htmlspecialchars($birim['adi'] ?? ''); ?>"
                             >
                             <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                        </a>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                  <td colspan="5" class="text-center">Kayıt bulunamadı.</td>
                </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
         <!-- Sayfalama Buraya Gelebilir -->
         <!-- Şimdilik atlıyoruz -->
        <div class="card-footer d-flex align-items-center">
          <p class="m-0 text-secondary">Toplam <strong><?php echo count($birimler); ?></strong> kayıt</p>
        </div>
      </div>

    </div>
  </div>

<!-- Modalları Dahil Et -->
<?php include 'modals/birim-ekle.php'; ?>
<?php include 'modals/birim-duzenle.php'; ?>
<?php include 'modals/birim-sil-onay.php'; ?>

<!-- JavaScript Kısmı (Modal Etkileşimi ve POST Yönlendirme) -->
<!-- index.php'de ana JS dosyanızın yüklendiği yerden sonra bu script çalışmalı -->
<!-- Bu script pages/birimler.php dosyasına özgü olacaktır -->
<script>
document.addEventListener('DOMContentLoaded', function() {

    // --- Modal Elementleri ---
    const addModalElement = document.getElementById('modal-birim-ekle');
    const editModalElement = document.getElementById('modal-birim-duzenle');
    const deleteConfirmModalElement = document.getElementById('modal-birim-sil-onay');

    // --- Form Elementleri (Modal İçindeki) ---
    // Ekleme Modalı Formu
    // Modalınızdaki formun ve inputların doğru ID'lerine işaret ettiğinden emin olun!
    const addForm = document.getElementById('form-birim-ekle-modal');
    // Input elementleri için referanslara gerek yok, form.reset() yeterli
    // Eğer modal açıldığında formu doldurmak gibi özel bir ihtiyacınız varsa ekleyebilirsiniz
    // const addBirimKodInput = document.getElementById('birim-ekle-kod-modal');
    // const addBirimAdiInput = document.getElementById('birim-ekle-adi-modal');
    // const addBirimDurumSelect = document.getElementById('birim-ekle-durum-modal');


    // Düzenleme Modalı Formu
    // Modalınızdaki formun ve inputların doğru ID'lerine işaret ettiğinden emin olun!
    const editForm = document.getElementById('form-birim-duzenle-modal');
    const editBirimIdInput = document.getElementById('birim-duzenle-id-modal');
    const editBirimKodInput = document.getElementById('birim-duzenle-kod-modal');
    const editBirimAdiInput = document.getElementById('birim-duzenle-adi-modal');
    const editBirimDurumSelect = document.getElementById('birim-duzenle-durum-modal');

    // Silme Onay Modalı Elementleri
    // Modalınızdaki elementlerin doğru ID'lerine işaret ettiğinden emin olun!
    const deleteConfirmTextSpan = document.getElementById('modal-sil-birim-adi');
    const deleteConfirmButton = document.getElementById('btn-modal-sil-onay');


    // --- Helper Fonksiyonu: htmlspecialchars (JavaScript) ---
    // Data özniteliklerinden alınan veya DOM'a basılan veriyi güvence altına almak için
    function htmlspecialchars(str) {
        if (typeof str !== 'string' && typeof str !== 'number') { return ''; }
        str = String(str);
        const map = {'&': '&', '<': '<', '>': '>', '"': '"', "'": '''};
        return str.replace(/[&<>"']/g, function(m) { return map[m]; });
    }


    // --- Modal Olay Dinleyicileri ---

    // Birim Ekle Modal açıldığında formu sıfırla (Gerekirse)
    // Eğer modal her açıldığında formun temizlenmesini istiyorsanız bu eventi kullanın
    if(addModalElement) {
        addModalElement.addEventListener('shown.bs.modal', function (event) {
            if(addForm) addForm.reset(); // Formu sıfırla
             // Modal başlığını "Yeni Birim Ekle" olarak ayarla
             const modalTitle = addModalElement.querySelector('.modal-title');
            if(modalTitle) {
                 modalTitle.textContent = 'Yeni Birim Ekle';
            }
        });
    }


    // Birim Düzenle Modal açıldığında formu doldur
    if(editModalElement) {
        editModalElement.addEventListener('shown.bs.modal', function (event) {
            const button = event.relatedTarget; // Modalı tetikleyen Düzenle butonu

            // Butonun data-* özniteliklerinden birim bilgilerini al (hepsi string olarak gelir)
            const id = button.dataset.id;
            const kod = button.dataset.kod;
            const adi = button.dataset.adi;
            const durum = button.dataset.durum; // '0' veya '1' stringi

            // Modal içindeki form alanlarını doldur
            if(editBirimIdInput) editBirimIdInput.value = id;
            if(editBirimKodInput) editBirimKodInput.value = kod;
            if(editBirimAdiInput) editBirimAdiInput.value = adi;
            if(editBirimDurumSelect) editBirimDurumSelect.value = durum; // Select elementinin değeri string olarak atanır

            // Modal başlığını güncelle (isteğe bağlı)
            const modalTitle = editModalElement.querySelector('.modal-title');
            if(modalTitle) {
                 modalTitle.textContent = `Birim Düzenle: ${htmlspecialchars(kod)}`; // Kod ile başlık göster
            }
        });

         // Düzenleme Modalı KAPANDIĞINDA formu sıfırla (Gerekirse)
         if(editModalElement) {
             editModalElement.addEventListener('hidden.bs.modal', function (event) {
                 if(editForm) editForm.reset();
                 if(editBirimIdInput) editBirimIdInput.value = ''; // Gizli ID'yi temizle
             });
         }
    }


    // Birim Silme Onay Modal açıldığında içeriği doldur
    if(deleteConfirmModalElement) {
        deleteConfirmModalElement.addEventListener('shown.bs.modal', function (event) {
            const button = event.relatedTarget; // Modalı tetikleyen Sil butonu

            // Butonun data-* özniteliklerinden birim bilgilerini al
            const id = button.dataset.id;
            const adi = button.dataset.adi;

            // Modal içindeki onay metnini ve onay butonunu güncelle
            if(deleteConfirmTextSpan) deleteConfirmTextSpan.textContent = htmlspecialchars(adi);
            if(deleteConfirmButton) deleteConfirmButton.dataset.id = id; // Onay butonuna silinecek ID'yi ata
        });
    }


    // --- Form Gönderim Olayları (Modallardaki Formlar İçin - GELENEKSEL POST) ---
    // Modallardaki formlar submit edildiğinde TARAYICI YÖNLENDİRME YAPACAK.
    // JavaScript sadece modal açma/kapatma ve modal içini doldurma için kullanılır.
    // Form submit olayları için özel bir JavaScript'e gerek yok, tarayıcı varsayılanı kullanır.
    // Sadece Silme onay butonunun click olayını yakalamamız lazım.

    // Silme Onay Modalındaki Onay Butonu Olayı (Form Gönderme Yerine POST Yönlendirme)
    if(deleteConfirmButton) {
        deleteConfirmButton.addEventListener('click', function(e) {
            e.preventDefault(); // Butonun varsayılan davranışını engelle (link ise #'a gitmesini engeller)

            const birimId = this.dataset.id; // Onay butonunun data-id'sinden ID'yi al
             if (!birimId) {
                 // Toastr'ın yüklü olduğundan emin olun
                 if(typeof toastr !== 'undefined') toastr.warning("Silinecek birim ID'si eksik.");
                 else console.warn("Silinecek birim ID'si eksik.");
                 return; // ID yoksa işlem yapma
             }

             console.log("Silme Onayı Verildi, ID:", birimId);

             // Silme işlemi için GİZLİ FORM OLUŞTURUP POST GÖNDEREREK YÖNLENDİRME YAP
             const form = document.createElement('form');
             form.method = 'POST';
             // Silme işlemi URL'si: /portal/islemler/birim/sil.php
             form.action = '/portal/islemler/birim/sil.php';

             // Silinecek birimin ID'sini gizli input olarak forma ekle
             const idInput = document.createElement('input');
             idInput.type = 'hidden';
             idInput.name = 'id';
             idInput.value = birimId;
             form.appendChild(idInput);

             // Formu body'ye ekle ve gönder
             document.body.appendChild(form);
             form.submit(); // Tarayıcı formu action URL'sine POST metodu ile gönderir ve yönlenir.
             // Bu noktada modal otomatik kapanır veya kapanması için JS ekleyebilirsiniz.
        });
    }

    // Ekleme ve Düzenleme Modalı formlarının submit olayları için özel JS'ye gerek yok.
    // Tarayıcı form action'ına POST gönderecek ve yönlendirecek.


}); // DOMContentLoaded sonu
</script>