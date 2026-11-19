<?php
$gitbookUrl = $_ENV['GITBOOK_URL'] ?? 'https://own-org.gitbook.io/ciptasejatiindonesia';
?>
<!-- Modal Panduan / GitBook User Manual -->
<div id="UserManual" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="UserManualLabel" aria-hidden="true">
    <div class="modal-dialog modal-gitbook">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title-wrapper">
                    <h4 class="modal-title" id="UserManualLabel">
                        <i class="ico-book2"></i>
                        <span>Buku Panduan & Dokumentasi Sistem</span>
                    </h4>
                </div>
                <div class="modal-action-wrapper">
                    <a href="<?= htmlspecialchars($gitbookUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-xs btn-primary" title="Buka di Tab Baru" style="font-weight: 500;">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> <span class="hidden-xs">Buka di Tab Baru</span>
                    </a>
                    <button type="button" class="btn btn-xs btn-default" onclick="window.refreshGitbookFrame();" title="Muat Ulang Halaman">
                        <i class="fa-solid fa-arrows-rotate"></i>
                    </button>
                    <button type="button" class="btn btn-xs btn-default hidden-xs" onclick="window.toggleGitbookFullscreen();" id="btn-fullscreen-gitbook" title="Perbesar Layar">
                        <i class="fa-solid fa-expand"></i>
                    </button>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>

            <!-- Notice for HTTP Local Development (GitBook CSP frame-ancestors https requirement) -->
            <div id="gitbook-http-notice" style="display: none; background: #fffbeb; border-bottom: 1px solid #fde68a; padding: 8px 16px; font-size: 12px; color: #92400e; align-items: center; justify-content: space-between;">
                <div>
                    <i class="fa-solid fa-circle-info" style="margin-right: 6px; color: #d97706;"></i>
                    <strong>Pemberitahuan Lingkungan Lokal:</strong> Anda mengakses aplikasi via protokol <code>http://</code>. Kebijakan keamanan GitBook (CSP) mengharuskan <code>https://</code> untuk menampilkan konten di dalam frame. Jika buku panduan tidak muncul di bawah, gunakan tombol di samping untuk membuka langsung.
                </div>
                <a href="<?= htmlspecialchars($gitbookUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-xs btn-warning" style="margin-left: 10px; flex-shrink: 0; font-weight: 600;">
                    <i class="fa-solid fa-external-link-alt"></i> Buka di Tab Baru
                </a>
            </div>

            <div class="modal-body" style="padding: 0; position: relative;">
                <!-- Loading indicator overlay -->
                <div id="gitbook-loader" class="gitbook-loading-overlay">
                    <div class="text-center" style="max-width: 480px; padding: 20px;">
                        <i class="fa-solid fa-circle-notch fa-spin fa-2x text-primary"></i>
                        <p style="margin-top: 14px; font-weight: 600; font-size: 14px; color: #334155;">Memuat Buku Panduan GitBook...</p>
                        <p style="font-size: 12px; color: #64748b; margin-top: 6px;">
                            Jika halaman tidak muncul atau membutuhkan waktu lama, silakan
                            <a href="<?= htmlspecialchars($gitbookUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" style="color: #2563eb; font-weight: 600; text-decoration: underline;">
                                buka buku panduan langsung di tab baru
                            </a>.
                        </p>
                    </div>
                </div>

                <!-- Iframe GitBook -->
                <iframe 
                    id="gitbook-frame" 
                    data-src="<?= htmlspecialchars($gitbookUrl, ENT_QUOTES, 'UTF-8'); ?>"
                    src=""
                    onload="window.hideGitbookLoader && window.hideGitbookLoader();"
                    frameborder="0"
                    allow="clipboard-read; clipboard-write"
                    style="width: 100%; height: 100%; border: none; display: block;">
                </iframe>
            </div>
        </div>
    </div>
</div>

<style>
/* GitBook Modal Custom Styling */
#UserManual .modal-gitbook {
    width: 95%;
    max-width: 1350px;
    margin: 25px auto;
    transition: all 0.25s ease-in-out;
}

#UserManual .modal-content {
    border: none;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.35);
}

#UserManual .modal-header {
    background-color: #1e293b;
    color: #ffffff;
    padding: 12px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #334155;
}

#UserManual .modal-title-wrapper {
    display: flex;
    align-items: center;
}

#UserManual .modal-title {
    color: #ffffff;
    font-size: 15px;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

#UserManual .modal-title i {
    font-size: 18px;
    color: #38bdf8;
}

#UserManual .modal-action-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
}

#UserManual .modal-action-wrapper .btn-default {
    background-color: #334155;
    border-color: #475569;
    color: #f1f5f9;
    padding: 4px 10px;
    border-radius: 4px;
}

