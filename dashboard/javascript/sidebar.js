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
    if (currentPage === 'tingkatgelar' || currentPage === 'idsertifikat' || currentPage === 'dataterpusat') {
      addInClassToSubmenu('master');
    }
    if (currentPage === 'lokasipusat' || currentPage === 'lokasidaerah' || currentPage === 'lokasicabang') {
      addInClassToSubmenu('master');
      addInClassToSubmenu('lokasi');
    }
  
    // MenuID: Transaksi
    if (currentPage === 'anggota' || currentPage === 'kepengurusan') {
      addInClassToSubmenu('transaksi');
    }
    if (currentPage === 'pusatdaya' || currentPage === 'ujinaiktingkat' || currentPage === 'latihangabungan' || currentPage === 'pendidikanlatihan') {
      addInClassToSubmenu('transaksi');
      addInClassToSubmenu('aktivitas');
    }
  
    // MenuID: Laporan
    if (currentPage === 'lapdaftarcabang' || currentPage === 'lapdaftarguru' || currentPage === 'lapdaftarpelatih' || currentPage === 'lapdaftarpengurus' || currentPage === 'lapdaftaranggota' || currentPage === 'lapidanggota' || currentPage === 'lapformatstandar') {
      addInClassToSubmenu('laporan');
    }
  
    // MenuID: Admin
    if (currentPage === 'profil' || currentPage === 'user' || currentPage === 'menu') {
      addInClassToSubmenu('admin');
    }
    if (currentPage === 'kontenheader' || currentPage === 'kontenfooter' || currentPage === 'kontententang' || currentPage === 'kontenblog' || currentPage === 'kontenhubungi') {
      addInClassToSubmenu('admin');
      addInClassToSubmenu('manajemenkonten');
    }
    if (currentPage === 'berandaposter' || currentPage === 'berandakegiatan' || currentPage === 'berandainformasi') {
      addInClassToSubmenu('admin');
      addInClassToSubmenu('manajemenkonten');
      addInClassToSubmenu('beranda');
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
          console.log(level4Items,currentPage,anchor.getAttribute('href'));
      });
    }
  
    // Set the active class for all level items in sub-menus
    setActiveLevelItems(currentPage);
  });