document.addEventListener('DOMContentLoaded', function () {
    const currentURL = window.location.pathname;
    const pathSegments = currentURL.split('/');
    const currentPage = pathSegments[pathSegments.length - 1];
    
    // Function to add the "in" class to a specific submenu
    function addInClassToSubmenu(submenuID) {
      const submenu = document.getElementById(submenuID);
      if (submenu) {
          submenu.classList.add('in');
      }
    }
  
    // Example: Add the "in" class to the "admin" submenu
    // MenuID: Master
    if (currentPage === 'tingkatgelar.php' || currentPage === 'idsertifikat.php' || currentPage === 'dataterpusat.php') {
      addInClassToSubmenu('master');
    }
    if (currentPage === 'lokasipusat.php' || currentPage === 'lokasidaerah.php' || currentPage === 'lokasicabang.php') {
      addInClassToSubmenu('master');
      addInClassToSubmenu('lokasi');
    }
  
    // MenuID: Transaksi
    if (currentPage === 'kepengurusan.php') {
      addInClassToSubmenu('transaksi');
    }
    if (currentPage === 'anggota.php' || currentPage === 'kasanggota.php' || currentPage === 'mutasianggota.php') {
      addInClassToSubmenu('transaksi');
      addInClassToSubmenu('anggota');
    }
    if (currentPage === 'pusatdaya.php' || currentPage === 'ujinaiktingkat.php' || currentPage === 'latihangabungan.php' || currentPage === 'pendidikanlatihan.php') {
      addInClassToSubmenu('transaksi');
      addInClassToSubmenu('aktivitas');
    }
  
    // MenuID: Laporan
    if (currentPage === 'lapdaftarcabang.php' || currentPage === 'lapformatstandar.php') {
      addInClassToSubmenu('laporan');
    }
    if (currentPage === 'lapdaftarguru.php' || currentPage === 'lapdaftarpelatih.php' || currentPage === 'lapdaftarpengurus.php') {
      addInClassToSubmenu('laporan');
      addInClassToSubmenu('lappengurus');
    }
    if (currentPage === 'lapdaftaranggota.php' || currentPage === 'lapmutasianggota.php' || currentPage === 'lapkasanggota.php') {
      addInClassToSubmenu('laporan');
      addInClassToSubmenu('lapanggota');
    }
  
    // MenuID: Admin
    if (currentPage === 'profil.php' || currentPage === 'visimisi.php' || currentPage === 'warnalambang.php' || currentPage === 'mediasosial.php') {
      addInClassToSubmenu('admin');
      addInClassToSubmenu('profil');
    }
    if (currentPage === 'user.php' || currentPage === 'menu.php') {
      addInClassToSubmenu('admin');
    }
    if (currentPage === 'kontenheader.php' || currentPage === 'kontenfooter.php' || currentPage === 'kontententang.php' || currentPage === 'kontenblog.php' || currentPage === 'kontenhubungi.php') {
      addInClassToSubmenu('admin');
      addInClassToSubmenu('manajemenkonten');
    }
    if (currentPage === 'berandaposter.php' || currentPage === 'berandakegiatan.php' || currentPage === 'berandainformasi.php') {
      addInClassToSubmenu('admin');
      addInClassToSubmenu('manajemenkonten');
      addInClassToSubmenu('beranda');
    }
    if (currentPage === 'tentangsejarah.php' || currentPage === 'tentangvisimisi.php') {
      addInClassToSubmenu('admin');
      addInClassToSubmenu('manajemenkonten');
      addInClassToSubmenu('tentangkami');
    }
  
    // Function to set the active class for all level3 items in sub-menus
    function setActiveLevelItems(currentPage) {
        const level2Items = document.querySelectorAll('.level2');
        const level3Items = document.querySelectorAll('.level3');
        const level4Items = document.querySelectorAll('.level4');
  
        level2Items.forEach(item => {
            const anchor = item.querySelector('a');
            if (anchor && anchor.getAttribute('href') === currentPage) {
                item.classList.add('active');
            }
        });
        level3Items.forEach(item => {
            const anchor = item.querySelector('a');
            if (anchor && anchor.getAttribute('href') === currentPage) {
                item.classList.add('active');
            }
        });
        level4Items.forEach(item => {
          const anchor = item.querySelector('a');
          if (anchor && anchor.getAttribute('href') === currentPage) {
              item.classList.add('active');
          }
      });
    }
  
    // Set the active class for all level items in sub-menus
    setActiveLevelItems(currentPage);
  });