#UserManual .modal-action-wrapper .btn-default:hover {
    background-color: #475569;
    color: #ffffff;
}

#UserManual .modal-header .close {
    color: #ffffff;
    opacity: 0.8;
    text-shadow: none;
    font-size: 24px;
    margin-left: 10px;
    line-height: 1;
}

#UserManual .modal-header .close:hover {
    opacity: 1;
}

#UserManual .modal-body {
    height: calc(85vh - 60px);
    min-height: 550px;
    background: #f8fafc;
}

.gitbook-loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    transition: opacity 0.25s ease;
}

/* Fullscreen Mode */
#UserManual.modal-fullscreen .modal-gitbook {
    width: 100vw !important;
    max-width: 100vw !important;
    height: 100vh !important;
    margin: 0 !important;
}

#UserManual.modal-fullscreen .modal-content {
    height: 100vh !important;
    border-radius: 0 !important;
}

#UserManual.modal-fullscreen .modal-body {
    height: calc(100vh - 55px) !important;
}
</style>

<script>
window.gitbookUrl = <?= json_encode($gitbookUrl); ?>;
window.gitbookLoaded = false;
window.gitbookLoadTimer = null;

// Function to load the iframe cleanly
window.loadGitbookFrame = function() {
    var frame = document.getElementById('gitbook-frame');
    var loader = document.getElementById('gitbook-loader');
    var notice = document.getElementById('gitbook-http-notice');

    // Show HTTP notice if accessed via unencrypted http: (local environment)
    if (window.location.protocol === 'http:' && notice) {
        notice.style.display = 'flex';
    }

    if (!frame) return;

    if (!window.gitbookLoaded || !frame.src || frame.src === '' || frame.src === window.location.href) {
        var targetSrc = frame.getAttribute('data-src') || window.gitbookUrl;
        if (loader) {
            loader.style.display = 'flex';
            loader.style.opacity = '1';
        }
        frame.src = targetSrc;
        window.gitbookLoaded = true;

        // Safety timeout: automatically dismiss loader after 3.5s so user is never stuck
        if (window.gitbookLoadTimer) clearTimeout(window.gitbookLoadTimer);
        window.gitbookLoadTimer = setTimeout(function() {
            window.hideGitbookLoader();
        }, 3500);
    }
};

// Function to hide the loader cleanly
window.hideGitbookLoader = function() {
    var loader = document.getElementById('gitbook-loader');
    if (loader) {
        loader.style.opacity = '0';
        setTimeout(function() {
            loader.style.display = 'none';
        }, 250);
    }
    if (window.gitbookLoadTimer) {
        clearTimeout(window.gitbookLoadTimer);
        window.gitbookLoadTimer = null;
    }
};

// Function to reload the iframe
window.refreshGitbookFrame = function() {
    var frame = document.getElementById('gitbook-frame');
    var loader = document.getElementById('gitbook-loader');
    if (!frame) return;
    if (loader) {
        loader.style.display = 'flex';
        loader.style.opacity = '1';
    }
    var targetSrc = frame.getAttribute('data-src') || window.gitbookUrl;
    frame.src = '';
    setTimeout(function() {
        frame.src = targetSrc;
        if (window.gitbookLoadTimer) clearTimeout(window.gitbookLoadTimer);
        window.gitbookLoadTimer = setTimeout(function() {
            window.hideGitbookLoader();
        }, 3500);
    }, 100);
};

// Function to toggle fullscreen mode
window.toggleGitbookFullscreen = function() {
    var modal = document.getElementById('UserManual');
    var btn = document.getElementById('btn-fullscreen-gitbook');
    if (!modal) return;
    modal.classList.toggle('modal-fullscreen');
    if (btn) {
        var icon = btn.querySelector('i');
        if (modal.classList.contains('modal-fullscreen')) {
            if (icon) { icon.className = 'fa-solid fa-compress'; }
            btn.title = 'Kecilkan Layar';
        } else {
            if (icon) { icon.className = 'fa-solid fa-expand'; }
            btn.title = 'Perbesar Layar';
        }
    }
};

// Event listener setup
document.addEventListener('DOMContentLoaded', function() {
    if (window.jQuery) {
        $(document).on('show.bs.modal', '#UserManual', function() {
            window.loadGitbookFrame();
        });
        $(document).on('hidden.bs.modal', '#UserManual', function() {
            var modal = document.getElementById('UserManual');
            if (modal) modal.classList.remove('modal-fullscreen');
            var btn = document.getElementById('btn-fullscreen-gitbook');
            if (btn) {
                var icon = btn.querySelector('i');
                if (icon) icon.className = 'fa-solid fa-expand';
            }
        });
    }
});
</script>
