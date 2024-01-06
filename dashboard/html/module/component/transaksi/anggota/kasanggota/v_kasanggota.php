<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];

if ($USER_AKSES == "Administrator") {
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
    LEFT JOIN m_anggota a ON k.ANGGOTA_KEY = a.ANGGOTA_KEY
    LEFT JOIN m_anggota a2 ON k.INPUT_BY = a2.ANGGOTA_ID
    LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
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
    LEFT JOIN m_anggota a ON k.ANGGOTA_KEY = a.ANGGOTA_KEY
    LEFT JOIN m_anggota a2 ON k.INPUT_BY = a2.ANGGOTA_ID
    LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
    WHERE k.DELETION_STATUS = 0 AND a.DELETION_STATUS=0 and a.CABANG_KEY = '$USER_CABANG'
    ORDER BY k.KAS_ID");

    $getAnggota = GetQuery("SELECT * FROM m_anggota WHERE ANGGOTA_AKSES <> 'Administrator' AND ANGGOTA_STATUS = 0 AND CABANG_KEY = '$USER_CABANG'");
}

$getDaerah = GetQuery("select * from m_daerah where DELETION_STATUS = 0");
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
                    <div class="row">
                        <?php
                        if ($USER_AKSES == "Administrator") {
                            ?>
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
                            <?php
                        }
                        ?>
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
<div class="row"> <!-- Add Data Button -->
    <div class="col-lg-12">
        <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddKasAnggota btn btn-inverse btn-outline mb5 btn-rounded" href="#AddKasAnggota"><i class="ico-plus2"></i> Tambah Data Kas Anggota</a>
    </div>
