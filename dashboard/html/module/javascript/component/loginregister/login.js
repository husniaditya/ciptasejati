(function () {
  // Inject minimal styles and overlay element if not present
  function ensureLoginOverlay() {
    if (!document.getElementById('login-loading-overlay')) {
      var style = document.createElement('style');
      style.id = 'login-loading-overlay-style';
      style.textContent = "\n#login-loading-overlay{position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.45);display:none;align-items:center;justify-content:center;z-index:9999;}\n#login-loading-overlay .overlay-box{background:#fff;padding:18px 22px;border-radius:10px;box-shadow:0 10px 30px rgba(0,0,0,0.2);min-width:260px;text-align:center;}\n#login-loading-overlay .spinner{width:36px;height:36px;border:4px solid #e5e7eb;border-top-color:#1d4ed8;border-radius:50%;margin:0 auto 10px;animation:login-spin .9s linear infinite;}\n@keyframes login-spin{to{transform:rotate(360deg)}}\n#login-loading-overlay .message{font-size:14px;color:#111827}"
      ;
      document.head.appendChild(style);

      var overlay = document.createElement('div');
      overlay.id = 'login-loading-overlay';
      overlay.setAttribute('role', 'status');
      overlay.setAttribute('aria-live', 'polite');
      overlay.setAttribute('aria-busy', 'true');
      overlay.innerHTML = '\n  <div class="overlay-box">\n    <div class="spinner" aria-hidden="true"></div>\n    <div class="message">Sedang memproses login...</div>\n  </div>\n';
      document.body.appendChild(overlay);
    }
  }

  function showLoginOverlay(msg) {
    ensureLoginOverlay();
    var overlay = document.getElementById('login-loading-overlay');
    var messageEl = overlay.querySelector('.message');
    if (messageEl && msg) messageEl.textContent = msg; else if (messageEl) messageEl.textContent = 'Sedang memproses login...';
    overlay.style.display = 'flex';
    document.body.style.pointerEvents = 'none';
  }
  function hideLoginOverlay() {
    var overlay = document.getElementById('login-loading-overlay');
    if (!overlay) return;
    overlay.style.display = 'none';
    document.body.style.pointerEvents = '';
  }

  // Simple inline error rendering
  function showLoginError(msg) {
    var container = document.getElementById('error-container');
    if (!container) return alert(msg);
    container.textContent = msg;
    container.style.color = '#b91c1c'; // red-700
  }

  // Expose minimal API if needed elsewhere
  window.LoginOverlay = { show: showLoginOverlay, hide: hideLoginOverlay };

  // Toast utilities (simple, self-contained)
  function ensureToastStyles() {
    if (document.getElementById('login-toast-style')) return;
    var style = document.createElement('style');
    style.id = 'login-toast-style';
    style.textContent = "\n#toast-container{position:fixed;bottom:20px;right:20px;z-index:10000;display:flex;flex-direction:column;gap:8px}\n.toast{min-width:260px;max-width:360px;padding:10px 12px;border-radius:8px;color:#fff;box-shadow:0 6px 20px rgba(0,0,0,.2);display:flex;align-items:flex-start;gap:8px;opacity:.98}\n.toast-success{background:#16a34a}\n.toast-error{background:#dc2626}\n.toast .toast-close{margin-left:auto;background:transparent;border:none;color:#fff;font-size:16px;cursor:pointer}\n";
    document.head.appendChild(style);
  }
  function ensureToastContainer() {
    ensureToastStyles();
    var c = document.getElementById('toast-container');
    if (!c) {
      c = document.createElement('div');
      c.id = 'toast-container';
      document.body.appendChild(c);
    }
    return c;
  }
  function showToast(type, message, timeout) {
    var container = ensureToastContainer();
    var toast = document.createElement('div');
    toast.className = 'toast ' + (type === 'success' ? 'toast-success' : 'toast-error');
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'polite');
    toast.innerHTML = '<div class="toast-message"></div><button class="toast-close" aria-label="Tutup">×</button>';
    toast.querySelector('.toast-message').textContent = message;
    toast.querySelector('.toast-close').addEventListener('click', function(){ container.removeChild(toast); });
    container.appendChild(toast);
    var ttl = typeof timeout === 'number' ? timeout : 2500;
    setTimeout(function(){ if (toast.parentNode) container.removeChild(toast); }, ttl);
  }

  // Provide togglePassword if not defined
  if (typeof window.togglePassword !== 'function') {
    window.togglePassword = function (id) {
      var el = document.getElementById(id);
      if (!el) return;
      el.type = (el.type === 'password') ? 'text' : 'password';
    };
  }

  // Attach handler after DOM ready
  function onReady(fn){ if(document.readyState==='loading'){document.addEventListener('DOMContentLoaded',fn);} else { fn(); }}

  onReady(function () {
    var $form = $('form[name="form-login"]');
    if (!$form.length) return;

    $form.on('submit', function (e) {
      e.preventDefault();

      var username = $.trim($form.find('input[name="username"]').val());
      var password = $form.find('input[name="password"]').val();
      if (!username || !password) {
        showLoginError('Mohon isi ID Anggota dan Password.');
        return;
      }

      showLoginOverlay('Sedang memverifikasi kredensial...');

      $.ajax({
        type: 'POST',
        url: 'module/backend/loginregister/t_login.php',
        dataType: 'json',
        data: {
          username: username,
          password: password,
          login: '1',
          ajax: '1'
        },
        success: function (resp) {
          if (resp && resp.status === 'ok') {
            // Success: show toast briefly, then navigate
            showToast('success', 'Login berhasil, masuk ke dashboard...');
            setTimeout(function(){
              window.location.href = resp.redirect || 'dashboard.php';
            }, 600);
          } else {
            hideLoginOverlay();
            var msg = (resp && resp.message) ? resp.message : 'Login gagal. Silakan coba lagi.';
            showLoginError(msg);
            showToast('error', msg);
          }
        },
        error: function (xhr, status, err) {
          hideLoginOverlay();
          showLoginError('Terjadi kesalahan koneksi. Silakan coba lagi.');
          showToast('error', 'Terjadi kesalahan koneksi. Silakan coba lagi.');
        }
      });
    });

    // In case user returns via back/forward cache, hide overlay
    window.addEventListener('pageshow', function () { hideLoginOverlay(); });
  });
})();
