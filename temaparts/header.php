<header class="navbar navbar-expand-md d-print-none">
  <div class="container-xl">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
      aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
      <a href="<?= $SiteURL; ?>"><img src="<?= $SiteURL; ?>static/logo_celikerler.svg" alt="Logo"
          class="navbar-brand-image"></a>
    </div>
    <div class="navbar-nav flex-row order-md-last">
      <div class="d-none d-md-flex">
        <div class="nav-item">
          <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Koyu Mod" data-bs-toggle="tooltip"
            data-bs-placement="bottom">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
              <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" />
            </svg>
          </a>
          <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Açık Mod" data-bs-toggle="tooltip"
            data-bs-placement="bottom">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
              <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
              <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" />
            </svg>
          </a>
        </div>
        <div class="nav-item dropdown d-none d-md-flex">
          <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show notifications"
            data-bs-auto-close="outside" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
              <path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
              <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
            </svg>
            <span class="badge bg-red"></span>
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
            <div class="card">
              <div class="card-header d-flex">
                <h3 class="card-title">Bildirimler</h3>
                <div class="btn-close ms-auto" data-bs-dismiss="dropdown"></div>
              </div>
              <div class="list-group list-group-flush list-group-hoverable">
                <div class="list-group-item">
                  <div class="row align-items-center">
                    <div class="col-auto"><span class="status-dot status-dot-animated bg-red d-block"></span></div>
                    <div class="col text-truncate">
                      <a href="#" class="text-body d-block">Örnek 1</a>
                      <div class="d-block text-secondary text-truncate mt-n1">Change deprecated html tags to text
                        decoration classes (#29604)</div>
                    </div>
                    <div class="col-auto">
                      <a href="#" class="list-group-item-actions">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          class="icon text-muted icon-2">
                          <path
                            d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                        </svg>
                      </a>
                    </div>
                  </div>
                </div>
                <div class="list-group-item">
                  <div class="row align-items-center">
                    <div class="col-auto"><span class="status-dot d-block"></span></div>
                    <div class="col text-truncate">
                      <a href="#" class="text-body d-block">Örnek 2</a>
                      <div class="d-block text-secondary text-truncate mt-n1">justify-content:between ⇒
                        justify-content:space-between (#29734)</div>
                    </div>
                    <div class="col-auto">
                      <a href="#" class="list-group-item-actions show">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          class="icon text-yellow icon-2">
                          <path
                            d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                        </svg>
                      </a>
                    </div>
                  </div>
                </div>
                <div class="list-group-item">
                  <div class="row align-items-center">
                    <div class="col-auto"><span class="status-dot d-block"></span></div>
                    <div class="col text-truncate">
                      <a href="#" class="text-body d-block">Örnek 3</a>
                      <div class="d-block text-secondary text-truncate mt-n1">Update change-version.js (#29736)
                      </div>
                    </div>
                    <div class="col-auto">
                      <a href="#" class="list-group-item-actions">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          class="icon text-muted icon-2">
                          <path
                            d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                        </svg>
                      </a>
                    </div>
                  </div>
                </div>
                <div class="list-group-item">
                  <div class="row align-items-center">
                    <div class="col-auto"><span class="status-dot status-dot-animated bg-green d-block"></span>
                    </div>
                    <div class="col text-truncate">
                      <a href="#" class="text-body d-block">Örnek 4</a>
                      <div class="d-block text-secondary text-truncate mt-n1">Regenerate package-lock.json (#29730)
                      </div>
                    </div>
                    <div class="col-auto">
                      <a href="#" class="list-group-item-actions">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          class="icon text-muted icon-2">
                          <path
                            d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                        </svg>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <a href="#" class="btn btn-2 w-100"> Tümünü arşivle </a>
                  </div>
                  <div class="col">
                    <a href="#" class="btn btn-2 w-100"> Tümünü okundu yap </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="nav-item dropdown d-none d-md-flex me-3">
          <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show app menu"
            data-bs-auto-close="outside" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
              <path d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
              <path d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
              <path d="M14 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
              <path d="M14 7l6 0" />
              <path d="M17 4l0 6" />
            </svg>
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
            <div class="card">
              <div class="card-header">
                <div class="card-title">Uygulamalar</div>
                <div class="card-actions btn-actions"></div>
              </div>
              <div class="card-body scroll-y p-2" style="max-height: 50vh">
                <div class="row g-0">
                  <div class="col-4">
                    <a href="#"
                      class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                      <img src="./static/brands/amazon.svg" class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                        alt="" />
                      <span class="h5">Uygulama 1</span>
                    </a>
                  </div>
                  <div class="col-4">
                    <a href="#"
                      class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                      <img src="./static/brands/android.svg" class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                        alt="" />
                      <span class="h5">Uygulama 2</span>
                    </a>
                  </div>
                  <div class="col-4">
                    <a href="#"
                      class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                      <img src="./static/brands/app-store.svg" class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                        alt="" />
                      <span class="h5">Uygulama 3</span>
                    </a>
                  </div>
                  <div class="col-4">
                    <a href="#"
                      class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                      <img src="./static/brands/apple-podcast.svg" class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                        alt="" />
                      <span class="h5">Uygulama 4</span>
                    </a>
                  </div>
                  <div class="col-4">
                    <a href="#"
                      class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                      <img src="./static/brands/apple.svg" class="w-6 h-6 mx-auto mb-2" width="24" height="24" alt="" />
                      <span class="h5">Uygulama 5</span>
                    </a>
                  </div>
                  <div class="col-4">
                    <a href="#"
                      class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                      <img src="./static/brands/behance.svg" class="w-6 h-6 mx-auto mb-2" width="24" height="24"
                        alt="" />
                      <span class="h5">Uygulama 6</span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="nav-item dropdown">
        <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown" aria-label="Open user menu">
          <span class="avatar avatar-sm"
            style="background-image: url(<?php createDefaultAvatar($_SESSION['ad'], $_SESSION['soyad']); ?>)"> </span>
          <div class="d-none d-xl-block ps-2">
            <div><?php echo $_SESSION['adsoyad']; ?></div>
            <div class="mt-1 small text-secondary"><?php echo $_SESSION['gorev']; ?></div>
          </div>
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
          <a href="<?= $SiteURL; ?>profil" class="dropdown-item">Profil</a>
          <div class="dropdown-divider"></div>
          <a href="<?= $SiteURL; ?>ayarlar" class="dropdown-item">Ayarlar</a>
          <div class="dropdown-divider"></div>
          <a href="<?= $SiteURL; ?>cikis" class="dropdown-item">Çıkış</a>
        </div>
      </div>
    </div>
    <div class="collapse navbar-collapse" id="navbar-menu">
      <?php include_once 'temaparts/menu.php'; ?>
    </div>
  </div>
</header>
<div class="page-wrapper">
  
  <?php
  // Başarı mesajını kontrol et ve HTML'e bas
  if (isset($_SESSION['success_message'])) {
    echo '<input type="hidden" id="session-success-message" value="' . htmlspecialchars($_SESSION['success_message']) . '">';
    unset($_SESSION['success_message']); // Mesajı kullandıktan sonra temizle
  }

  // Hata mesajını kontrol et ve HTML'e bas
  if (isset($_SESSION['error_message'])) {
    echo '<input type="hidden" id="session-error-message" value="' . htmlspecialchars($_SESSION['error_message']) . '">';
    unset($_SESSION['error_message']); // Mesajı kullandıktan sonra temizle
  }
  ?>