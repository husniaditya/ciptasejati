<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];
$USER_DAERAH = $_SESSION["LOGINDAR_CS"];

// Note: Initial full data fetch removed. DataTables now uses server-side AJAX (aj_tableanggota_ssp.php)

$getAkses = GetQuery("select * from p_param where KATEGORI = 'USER_AKSES' ORDER BY CODE");
$getDaerah = GetQuery("select * from m_daerah where DELETION_STATUS = 0 order by DAERAH_DESKRIPSI");
$getCabang = GetQuery("select * from m_cabang where DELETION_STATUS = 0 order by CABANG_DESKRIPSI");
$getTingkatan = GetQuery("select * from m_tingkatan where DELETION_STATUS = 0 order by TINGKATAN_LEVEL");
$getURL = GetQuery("SELECT * FROM p_param WHERE KATEGORI = 'url'");
while ($urlData = $getURL->fetch(PDO::FETCH_ASSOC)) {
    $URL = $urlData["DESK"];
}
// Fetch all rows into an array
$rowd = $getDaerah->fetchAll(PDO::FETCH_ASSOC);
$rows = $getCabang->fetchAll(PDO::FETCH_ASSOC);
$rowt = $getTingkatan->fetchAll(PDO::FETCH_ASSOC);
$rowakses = $getAkses->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="panel-group" id="accordion1">
    <div class="panel panel-default">
        <a data-toggle="collapse" data-parent="#accordion1" href="#collapseOne" class="collapsed">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <i class="fa-solid fa-chevron-down"></i> Filter Data Anggota
                </h4>
            </div>
        </a>
        <div id="collapseOne" class="panel-collapse collapse">
            <div class="panel-body">
                <form method="post" class="form filterAnggota" id="filterAnggota">
                    <div class="row">
                        <?php
                        if ($USER_AKSES == "Administrator" || $USER_AKSES == "Pengurus Daerah") {
                            ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Daerah</label>
                                    <select name="DAERAH_KEY" id="selectize-select3" required="" class="form-control" data-parsley-required>
                                        <option value="">-- Pilih Daerah --</option>
                                        <?php
                                        if ($USER_AKSES == "Pengurus Daerah") {
                                            ?>
                                            <option value="<?= $USER_DAERAH; ?>" selected>
                                                <?php
                                                foreach ($rowd as $filterDaerah) {
                                                    extract($filterDaerah);
                                                    if ($DAERAH_KEY == $USER_DAERAH) {
                                                        echo $DAERAH_DESKRIPSI;
                                                    }
                                                }
                                                ?>
                                            </option>
                                            <?php
                                        } else {
                                            foreach ($rowd as $filterDaerah) {
                                                extract($filterDaerah);
                                                ?>
                                                <option value="<?= $DAERAH_KEY; ?>"><?= $DAERAH_DESKRIPSI; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div> 
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Cabang</label>
                                    <select name="CABANG_KEY" id="selectize-select2" required="" class="form-control" data-parsley-required>
                                        <option value="">-- Pilih Cabang --</option>
                                    </select>
                                </div> 
                            </div>
                            <?php
                        }
                        ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Ranting</label>
                                <input type="text" class="form-control" id="filterANGGOTA_RANTING" name="ANGGOTA_RANTING" value="" placeholder="Input Lokasi Ranting">
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tingkatan</label>
                                <select name="TINGKATAN_ID" id="selectize-select" required="" class="form-control" data-parsley-required>
                                    <option value="">-- Pilih Tingkatan --</option>
                                    <?php
                                    foreach ($rowt as $filterTingkatan) {
                                        extract($filterTingkatan);
                                        ?>
                                        <option value="<?= $TINGKATAN_ID; ?>"><?= $TINGKATAN_NAMA; ?> - <?= $TINGKATAN_SEBUTAN; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>ID Anggota</label>
                                <input type="text" class="form-control" id="filterANGGOTA_ID" name="ANGGOTA_ID" value="" placeholder="Input ID Anggota">
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" id="filterANGGOTA_NAMA" name="ANGGOTA_NAMA" value="" placeholder="Input Nama">
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Akses Pengguna</label>
                                <select id="filterANGGOTA_AKSES" name="ANGGOTA_AKSES" class="form-control"  data-parsley-required required>
                                    <option value="">Tampilkan semua</option>
                                    <option value="User">Anggota</option>
                                    <option value="Koordinator">Koordinator</option>
                                    <option value="Pengurus">Pengurus</option>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status Anggota</label>
                                <select id="filterANGGOTA_STATUS" name="ANGGOTA_STATUS" class="form-control"  data-parsley-required required>
                                    <option value="">Tampilkan semua</option>
                                    <option value="0">Aktif</option>
                                    <option value="1">Non Aktif</option>
                                    <option value="2">Mutasi</option>
                                </select>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" align="center">
                            <button type="button" id="reloadButton" onclick="clearForm()" class="submit btn btn-teal btn-outline mb5 btn-rounded"><span class="ico-refresh"></span> Reset Filter</button>
                        </div>
                    </div>
                </form>
            </div>

                    
        </div>
    </div>
</div>
<hr>
<!-- START row -->
<?php
if (isset($_SESSION["ADD_DaftarAnggota"]) && $_SESSION["ADD_DaftarAnggota"] == "Y") {
    ?>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddAnggota btn btn-inverse btn-outline mb5 btn-rounded" href="#AddAnggota"><i class="ico-plus2"></i> Tambah Data Anggota</a>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 text-right">
            <a href="module/backend/transaksi/anggota/daftaranggota/t_download_template_anggota.php" class="btn btn-primary btn-outline mb5 btn-rounded" style="margin-left:8px;">
                <i class="ico-download3"></i> Download Template
            </a>
            <a data-toggle="modal" title="Upload Template" class="btn btn-success btn-outline mb5 btn-rounded" style="margin-left:8px;" href="#UploadAnggota">
                <i class="ico-upload"></i> Upload Template
            </a>
        </div>
    </div>
    <br>
    <?php
}
?>
<!--/ END row -->

<!-- START row -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Tabel Anggota</h3>
            </div>
            <table class="table table-striped table-bordered" id="anggota-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>ID Anggota</th>
                        <th>Nama</th>
                        <th>TTL</th>
                        <th>L/P</th>
                        <th>Sabuk </th>
                        <th>Tingkatan </th>
                        <th>Gelar </th>
                        <th>KTP</th>
                        <th>No HP</th>
                        <th>Email</th>
                        <th>Ranting </th>
                        <th>Cabang </th>
                        <th>Daerah </th>
                        <th>Tgl Bergabung</th>
                        <th>Status Anggota</th>
                        <th>Tgl Resign</th>
                    </tr>
                </thead>
                <tbody id="anggotadata"></tbody>
            </table>
        </div>
    </div>
</div>
<br><br>
<!--/ END row -->

<!-- Confirmation Modal: Open Card ID -->
<div id="ConfirmOpenCardIDModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="confirmOpenCardIDTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3 class="semibold modal-title text-inverse" id="confirmOpenCardIDTitle">Buka Kartu ID</h3>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-start" style="gap:12px;">
                    <div style="flex:0 0 auto; width:40px; height:40px; border-radius:10px; background:#e9f2ff; display:flex; align-items:center; justify-content:center; color:#0d6efd;">
                        <i class="fa-regular fa-id-card"></i>
                    </div>
                    <div>
                        <div style="font-size:14px; color:#6c757d;">Anda akan membuka Kartu ID untuk:</div>
                        <div style="font-size:16px; font-weight:600; color:#212529;" id="confirmCardNama">-</div>
                        <div style="font-size:13px; color:#6c757d;" id="confirmCardId">ID: -</div>
                        <div style="margin-top:12px;">
                            <div id="ktaUnavailable" style="display:none; padding:16px; border:1px dashed #dee2e6; border-radius:6px; color:#6c757d; background:#f8f9fa; text-align:center;">
                                Belum tersedia
                            </div>
                            <iframe id="idcardPreview" title="Pratinjau Kartu ID" src="about:blank" style="width:100%; height:520px; border:0; border-radius:6px; background:#f8f9fa;"></iframe>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                <button type="button" class="btn btn-primary btn-outline mb5 btn-rounded" id="confirmOpenCardBtn">
                    <i class="fa-solid fa-check"></i> <span id="confirmOpenCardBtnText">Aktivasi KTA</span>
                </button>
            </div>
        </div>
    </div>
    <script>
        (function(){
            var pendingTargetLink = null;
            // Inject base URL from PHP into JS
            var APP_BASE_URL = <?php echo json_encode(rtrim($URL ?? '', '/')); ?>;
            // Helper: Base64 encode (handles unicode safely)
            function encodeIdToBase64(val){
                try {
                    return window.btoa(String(val));
                } catch (e) {
                    try {
                        return window.btoa(unescape(encodeURIComponent(String(val))));
                    } catch (e2) {
                        return '';
                    }
                }
            }
            function normalizePathToAbsolute(path){
                var base = (typeof APP_BASE_URL === 'string' ? APP_BASE_URL : '').replace(/\/+$/, '');
                if (!path) return '';
                // Trim
                path = String(path).trim();
                // If already absolute URL
                if (/^https?:\/\//i.test(path)) return path;
                // Strip leading ./
                path = path.replace(/^\.\//, '');
                // Ensure it starts from dashboard/html if it points to assets/ or images/
                if (/^(assets|images)\//i.test(path)) {
                    return base + '/dashboard/html/' + path.replace(/\/+$/, '');
                }
                // If it already looks like /dashboard/html/... keep as absolute from base
                if (/^\/?dashboard\/html\//i.test(path)) {
                    return base + '/' + path.replace(/^\//, '');
                }
                // Fallback: join to base
                return base + '/' + path.replace(/^\//, '');
            }

            function openConfirm(linkEl){
                var nama = linkEl.getAttribute('data-nama') || '-';
                var id = linkEl.getAttribute('data-id') || '-';
                var key = linkEl.getAttribute('data-id2') || '-';
                var kta = linkEl.getAttribute('data-kta') || '';
                document.getElementById('confirmCardNama').textContent = nama;
                document.getElementById('confirmCardId').textContent = 'ID: ' + key;
                pendingTargetLink = linkEl;
                // Prepare preview using stored ANGGOTA_KTA; show 'Belum tersedia' if empty
                try {
                    var iframe = document.getElementById('idcardPreview');
                    var msg = document.getElementById('ktaUnavailable');
                    var btnTextEl = document.getElementById('confirmOpenCardBtnText');
                    if (btnTextEl) btnTextEl.textContent = (kta && kta.length > 0) ? 'Aktivasi Ulang KTA' : 'Generate KTA';
                    if (kta && kta.length > 0) {
                        var previewUrl = normalizePathToAbsolute(kta);
                        if (iframe) {
                            iframe.style.display = '';
                            iframe.src = previewUrl;
                        }
                        if (msg) msg.style.display = 'none';
                    } else {
                        if (iframe) {
                            iframe.src = 'about:blank';
                            iframe.style.display = 'none';
                        }
                        if (msg) msg.style.display = '';
                    }
                } catch (e) {
                    // no-op; iframe will remain blank
                }
                $('#ConfirmOpenCardIDModal').modal('show');
            }

            // Intercept clicks on the Card ID link
            document.addEventListener('click', function(e){
                var t = e.target;
                // Walk up to the anchor if icon or span clicked
                while (t && t !== document && !(t.matches && t.matches('a.open-CardId'))){
                    t = t.parentNode;
                }
                if (!t || !t.matches || !t.matches('a.open-CardId')) return;

                // Prevent default first navigation/modal open
                e.preventDefault();

                // Guard to avoid loop on confirmed navigation
                if (t.getAttribute('data-confirmed') === '1'){
                    t.removeAttribute('data-confirmed');
                    // proceed
                    var href = t.getAttribute('href');
                    if (href && href.startsWith('#')) {
                        // If it targets a modal id, trigger it
                        $(href).modal('show');
                    } else if (href) {
                        window.location.href = href;
                    }
                    return;
                }

                openConfirm(t);
            }, true);

            // Confirm button handler -> open print in new tab
            document.addEventListener('DOMContentLoaded', function(){
                var btn = document.getElementById('confirmOpenCardBtn');
                if (!btn) return;
                btn.addEventListener('click', function(){
                    if (!pendingTargetLink) return;
                    // Always open generator endpoint (print_idanggota.php) in a new tab
                    var anggotaId = pendingTargetLink.getAttribute('data-id') || '';
                    var encoded = encodeIdToBase64(anggotaId);
                    try {
                        var base = (typeof APP_BASE_URL === 'string' ? APP_BASE_URL : '').replace(/\/+$/, '');
                        var url = base + '/dashboard/html/assets/print/transaksi/anggota/print_idanggota.php?id=' + encoded;
                        window.open(url, '_blank');
                    } catch (e) {
                        // Fallback attempt
                        var base = (typeof APP_BASE_URL === 'string' ? APP_BASE_URL : '').replace(/\/+$/, '');
                        var urlFallback = base + '/dashboard/html/assets/print/transaksi/anggota/print_idanggota.php?id=' + encoded;
                        window.open(urlFallback, '_blank');
                    }
                    $('#ConfirmOpenCardIDModal').modal('hide');
                    pendingTargetLink = null;
                });
                // Clear iframe on modal hide to free memory
                $('#ConfirmOpenCardIDModal').on('hidden.bs.modal', function(){
                    var iframe = document.getElementById('idcardPreview');
                    if (iframe) {
                        iframe.src = 'about:blank';
                        iframe.style.display = '';
                    }
                    var msg = document.getElementById('ktaUnavailable');
                    if (msg) msg.style.display = 'none';
                });
            });
        })();
    </script>
</div>

<div id="AddAnggota" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="AddAnggota-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Tambah Data Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" align="center">
                            <div class="form-group">
                                <!-- PUT THE SAMPLE UPLOAD PHOTO HERE -->
                                <label>Foto Anggota </label><br>
                                <div id="preview-container">
                                    <img id="preview-image" src="#" alt="Preview" style="width: 300px; height: 300px;text-align: center;overflow: hidden;position: relative; object-fit:contain" />
                                </div>
                                <br>
                                <div>
                                    <span class="btn btn-inverse mb5 btn-rounded fileinput-button">
                                        <i class="fa-regular fa-image"></i>
                                        <span>Upload Foto...</span>
                                        <!-- The file input field used as target for the file upload widget -->
                                        <input type="file" name="ANGGOTA_PIC[]" id="ANGGOTA_PIC" onchange="previewImage(this);" accept="image/*" /> <br>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <?php
                            if ($USER_AKSES == "Administrator") {
                                ?>
                                <div class="short-div">
                                    <div class="form-group">
                                        <label>Daerah<span class="text-danger">*</span></label>
                                        <div id="selectize-wrapper9" style="position: relative;">
                                            <select name="DAERAH_KEY" id="selectize-dropdown9" required="" class="form-control" data-parsley-required>
                                                <option value="">-- Pilih Daerah --</option>
                                                <?php
                                                foreach ($rowd as $rowDaerah) {
                                                    extract($rowDaerah);
                                                    ?>
                                                    <option value="<?= $DAERAH_KEY; ?>"><?= $DAERAH_DESKRIPSI; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div> 
                                </div>
                                <div class="short-div">
                                    <div class="form-group">
                                        <label>Cabang<span class="text-danger">*</span></label>
                                        <div id="selectize-wrapper3" style="position: relative;">
                                            <select name="CABANG_KEY" id="selectize-dropdown3" required="" class="form-control" data-parsley-required>
                                                <option value="">-- Pilih Cabang --</option>]
                                            </select>
                                        </div>
                                    </div> 
                                </div>
                                <?php
                            }
                            ?>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Ranting</label><span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="ANGGOTA_RANTING" name="ANGGOTA_RANTING" value="" data-parsley-required required>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tingkatan<span class="text-danger">*</span></label>
                                    <div id="selectize-wrapper2" style="position: relative;">
                                        <select name="TINGKATAN_ID" id="selectize-dropdown2" required="" class="form-control" data-parsley-required>
                                            <option value="">-- Pilih Tingkatan --</option>
                                            <?php
                                            foreach ($rowt as $rowTingkatan) {
                                                extract($rowTingkatan);
                                                ?>
                                                <option value="<?= $TINGKATAN_ID; ?>"><?= $TINGKATAN_NAMA; ?> - <?= $TINGKATAN_SEBUTAN; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>No Urut Anggota<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" minlength="5" maxlength="5" oninput="validateInput(this)" required id="ANGGOTA_ID" name="ANGGOTA_ID" value="" placeholder="Inputkan 5 digit nomor urut keanggotaan" data-parsley-required>
                                    <div id="warning-message" style="color: red;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Bergabung</label><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="datepicker44" name="ANGGOTA_JOIN" placeholder="Pilih tanggal" readonly required data-parsley-required/>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama</label><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ANGGOTA_NAMA" name="ANGGOTA_NAMA" value="" data-parsley-required data-parsley-maxlength="50" required>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tempat &amp; Tanggal Lahir</label><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ANGGOTA_TEMPAT_LAHIR" name="ANGGOTA_TEMPAT_LAHIR" value="" data-parsley-required required>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label style="color: transparent;">.</label>
                                <input type="text" class="form-control" id="datepicker4" name="ANGGOTA_TANGGAL_LAHIR" placeholder="Pilih tanggal" readonly data-parsley-required required/>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Agama</label><span class="text-danger">*</span></label>
                                <select id="ANGGOTA_AGAMA" name="ANGGOTA_AGAMA" class="form-control" placeholder="Pilih Agama..." data-parsley-required required>
                                    <option value="">Pilih Agama...</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Khonghucu">Khonghucu</option>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jenis Kelamin</label><span class="text-danger">*</span></label>
                                <select id="ANGGOTA_KELAMIN" name="ANGGOTA_KELAMIN" class="form-control" placeholder="Pilih Jenis Kelamin..." data-parsley-required required>
                                    <option value="">Pilih Jenis Kelamin...</option>
                                    <option value="L">Pria</option>
                                    <option value="P">Wanita</option>
                                </select>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>KTP</label><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ANGGOTA_KTP" name="ANGGOTA_KTP" value="" placeholder="Inputkan no KTP" data-parsley-required data-parsley-type="number" required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alamat</label>
                                <textarea type="text" rows="4" class="form-control" id="ANGGOTA_ALAMAT" name="ANGGOTA_ALAMAT" value=""></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pekerjaan</label>
                                <input type="text" class="form-control" id="ANGGOTA_PEKERJAAN" name="ANGGOTA_PEKERJAAN" value="">
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No HP</label><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ANGGOTA_HP" name="ANGGOTA_HP" value="" data-parsley-required data-parsley-type="number" required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label><span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="ANGGOTA_EMAIL" name="ANGGOTA_EMAIL" data-parsley-required data-parsley-type="email" required/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Akses Anggota</label><span class="text-danger">*</span></label>
                                <select id="ANGGOTA_AKSES" name="ANGGOTA_AKSES" class="form-control" data-parsley-required required>
                                    <option value="">-- Pilih Akses --</option>
                                    <?php
                                    foreach ($rowakses as $dataAkses) {
                                        extract($dataAkses);
                                        ?>
                                        <option value="<?= $TEXT; ?>"> <?= $TEXT; ?> </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                    <button type="submit" name="submit" id="savedaftaranggota" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="ViewAnggota" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ViewAnggota-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Lihat Data Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" align="center">
                            <label>Foto Anggota </label><br>
                            <div id="loadpic"></div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Daerah<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="viewDAERAH_KEY" name="DAERAH_KEY" value="" data-parsley-required readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Cabang<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="viewCABANG_KEY" name="CABANG_KEY" value="" data-parsley-required readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Ranting</label><span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="viewANGGOTA_RANTING" name="ANGGOTA_RANTING" value="" data-parsley-required readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tingkatan<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="viewTINGKATAN_ID" name="TINGKATAN_ID" value="" data-parsley-required readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>ID Anggota<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="viewANGGOTA_ID" name="ANGGOTA_ID" value="" data-parsley-required readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-pills nav-justified">
                                <li class="active"><a href="#tab-informasianggota" data-toggle="tab">Informasi Anggota</a></li>
                                <li><a href="#tab-riwayatmutasi" data-toggle="tab" class="mutasi">Riwayat Mutasi</a></li>
                                <li><a href="#tab-mutasikas" data-toggle="tab" class="mutasikas">Mutasi Kas</a></li>
                                <li><a href="#tab-riwayatppd" data-toggle="tab" class="riwayatppd">Riwayat PPD</a></li>
                                <li><a href="#tab-riwayatukt" data-toggle="tab" class="riwayatukt">Riwayat UKT</a></li>
                            </ul>
                            <!--/ tab -->
                            <!-- tab content -->
                            <div class="tab-content panel-custom">
                                <div class="tab-pane active" id="tab-informasianggota">
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tanggal Bergabung</label>
                                                <input type="text" class="form-control" id="viewANGGOTA_JOIN" name="ANGGOTA_JOIN" value="" data-parsley-required readonly>
                                            </div> 
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Status Anggota</label>
                                                <input type="text" class="form-control" id="viewANGGOTA_STATUS" name="ANGGOTA_STATUS" value="" data-parsley-required readonly>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nama</label>
                                                <input type="text" class="form-control" id="viewANGGOTA_NAMA" name="ANGGOTA_NAMA" value="" data-parsley-required readonly>
                                            </div> 
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Tempat &amp; Tanggal Lahir</label>
                                                <input type="text" class="form-control" id="viewANGGOTA_TEMPAT_LAHIR" name="ANGGOTA_TEMPAT_LAHIR" value="" data-parsley-required readonly>
                                            </div> 
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label style="color: transparent;">.</label>
                                                <input type="text" class="form-control" id="viewANGGOTA_TANGGAL_LAHIR" name="ANGGOTA_TANGGAL_LAHIR" readonly data-parsley-required/>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Agama</label>
                                                <input type="text" class="form-control" id="viewANGGOTA_AGAMA" name="ANGGOTA_AGAMA" value="" data-parsley-required readonly>
                                            </div> 
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Jenis Kelamin</label>
                                                <input type="text" class="form-control" id="viewANGGOTA_KELAMIN" name="ANGGOTA_KELAMIN" value="" data-parsley-required readonly>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>KTP</label>
                                                <input type="text" class="form-control" id="viewANGGOTA_KTP" name="ANGGOTA_KTP" value="" data-parsley-required readonly>
                                            </div> 
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Alamat</label>
                                                <textarea type="text" rows="4" class="form-control" id="viewANGGOTA_ALAMAT" name="ANGGOTA_ALAMAT" value="" data-parsley-required readonly></textarea>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Pekerjaan</label>
                                                <input type="text" class="form-control" id="viewANGGOTA_PEKERJAAN" name="ANGGOTA_PEKERJAAN" value="" readonly>
                                            </div> 
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>No HP</label>
                                                <input type="text" class="form-control" id="viewANGGOTA_HP" name="ANGGOTA_HP" value="" data-parsley-required readonly>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" class="form-control" id="viewANGGOTA_EMAIL" name="ANGGOTA_EMAIL" data-parsley-required readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Akses Anggota</label><span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="viewANGGOTA_AKSES" name="ANGGOTA_AKSES" value="" data-parsley-required readonly>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab-riwayatmutasi">
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-default" id="demo">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Tabel Riwayat Mutasi</h3>
                                                </div>
                                                <table class="table table-striped table-bordered" id="riwayatmutasi-table">
                                                    <thead>
                                                        <tr>
                                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                            <th>No Dokumen</th>
                                                            <th>Daerah Awal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                            <th>Cabang Awal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                            <th>Daerah Tujuan </th>
                                                            <th>Cabang Tujuan </th>
                                                            <th>Deskripsi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                            <th>Tanggal Efektif</th>
                                                            <th>Input Oleh</th>
                                                            <th>Input Tanggal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="riwayatmutasi">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab-mutasikas">
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-default" id="demo">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Tabel Mutasi Kas</h3>
                                                </div>
                                                <table class="table table-striped table-bordered" id="mutasikas-table">
                                                    <thead>
                                                        <tr>
                                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                            <th>No Dokumen</th>
                                                            <th>Jenis</th>
                                                            <th>Tanggal</th>
                                                            <th>Kategori</th>
                                                            <th>Deskripsi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                            <th>Jumlah</th>
                                                            <th>Saldo</th>
                                                            <th>Input Oleh</th>
                                                            <th>Input Tanggal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="riwayatkas">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab-riwayatppd">
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-default" id="demo">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Tabel Riwayat PPD</h3>
                                                </div>
                                                <table class="table table-striped table-bordered" id="riwayatppd-table">
                                                    <thead>
                                                        <tr>
                                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                            <th>No Dokumen&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                            <th>ID Anggota&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                            <th>Nama Anggota&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                            <th>Daerah &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                            <th>Cabang &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                            <th>Jenis </th>
                                                            <th>Tingkatan </th>
                                                            <th>Tingkatan PPD </th>
                                                            <th>Cabang PPD</th>
                                                            <th>Tanggal</th>
                                                            <th>Deskripsi</th>
                                                            <th>Sertifikat</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="daftariwayatppd">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab-riwayatukt">
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-default" id="demo">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Riwayat UKT</h3>
                                                </div>
                                                <table class="table table-striped table-bordered" id="riwayatukt-table">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>No Dokumen</th>
                                                            <th>ID Anggota </th>
                                                            <th>Nama Anggota </th>
                                                            <th>Daerah </th>
                                                            <th>Cabang </th>
                                                            <th>Ranting </th>
                                                            <th>Tingkatan </th>
                                                            <th>Penyelenggara UKT </th>
                                                            <th>Tanggal UKT </th>
                                                            <th>Total Nilai </th>
                                                            <th>Predikat </th>
                                                            <th>Deskripsi </th>
                                                            <th>Input Oleh</th>
                                                            <th>Input Tanggal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="daftarriwayatukt">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="EditAnggota" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditAnggota-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Edit Data Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row hidden">
                        <div class="col-md-6">
                            <label>Key<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" required id="editANGGOTA_KEY" name="ANGGOTA_KEY" value="" data-parsley-required readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" align="center">
                            <div class="form-group">
                                <!-- PUT THE SAMPLE UPLOAD PHOTO HERE -->
                                <label>Foto Anggota </label><br>
                                <div id="loadpicedit"></div>
                                <div id="preview-container-edit">
                                    <img id="preview-image-edit" src="#" alt="Preview" style="width: 300px; height: 300px;text-align: center;overflow: hidden;position: relative; object-fit:contain" />
                                </div>
                                <br>
                                <div>
                                    <span class="btn btn-inverse mb5 btn-rounded fileinput-button">
                                        <i class="fa-regular fa-image"></i>
                                        <span>Upload Foto...</span>
                                        <!-- The file input field used as target for the file upload widget -->
                                        <input type="file" name="ANGGOTA_PIC[]" id="editANGGOTA_PIC" onchange="previewImageedit(this);" accept="image/*" /> <br>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <?php
                            if ($USER_AKSES == "Administrator") {
                                ?>
                                <div class="short-div">
                                    <div class="form-group">
                                        <label>Daerah<span class="text-danger">*</span></label>
                                        <div id="selectize-wrapper4" style="position: relative;">
                                            <select name="DAERAH_KEY" id="selectize-dropdown4" required="" class="form-control" data-parsley-required>
                                                <option value="">-- Pilih Daerah --</option>
                                                <?php
                                                foreach ($rowd as $rowDaerah) {
                                                    extract($rowDaerah);
                                                    ?>
                                                    <option value="<?= $DAERAH_KEY; ?>"><?= $DAERAH_DESKRIPSI; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div> 
                                </div>
                                <div class="short-div">
                                    <div class="form-group">
                                        <label>Cabang<span class="text-danger">*</span></label>
                                        <div id="selectize-wrapper5" style="position: relative;">
                                            <select name="CABANG_KEY" id="selectize-dropdown5" required="" class="form-control" data-parsley-required>
                                                <option value="">-- Pilih Cabang --</option>
                                            </select>
                                        </div>
                                    </div> 
                                </div>
                                <?php
                            }
                            ?>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Ranting</label><span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="editANGGOTA_RANTING" name="ANGGOTA_RANTING" value="" data-parsley-required required>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tingkatan<span class="text-danger">*</span></label>
                                    <div id="selectize-wrapper6" style="position: relative;">
                                        <select name="TINGKATAN_ID" id="selectize-dropdown6" required="" class="form-control" data-parsley-required>
                                            <option value="">-- Pilih Tingkatan --</option>
                                            <?php
                                            foreach ($rowt as $rowTingkatan) {
                                                extract($rowTingkatan);
                                                ?>
                                                <option value="<?= $TINGKATAN_ID; ?>"><?= $TINGKATAN_NAMA; ?> - <?= $TINGKATAN_SEBUTAN; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>No Urut Anggota<span class="text-danger">*</span></label>
                                    <?php
                                    if ($USER_AKSES == "Administrator") {
                                        ?>
                                        <input type="text" class="form-control" minlength="5" maxlength="5" oninput="validateInput(this)" required id="editANGGOTA_ID" name="ANGGOTA_ID" value="" placeholder="Inputkan 5 digit nomor urut keanggotaan" data-parsley-required>
                                        <div id="warning-message-edit" style="color: red;"></div>
                                        <?php
                                    } else {
                                        ?>
                                        <input type="text" class="form-control" minlength="5" maxlength="5" oninput="validateInput(this)" required id="editANGGOTA_ID" name="ANGGOTA_ID" value="" placeholder="Inputkan 5 digit nomor urut keanggotaan" data-parsley-required readonly>
                                        <div id="warning-message-edit" style="color: red;"></div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Bergabung</label><span class="text-danger">*</span></label>
                                <?php
                                if ($USER_AKSES == "Administrator") {
                                    ?>
                                    <input type="text" class="form-control" id="datepicker45" name="ANGGOTA_JOIN" placeholder="Pilih tanggal" readonly required data-parsley-required/>
                                    <?php
                                } else {
                                    ?>
                                    <input type="text" class="form-control" id="editANGGOTA_JOIN" name="ANGGOTA_JOIN" value="" data-parsley-required readonly>
                                    <?php
                                }
                                ?>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status Anggota</label><span class="text-danger">*</span></label>
                                <select id="editANGGOTA_STATUS" name="ANGGOTA_STATUS" class="form-control" data-parsley-required required>
                                    <option value="0">Aktif</option>
                                    <option value="1">Non Aktif</option>
                                    <option value="2">Mutasi</option>
                                </select>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama</label><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editANGGOTA_NAMA" name="ANGGOTA_NAMA" value="" data-parsley-required data-parsley-maxlength="50" required>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tempat &amp; Tanggal Lahir</label><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editANGGOTA_TEMPAT_LAHIR" name="ANGGOTA_TEMPAT_LAHIR" value="" data-parsley-required required>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label style="color: transparent;">.</label>
                                <input type="text" class="form-control" id="datepicker46" name="ANGGOTA_TANGGAL_LAHIR" placeholder="Pilih tanggal" readonly data-parsley-required required/>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Agama</label><span class="text-danger">*</span></label>
                                <select id="editANGGOTA_AGAMA" name="ANGGOTA_AGAMA" class="form-control" placeholder="Pilih Agama..." data-parsley-required required>
                                    <option value="">Pilih Agama...</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Khonghucu">Khonghucu</option>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jenis Kelamin</label><span class="text-danger">*</span></label>
                                <select id="editANGGOTA_KELAMIN" name="ANGGOTA_KELAMIN" class="form-control" placeholder="Pilih Jenis Kelamin..." data-parsley-required required>
                                    <option value="">Pilih Jenis Kelamin...</option>
                                    <option value="L">Pria</option>
                                    <option value="P">Wanita</option>
                                </select>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>KTP</label><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editANGGOTA_KTP" name="ANGGOTA_KTP" value="" placeholder="Inputkan no KTP" data-parsley-required data-parsley-type="number" required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alamat</label>
                                <textarea type="text" rows="4" class="form-control" id="editANGGOTA_ALAMAT" name="ANGGOTA_ALAMAT" value="" data-parsley-required></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pekerjaan</label>
                                <input type="text" class="form-control" id="editANGGOTA_PEKERJAAN" name="ANGGOTA_PEKERJAAN" value="">
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No HP</label><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editANGGOTA_HP" name="ANGGOTA_HP" value="" data-parsley-required data-parsley-type="number" required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label><span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="editANGGOTA_EMAIL" name="ANGGOTA_EMAIL" data-parsley-required data-parsley-type="email" required/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Akses Anggota</label><span class="text-danger">*</span></label>
                                <select id="editANGGOTA_AKSES" name="ANGGOTA_AKSES" class="form-control" data-parsley-required required>
                                    <option value="">-- Pilih Akses --</option>
                                    <?php
                                    foreach ($rowakses as $dataAkses) {
                                        extract($dataAkses);
                                        ?>
                                        <option value="<?= $TEXT; ?>"> <?= $TEXT; ?> </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                    <button type="submit" name="submit" id="editdaftaranggota" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Upload Anggota (Template) Modal -->
<div id="UploadAnggota" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="uploadAnggotaLabel" aria-hidden="true">
    <form id="UploadAnggota-form" method="post" class="form" enctype="multipart/form-data" data-parsley-validate>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse" id="uploadAnggotaLabel">Upload Template Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        Silakan unggah file template berformat Excel (.xlsx). Unduh formatnya melalui tombol "Download Template".
                    </div>
                    <div class="row">
                        <?php if ($USER_AKSES == "Administrator") { ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Daerah</label>
                                <div id="selectize-wrapper8" style="position: relative;">
                                    <select name="DAERAH_KEY" id="selectize-dropdown8" class="form-control" required data-parsley-required>
                                        <option value="">-- Pilih Daerah --</option>
                                        <?php foreach ($rowd as $rowDaerah) { extract($rowDaerah); ?>
                                            <option value="<?= $DAERAH_KEY; ?>"><?= $DAERAH_DESKRIPSI; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cabang</label>
                                <div id="selectize-wrapper7" style="position: relative;">
                                    <select name="CABANG_KEY" id="selectize-dropdown7" required="" class="form-control" data-parsley-required>
                                        <option value="">-- Pilih Cabang --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <?php } else { ?>
                        <input type="hidden" name="CABANG_KEY" id="CABANG_KEY" value="<?= htmlspecialchars($USER_CABANG, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php } ?>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fileTemplate">File Template Excel (.xlsx)</label>
                                <input type="file" id="fileTemplate" name="fileTemplate" class="form-control" accept=".xlsx" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="importMode">Mode Import</label>
                                <select id="importMode" name="mode" class="form-control" required>
                                    <option value="insert">Penambahan data</option>
                                    <option value="replace">Hapus semua data lama dan tambah baru</option>
                                    <option value="upsert">Perbarui data jika ID sudah ada</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                    <button type="submit" class="btn btn-primary btn-outline mb5 btn-rounded" id="btnUploadAnggota">
                        <span class="ico-upload"></span> Upload
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Duplicate Resolution Modal -->
<div id="DuplicateResolutionModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="duplicateResolutionLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3 class="semibold modal-title text-inverse" id="duplicateResolutionLabel">
                    <i class="fa-solid fa-triangle-exclamation"></i> Data Duplikat Ditemukan
                </h3>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <strong>Perhatian!</strong> Beberapa ID Anggota dalam file template sudah terdaftar di sistem. Silakan pilih data mana yang ingin Anda gunakan untuk setiap duplikat.
                </div>
                <div id="duplicateList">
                    <!-- Duplicate rows will be inserted here dynamically -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-teal btn-outline mb5 btn-rounded" id="btnSelectAllExisting">
                    <i class="fa-solid fa-database"></i> Pilih Semua Data Lama
                </button>
                <button type="button" class="btn btn-inverse btn-outline mb5 btn-rounded" id="btnSelectAllNew">
                    <i class="fa-solid fa-file-import"></i> Pilih Semua Data Baru
                </button>
                <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal">
                    <span class="ico-cancel"></span> Batal
                </button>
                <button type="button" class="btn btn-success btn-outline mb5 btn-rounded" id="btnProceedWithSelection">
                    <i class="fa-solid fa-check"></i> Lanjutkan Upload
                </button>
            </div>
        </div>
    </div>
</div>