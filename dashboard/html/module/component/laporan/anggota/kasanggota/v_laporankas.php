<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];
$USER_KEY = $_SESSION["LOGINKEY_CS"];

if ($USER_AKSES == "Administrator") {
    $getKas = GetQuery("SELECT k.*,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,a.ANGGOTA_RANTING,a.ANGGOTA_ID,a.ANGGOTA_NAMA,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(k.KAS_TANGGAL, '%d %M %Y') FKAS_TANGGAL, DATE_FORMAT(k.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,
    CASE
        WHEN k.KAS_JUMLAH < 0 THEN CONCAT('(', FORMAT(ABS(k.KAS_JUMLAH), 0), ')')
        ELSE FORMAT(k.KAS_JUMLAH, 0)
    END AS FKAS_JUMLAH,
    CASE 
        WHEN k.KAS_DK = 'D' THEN 'Debit'
        ELSE 'Kredit' 
    END AS KAS_DK_DES,
    CASE
        WHEN k.KAS_DK = 'D' THEN 'color: green;'
        ELSE 'color: red;' 
    END AS KAS_COLOR
    FROM t_kas k
    LEFT JOIN m_anggota a ON k.ANGGOTA_ID = a.ANGGOTA_ID AND a.ANGGOTA_STATUS = 0 AND a.DELETION_STATUS = 0
    LEFT JOIN m_anggota a2 ON k.INPUT_BY = a2.ANGGOTA_ID AND a2.ANGGOTA_STATUS = 0 AND a2.DELETION_STATUS = 0
    LEFT JOIN m_cabang c ON k.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
    WHERE k.DELETION_STATUS = 0 AND a.DELETION_STATUS=0
    ORDER BY k.KAS_ID");
    
    $getAnggota = GetQuery("SELECT * FROM m_anggota WHERE ANGGOTA_AKSES <> 'Administrator' AND ANGGOTA_STATUS = 0");
} else {
    $getKas = GetQuery("SELECT k.*,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,a.ANGGOTA_ID,a.ANGGOTA_NAMA,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(k.KAS_TANGGAL, '%d %M %Y') FKAS_TANGGAL, DATE_FORMAT(k.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,
    CASE
        WHEN k.KAS_JUMLAH < 0 THEN CONCAT('(', FORMAT(ABS(k.KAS_JUMLAH), 0), ')')
        ELSE FORMAT(k.KAS_JUMLAH, 0)
    END AS FKAS_JUMLAH,
    CASE 
        WHEN k.KAS_DK = 'D' THEN 'Debit'
        ELSE 'Kredit' 
    END AS KAS_DK_DES,
    CASE
        WHEN k.KAS_DK = 'D' THEN 'color: green;'
        ELSE 'color: red;' 
    END AS KAS_COLOR
    FROM t_kas k
    LEFT JOIN m_anggota a ON k.ANGGOTA_ID = a.ANGGOTA_ID AND a.ANGGOTA_STATUS = 0 AND a.DELETION_STATUS = 0
    LEFT JOIN m_anggota a2 ON k.INPUT_BY = a2.ANGGOTA_ID AND a2.ANGGOTA_STATUS = 0 AND a2.DELETION_STATUS = 0
    LEFT JOIN m_cabang c ON k.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
    WHERE k.DELETION_STATUS = 0 AND a.DELETION_STATUS=0 AND a.CABANG_KEY = '$USER_CABANG' AND a.ANGGOTA_KEY = '$USER_KEY'
    ORDER BY k.KAS_ID");

    $getAnggota = GetQuery("SELECT * FROM m_anggota WHERE ANGGOTA_AKSES <> 'Administrator' AND ANGGOTA_STATUS = 0 AND CABANG_KEY = '$USER_CABANG'");
}

$getDaerah = GetQuery("select * from m_daerah where DELETION_STATUS = 0 order by DAERAH_DESKRIPSI");
$getCabang = GetQuery("select * from m_cabang where DELETION_STATUS = 0");
$getTingkatan = GetQuery("select * from m_tingkatan where DELETION_STATUS = 0");
// Fetch all rows into an array
$rowd = $getDaerah->fetchAll(PDO::FETCH_ASSOC);
$rows = $getCabang->fetchAll(PDO::FETCH_ASSOC);
$rowt = $getTingkatan->fetchAll(PDO::FETCH_ASSOC);
$rowa = $getAnggota->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="panel-group" id="accordion1"> <!-- Input Filter -->
    <div class="panel panel-default">
        <a data-toggle="collapse" data-parent="#accordion1" href="#collapseOne" class="collapsed">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <i class="fa-solid fa-chevron-down"></i> Filter Data Kas Anggota
                </h4>
            </div>
        </a>
        <div id="collapseOne" class="panel-collapse collapse">
            <div class="panel-body">
                <form method="post" class="form filterKasAnggota" id="filterKasAnggota">
                    <?php
                    if ($USER_AKSES == "Administrator") {
                        ?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Daerah</label>
                                    <select name="DAERAH_KEY" id="selectize-select3" class="form-control">
                                        <option value="">-- Pilih Daerah --</option>
                                        <?php
                                        foreach ($rowd as $filterDaerah) {
                                            extract($filterDaerah);
                                            ?>
                                            <option value="<?= $DAERAH_KEY; ?>"><?= $DAERAH_DESKRIPSI; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div> 
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Cabang</label>
                                    <select name="CABANG_KEY" id="selectize-select2" class="form-control">
                                        <option value="">-- Pilih Cabang --</option>
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
                                    <label>Tingkatan</label>
                                    <select name="TINGKATAN_ID" id="selectize-select" class="form-control">
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
                        <?php
                    }
                    ?>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>No Dokumen</label>
                                <input type="text" class="form-control" id="filterKAS_ID" name="KAS_ID" value="" placeholder="Input No Dokumen">
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Jenis Kas</label>
                                <select id="filterKAS_JENIS" name="KAS_JENIS" class="form-control">
                                    <option value="">Tampilkan semua</option>
                                    <option value="Wajib">Iuran Wajib</option>
                                    <option value="Tabungan">Tabungan</option>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Kategori Kas</label>
                                <select id="filterKAS_KATEGORI" name="KAS_KATEGORI" class="form-control">
                                    <option value="">Tampilkan semua</option>
                                    <option value="D">Debit</option>
                                    <option value="K">Kredit</option>
                                </select>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Periode</label>
                                <input type="text" class="form-control" id="datepicker4" name="TANGGAL_AWAL" placeholder="Pilih Tanggal Awal Periode" readonly/>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label style="visibility: hidden;">.</label>
                                <input type="text" class="form-control" id="datepicker5" name="TANGGAL_AKHIR" placeholder="Pilih Tanggal Akhir Periode" readonly/>
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
<div class="row"> <!-- Table Kas Anggota -->
    <div class="col-md-12">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Tabel Kas Anggota</h3>
            </div>
            <table class="table table-striped table-bordered" id="kasanggota-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>No Dokumen</th>
                        <th>ID Anggota </th>
                        <th>Nama </th>
                        <th>Tingkatan </th>
                        <th>Gelar</th>
                        <th>Jenis </th>
                        <th>Tanggal </th>
                        <th>Kategori </th>
                        <th>Deskripsi </th>
                        <th>Jumlah (Rp)</th>
                        <th>Saldo (Rp)</th>
                        <th>Ranting</th>
                        <th>Cabang</th>
                        <th>Daerah</th>
                        <th>Input Oleh</th>
                        <th>Input Tanggal</th>
                    </tr>
                </thead>
                <tbody id="kasanggotadata"></tbody>
            </table>
        </div>
    </div>
</div>
<br><br>
<!--/ END row -->

<div id="ViewKasAnggota" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ViewKasAnggota-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Lihat Data Kas Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" align="center">
                            <div class="short-div">
                                <label>Foto Anggota </label><br>
                                <div id="loadpicview"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Anggota</label>
                                    <input type="text" class="form-control" id="viewANGGOTA_IDNAMA" name="ANGGOTA_KEY" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div hidden">
                                <div class="form-group">
                                    <label>ID Cabang</label>
                                    <input type="text" class="form-control" id="viewCABANG_KEY" name="CABANG_KEY" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Daerah</label>
                                    <input type="text" class="form-control" id="viewDAERAH_DESKRIPSI" name="DAERAH_KEY" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Cabang</label>
                                    <input type="text" class="form-control" id="viewCABANG_DESKRIPSI" name="CABANG_DESKRIPSI" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Sabuk</label>
                                    <input type="text" class="form-control" id="viewTINGKATAN_NAMA" name="TINGKATAN_ID" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tingkatan</label>
                                    <input type="text" class="form-control" id="viewTINGKATAN_SEBUTAN" name="TINGKATAN_SEBUTAN" value="" readonly>
                                </div> 
                            </div>   
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jenis Kas<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="viewKAS_JENIS" name="KAS_JENIS" value="" readonly>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Saldo Awal</label>
                                <div class="input-group">
                                    <span class="input-group-addon">Rp</span>
                                    <input type="text" class="form-control text-right" id="viewKAS_SALDOAWAL" name="KAS_SALDOAWAL" value="" readonly>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Kategori</label>
                                <input type="text" class="form-control" id="viewKAS_DK" name="KAS_DK" value="" readonly>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jumlah</label>
                                <div class="input-group">
                                    <span class="input-group-addon">Rp</span>
                                    <input type="text" class="form-control text-right" id="viewKAS_JUMLAH" name="KAS_JUMLAH" value="" readonly>
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Saldo Akhir</label>
                                <div class="input-group">
                                    <span class="input-group-addon">Rp</span>
                                    <input type="text" class="form-control text-right" id="viewKAS_SALDOAKHIR" name="KAS_SALDOAKHIR" value="" readonly>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea type="text" rows="4" class="form-control" id="viewKAS_DESKRIPSI" name="KAS_DESKRIPSI" value="" readonly></textarea>
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