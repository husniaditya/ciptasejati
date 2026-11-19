<?php
$gitbookUrl = $_ENV['GITBOOK_URL'] ?? 'https://own-org.gitbook.io';
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
                    <a href="<?= htmlspecialchars($gitbookUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-xs btn-default" title="Buka di Tab Baru">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> <span class="hidden-xs">Buka di Tab Baru</span>
                    </a>
                    <button type="button" class="btn btn-xs btn-default" id="btn-refresh-gitbook" title="Muat Ulang Halaman">
                        <i class="fa-solid fa-arrows-rotate"></i>
                    </button>
                    <button type="button" class="btn btn-xs btn-default hidden-xs" id="btn-fullscreen-gitbook" title="Perbesar Layar">
                        <i class="fa-solid fa-expand"></i>
                    </button>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body" style="padding: 0; position: relative;">
                <!-- Loading indicator -->
                <div id="gitbook-loader" class="gitbook-loading-overlay">
                    <div class="text-center">
                        <i class="fa-solid fa-circle-notch fa-spin fa-2x text-primary"></i>
                        <p style="margin-top: 10px; font-weight: 500; color: #555;">Memuat Buku Panduan GitBook...</p>
                    </div>
                </div>

                <!-- Iframe GitBook -->
                <iframe 
                    id="gitbook-frame" 
                    data-src="<?= htmlspecialchars($gitbookUrl, ENT_QUOTES, 'UTF-8'); ?>"
                    src=""
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
    margin: 30px auto;
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
    transition: opacity 0.2s ease;
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
(function() {
    var gitbookLoaded = false;
    var $modal = $('#UserManual');
    var $frame = $('#gitbook-frame');
    var $loader = $('#gitbook-loader');

    // Lazy load GitBook when modal is first triggered
    $modal.on('show.bs.modal', function () {
        if (!gitbookLoaded) {
            var targetSrc = $frame.attr('data-src');
            $frame.attr('src', targetSrc);
            gitbookLoaded = true;
        }
    });

    // Hide loader once iframe has finished loading
    $frame.on('load', function() {
        if ($frame.attr('src')) {
            $loader.fadeOut(200);
        }
    });

    // Refresh button
    $('#btn-refresh-gitbook').on('click', function(e) {
        e.preventDefault();
        $loader.show();
        var currentSrc = $frame.attr('data-src');
        $frame.attr('src', '');
        setTimeout(function() {
            $frame.attr('src', currentSrc);
        }, 100);
    });

    // Toggle Fullscreen button
    $('#btn-fullscreen-gitbook').on('click', function(e) {
        e.preventDefault();
        $modal.toggleClass('modal-fullscreen');
        var $icon = $(this).find('i');
        if ($modal.hasClass('modal-fullscreen')) {
            $icon.removeClass('fa-expand').addClass('fa-compress');
            $(this).attr('title', 'Kecilkan Layar');
        } else {
            $icon.removeClass('fa-compress').addClass('fa-expand');
            $(this).attr('title', 'Perbesar Layar');
        }
    });

    // Reset fullscreen when closed
    $modal.on('hidden.bs.modal', function () {
        $modal.removeClass('modal-fullscreen');
        $('#btn-fullscreen-gitbook').find('i').removeClass('fa-compress').addClass('fa-expand');
    });
})();
</script>
