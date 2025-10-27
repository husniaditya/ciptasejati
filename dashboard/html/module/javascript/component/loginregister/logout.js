(function(){
  // Ensure overlay
  function ensureOverlay(){
    if(!document.getElementById('global-loading-overlay')){
      var style=document.createElement('style');
      style.id='global-loading-overlay-style';
      style.textContent="\n#global-loading-overlay{position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,.45);display:none;align-items:center;justify-content:center;z-index:9999}\n#global-loading-overlay .overlay-box{background:#fff;padding:16px 20px;border-radius:10px;box-shadow:0 10px 30px rgba(0,0,0,.2);min-width:240px;text-align:center}\n#global-loading-overlay .spinner{width:32px;height:32px;border:4px solid #e5e7eb;border-top-color:#1d4ed8;border-radius:50%;margin:0 auto 8px;animation:glob-spin .9s linear infinite}\n@keyframes glob-spin{to{transform:rotate(360deg)}}\n#global-loading-overlay .message{font-size:14px;color:#111827}\n";
      document.head.appendChild(style);
      var overlay=document.createElement('div');
      overlay.id='global-loading-overlay';
      overlay.setAttribute('role','status');
      overlay.setAttribute('aria-live','polite');
      overlay.setAttribute('aria-busy','true');
      overlay.innerHTML='\n  <div class="overlay-box">\n    <div class="spinner" aria-hidden="true"></div>\n    <div class="message">Memproses...</div>\n  </div>\n';
      document.body.appendChild(overlay);
    }
  }
  function showOverlay(msg){
    ensureOverlay();
    var ov=document.getElementById('global-loading-overlay');
    var m=ov.querySelector('.message');
    if(m) m.textContent= msg || 'Memproses...';
    ov.style.display='flex';
    document.body.style.pointerEvents='none';
  }
  function hideOverlay(){
    var ov=document.getElementById('global-loading-overlay');
    if(!ov) return;
    ov.style.display='none';
    document.body.style.pointerEvents='';
  }

  // Simple toast
  function ensureToast(){
    if(document.getElementById('global-toast-style')) return;
    var s=document.createElement('style');
    s.id='global-toast-style';
    s.textContent="\n#global-toast-container{position:fixed;bottom:20px;right:20px;z-index:10000;display:flex;flex-direction:column;gap:8px}\n.global-toast{min-width:240px;max-width:360px;padding:10px 12px;border-radius:8px;color:#fff;box-shadow:0 6px 20px rgba(0,0,0,.2);display:flex;align-items:flex-start;gap:8px;opacity:.98}\n.global-toast.success{background:#16a34a}\n.global-toast.error{background:#dc2626}\n.global-toast .close{margin-left:auto;background:transparent;border:none;color:#fff;font-size:16px;cursor:pointer}\n";
    document.head.appendChild(s);
    var c=document.createElement('div');
    c.id='global-toast-container';
    document.body.appendChild(c);
  }
  function toast(type,msg,ms){
    ensureToast();
    var c=document.getElementById('global-toast-container');
    var t=document.createElement('div');
    t.className='global-toast '+(type==='success'?'success':'error');
    t.setAttribute('role','alert');
    t.setAttribute('aria-live','polite');
    t.innerHTML='<div class="msg"></div><button class="close" aria-label="Tutup">×</button>';
    t.querySelector('.msg').textContent=msg;
    t.querySelector('.close').addEventListener('click',function(){ if(t.parentNode) c.removeChild(t); });
    c.appendChild(t);
    setTimeout(function(){ if(t.parentNode) c.removeChild(t); }, typeof ms==='number'?ms:2500);
  }

  // Bind logout interceptors
  function onReady(fn){ if(document.readyState==='loading'){document.addEventListener('DOMContentLoaded',fn);} else { fn(); }}

  onReady(function(){
    // Intercept clicks on logout links/buttons
    $(document).on('click', 'a[href$="logout.php"], a[href*="/logout"], .js-logout', function(e){
      // If anchor has data-noajax, let it pass
      if (this.hasAttribute('data-noajax')) return;
      e.preventDefault();
      var href = this.getAttribute('href') || 'logout.php';
      showOverlay('Sedang keluar...');
      $.ajax({
        type: 'POST',
        url: href.indexOf('logout')>-1 ? href : 'logout.php',
        dataType: 'json',
        data: { ajax: '1' },
        success: function(resp){
          if(resp && resp.status==='ok'){
            toast('success', resp.message || 'Anda berhasil logout');
            setTimeout(function(){ window.location.href = resp.redirect || 'index.php'; }, 500);
          } else {
            hideOverlay();
            toast('error', (resp && resp.message) || 'Logout gagal, coba lagi.');
          }
        },
        error: function(){
          hideOverlay();
          toast('error', 'Terjadi kesalahan koneksi.');
        }
      });
    });

    // Ensure overlay is hidden on bfcache restore
    window.addEventListener('pageshow', function(){ hideOverlay(); });
  });
})();
