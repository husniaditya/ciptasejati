<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];
$USER_DAERAH = $_SESSION["LOGINDAR_CS"];

if ($USER_AKSES == "Administrator") {
    $getAnggotadata = GetQuery("SELECT a.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,t.TINGKATAN_NAMA,t.TINGKATAN_GELAR,t.TINGKATAN_SEBUTAN,DATE_FORMAT(a.ANGGOTA_TANGGAL_LAHIR, '%d %M %Y') TGL_LAHIR,DATE_FORMAT(a.ANGGOTA_JOIN, '%d %M %Y') TGL_JOIN,DATE_FORMAT(a.ANGGOTA_RESIGN, '%d %M %Y') TGL_RESIGN,RIGHT(a.ANGGOTA_ID,3) SHORT_ID, CASE WHEN ANGGOTA_STATUS = 0 THEN 'Aktif' WHEN ANGGOTA_STATUS = 1 THEN 'Non Aktif' ELSE 'Mutasi' END STATUS_DES FROM m_anggota a
    LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID");
} else if ($USER_AKSES == "Pengurus Daerah") {
    $getAnggotadata = GetQuery("SELECT a.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,t.TINGKATAN_NAMA,t.TINGKATAN_GELAR,t.TINGKATAN_SEBUTAN,DATE_FORMAT(a.ANGGOTA_TANGGAL_LAHIR, '%d %M %Y') TGL_LAHIR,DATE_FORMAT(a.ANGGOTA_JOIN, '%d %M %Y') TGL_JOIN,DATE_FORMAT(a.ANGGOTA_RESIGN, '%d %M %Y') TGL_RESIGN,RIGHT(a.ANGGOTA_ID,3) SHORT_ID, CASE WHEN ANGGOTA_STATUS = 0 THEN 'Aktif' WHEN ANGGOTA_STATUS = 1 THEN 'Non Aktif' ELSE 'Mutasi' END STATUS_DES FROM m_anggota a
    LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
    WHERE c.DAERAH_KEY = '$USER_DAERAH'");
}
else {
    $getAnggotadata = GetQuery("SELECT a.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,t.TINGKATAN_NAMA,t.TINGKATAN_GELAR,t.TINGKATAN_SEBUTAN,DATE_FORMAT(a.ANGGOTA_TANGGAL_LAHIR, '%d %M %Y') TGL_LAHIR,DATE_FORMAT(a.ANGGOTA_JOIN, '%d %M %Y') TGL_JOIN,DATE_FORMAT(a.ANGGOTA_RESIGN, '%d %M %Y') TGL_RESIGN,RIGHT(a.ANGGOTA_ID,3) SHORT_ID, CASE WHEN ANGGOTA_STATUS = 0 THEN 'Aktif' WHEN ANGGOTA_STATUS = 1 THEN 'Non Aktif' ELSE 'Mutasi' END STATUS_DES FROM m_anggota a
    LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
    WHERE a.CABANG_KEY = '$USER_CABANG' and a.ANGGOTA_AKSES != 'Administrator'");
}

$getDaerah = GetQuery("select * from m_daerah where DELETION_STATUS = 0");
$getCabang = GetQuery("select * from m_cabang where DELETION_STATUS = 0");
$getTingkatan = GetQuery("select * from m_tingkatan where DELETION_STATUS = 0");
// Fetch all rows into an array
$rowd = $getDaerah->fetchAll(PDO::FETCH_ASSOC);
$rows = $getCabang->fetchAll(PDO::FETCH_ASSOC);
$rowt = $getTingkatan->fetchAll(PDO::FETCH_ASSOC);
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
                        <th>L/P</th>
                        <th>Sabuk </th>
                        <th>Tingkatan </th>
                        <th>Gelar </th>
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
                                    <label>No Urut Anggota<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="viewANGGOTA_ID" name="ANGGOTA_ID" value="" data-parsley-required readonly>
                                </div>
                            </div>
                        </div>
                    </div>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jenis Kelamin</label>
                                <input type="text" class="form-control" id="viewANGGOTA_KELAMIN" name="ANGGOTA_KELAMIN" value="" data-parsley-required readonly>
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