</div>
<br>
<!--/ END row -->

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
                        <th>Daerah</th>
                        <th>Cabang</th>
                        <th>ID Anggota </th>
                        <th>Nama </th>
                        <th>Tingkatan </th>
                        <th>Gelar</th>
                        <th>Jenis </th>
                        <th>Tanggal </th>
                        <th>Kategori </th>
                        <th>Deskripsi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th>Jumlah (Rp)</th>
                        <th>Saldo (Rp)</th>
                        <th>Input Oleh</th>
                        <th>Input Tanggal</th>
                    </tr>
                </thead>
                <tbody id="kasanggotadata">
                    <?php
                    while ($rowKas = $getKas->fetch(PDO::FETCH_ASSOC)) {
                        extract($rowKas);
                        $getSaldo = GetQuery("SELECT 
                            CASE
                                WHEN SUM(KAS_JUMLAH) < 0 THEN CONCAT('(', FORMAT(ABS(SUM(KAS_JUMLAH)), 0), ')')
                                ELSE FORMAT(SUM(KAS_JUMLAH), 0)
                            END AS FKAS_SALDO
                        FROM 
                            t_kas
                        WHERE 
                            DELETION_STATUS = 0 
                            AND ANGGOTA_KEY = '$ANGGOTA_KEY' 
                            AND KAS_ID <= '$KAS_ID' 
                            AND KAS_JENIS = '$KAS_JENIS';
                        ");

                        while ($rowSaldo = $getSaldo->fetch(PDO::FETCH_ASSOC)) {
                            extract($rowSaldo);
                        }
                        ?>
                        <tr>
                            <td align="center">
                                <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                                    <div class="btn-group" style="margin-bottom:5px;">
                                        <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a data-toggle="modal" href="#ViewKasAnggota" class="open-ViewKasAnggota" style="color:#222222;" data-id="<?= $KAS_ID; ?>" data-anggota="<?= $ANGGOTA_KEY; ?>" data-jenis="<?= $KAS_JENIS; ?>"><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                                            <li><a data-toggle="modal" href="#EditKasAnggota" class="open-EditKasAnggota" style="color:#00a5d2;" data-id="<?= $KAS_ID; ?>" data-anggota="<?= $ANGGOTA_KEY; ?>" data-jenis="<?= $KAS_JENIS; ?>"><span class="ico-edit"></span> Ubah</a></li>
                                            <li class="divider"></li>
                                            <li><a href="<?= $KAS_FILE; ?>" target="_blank" style="color: darkgoldenrod;"><i class="fa-solid fa-print"></i> Cetak</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#" onclick="eventkas('<?= $KAS_ID;?>','delete')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td><?= $KAS_ID; ?></td>
                            <td align="center"><?= $DAERAH_DESKRIPSI; ?></td>
                            <td align="center"><?= $CABANG_DESKRIPSI; ?></td>
                            <td align="center"><?= $ANGGOTA_ID; ?></td>
                            <td align="center"><?= $ANGGOTA_NAMA; ?></td>
                            <td align="center"><?= $TINGKATAN_NAMA; ?></td>
                            <td align="center"><?= $TINGKATAN_SEBUTAN; ?></td>
                            <td align="center"><?= $KAS_JENIS; ?></td>
                            <td align="center"><?= $FKAS_TANGGAL; ?></td>
                            <td align="center"><?= $KAS_DK_DES; ?></td>
                            <td><?= $KAS_DESKRIPSI; ?></td>
                            <td align="right" style="<?= $KAS_COLOR; ?>"><?= $FKAS_JUMLAH; ?></td>
                            <td align="right"><?= $FKAS_SALDO; ?></td>
                            <td><?= $INPUT_BY; ?></td>
                            <td><?= $INPUT_DATE; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<br><br>
<!--/ END row -->

<div id="AddKasAnggota" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="AddKasAnggota-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Tambah Data Kas Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" align="center">
                            <div class="short-div">
                                <label>Foto Anggota </label><br>
                                <div id="loadpic"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Anggota</label>
                                    <div id="selectize-wrapper" style="position: relative;">
                                        <select name="ANGGOTA_KEY" id="selectize-dropdown" required="" class="form-control" onchange="populateFields()" data-parsley-required>
                                            <option value="">-- Pilih Anggota --</option>
                                            <?php
                                            foreach ($rowa as $rowAnggota) {
                                                extract($rowAnggota);
                                                ?>
                                                <option value="<?= $ANGGOTA_KEY; ?>"><?= $ANGGOTA_ID; ?> - <?= $ANGGOTA_NAMA; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <div class="short-div hidden">
                                <div class="form-group">
                                    <label>ID Cabang</label>
                                    <input type="text" class="form-control" id="CABANG_AWAL" name="CABANG_AWAL" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Daerah</label>
                                    <input type="text" class="form-control" id="DAERAH_AWAL" name="DAERAH_AWAL" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Cabang</label>
                                    <input type="text" class="form-control" id="CABANG_DESKRIPSI" name="CABANG_DESKRIPSI" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Sabuk</label>
                                    <input type="text" class="form-control" id="TINGKATAN_ID" name="TINGKATAN_ID" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tingkatan</label>
                                    <input type="text" class="form-control" id="TINGKATAN_SEBUTAN" name="TINGKATAN_SEBUTAN" value="" readonly>
                                </div> 
                            </div>   
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jenis Kas<span class="text-danger">*</span></label>
                                <select id="KAS_JENIS" name="KAS_JENIS" class="form-control" placeholder="Pilih Jenis..." data-parsley-required required onchange="populateSaldoAwal()">
                                    <option value="">Pilih Jenis...</option>
                                    <option value="Wajib">Iuran Wajib</option>
                                    <option value="Tabungan">Tabungan</option>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Saldo Awal</label>
                                <div class="input-group">
                                    <span class="input-group-addon">Rp</span>
                                    <input type="text" class="form-control text-right" id="KAS_SALDOAWAL" name="KAS_SALDOAWAL" value="" readonly>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Kategori<span class="text-danger">*</span></label>
                                <select id="KAS_DK" name="KAS_DK" class="form-control" placeholder="Pilih Kategori..." data-parsley-required required>
                                    <option value="">Pilih Kategori...</option>
                                    <option value="D">Debit - Pemasukan</option>
                                    <option value="K">Kredit - Pengeluaran</option>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jumlah<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon">Rp</span>
                                    <input type="text" class="form-control text-right" id="KAS_JUMLAH" name="KAS_JUMLAH" value="" required data-parsley-required>
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Saldo Akhir</label>
                                <div class="input-group">
                                    <span class="input-group-addon">Rp</span>
                                    <input type="text" class="form-control text-right" id="KAS_SALDOAKHIR" name="KAS_SALDOAKHIR" value="" readonly>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea type="text" rows="4" class="form-control" id="KAS_DESKRIPSI" name="KAS_DESKRIPSI" value=""></textarea>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Cancel</button>
                    <button type="submit" name="submit" id="savekasanggota" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>

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
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="EditKasAnggota" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditKasAnggota-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Edit Data Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row hidden">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>ID KAS</label>
                                <input type="text" class="form-control" id="editKAS_ID" name="KAS_ID" value="" readonly>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" align="center">
                            <div class="short-div">
                                <label>Foto Anggota </label><br>
                                <div id="loadpicedit"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Anggota</label>
                                    <div id="selectize-wrapper4" style="position: relative;">
                                        <select name="ANGGOTA_KEY" id="selectize-dropdown4" required="" class="form-control" onchange="populateFieldsEdit()" data-parsley-required>
                                            <option value="">-- Pilih Anggota --</option>
                                            <?php
                                            foreach ($rowa as $rowAnggota) {
                                                extract($rowAnggota);
                                                ?>
                                                <option value="<?= $ANGGOTA_KEY; ?>"><?= $ANGGOTA_ID; ?> - <?= $ANGGOTA_NAMA; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <div class="short-div hidden">
                                <div class="form-group">
                                    <label>ID Cabang</label>
                                    <input type="text" class="form-control" id="editCABANG_AWAL" name="CABANG_KEY" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Daerah</label>
                                    <input type="text" class="form-control" id="editDAERAH_AWAL_DES" name="DAERAH_KEY" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Cabang</label>
                                    <input type="text" class="form-control" id="editCABANG_AWAL_DES" name="CABANG_DESKRIPSI" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Sabuk</label>
                                    <input type="text" class="form-control" id="editTINGKATAN_NAMA" name="TINGKATAN_ID" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tingkatan</label>
                                    <input type="text" class="form-control" id="editTINGKATAN_SEBUTAN" name="TINGKATAN_SEBUTAN" value="" readonly>
                                </div> 
                            </div>   
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jenis Kas<span class="text-danger">*</span></label>
                                <select id="editKAS_JENIS" name="KAS_JENIS" class="form-control" placeholder="Pilih Jenis..." data-parsley-required required onchange="populateSaldoAwalEdit()">
                                    <option value="">Pilih Jenis...</option>
                                    <option value="Wajib">Iuran Wajib</option>
                                    <option value="Tabungan">Tabungan</option>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Saldo Awal</label>
                                <div class="input-group">
                                    <span class="input-group-addon">Rp</span>
                                    <input type="text" class="form-control text-right" id="editKAS_SALDOAWAL" name="KAS_SALDOAWAL" value="" readonly>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Kategori<span class="text-danger">*</span></label>
                                <select id="editKAS_DK" name="KAS_DK" class="form-control" placeholder="Pilih Kategori..." data-parsley-required required>
                                    <option value="">Pilih Kategori...</option>
                                    <option value="D">Debit - Pemasukan</option>
                                    <option value="K">Kredit - Pengeluaran</option>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jumlah<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon">Rp</span>
                                    <input type="text" class="form-control text-right" id="editKAS_JUMLAH" name="KAS_JUMLAH" value="" required data-parsley-required="">
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Saldo Akhir</label>
                                <div class="input-group">
                                    <span class="input-group-addon">Rp</span>
                                    <input type="text" class="form-control text-right" id="editKAS_SALDOAKHIR" name="KAS_SALDOAKHIR" value="" readonly>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea type="text" rows="4" class="form-control" id="editKAS_DESKRIPSI" name="KAS_DESKRIPSI" value=""></textarea>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                    <button type="submit" name="submit" id="editkasanggota" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>