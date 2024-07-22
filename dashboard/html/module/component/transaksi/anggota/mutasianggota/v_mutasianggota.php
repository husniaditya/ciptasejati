<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];

if ($USER_AKSES == "Administrator") {
    $getMutasi = GetQuery("SELECT t.MUTASI_ID,daeawal.DAERAH_KEY AS DAERAH_AWAL_KEY,daeawal.DAERAH_DESKRIPSI AS DAERAH_AWAL_DES,t.CABANG_AWAL,cabawal.CABANG_DESKRIPSI AS CABANG_AWAL_DES,daetujuan.DAERAH_KEY AS DAERAH_TUJUAN_KEY,daetujuan.DAERAH_DESKRIPSI AS DAERAH_TUJUAN_DES,t.CABANG_TUJUAN,cabtujuan.CABANG_DESKRIPSI AS CABANG_TUJUAN_DES,a.ANGGOTA_KEY,a.ANGGOTA_ID,a.ANGGOTA_NAMA,t2.TINGKATAN_NAMA,t2.TINGKATAN_SEBUTAN,t.MUTASI_DESKRIPSI,t.MUTASI_TANGGAL,t.MUTASI_STATUS,t.MUTASI_STATUS_TANGGAL,t.MUTASI_APPROVE_TANGGAL,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(t.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(t.MUTASI_TANGGAL, '%d %M %Y %H:%i') MUTASI_TGL, DATE_FORMAT(t.MUTASI_STATUS_TANGGAL, '%d %M %Y %H:%i') MUTASI_STATUS_TANGGAL, DATE_FORMAT(t.MUTASI_TANGGAL, '%d %M %Y') TANGGAL_EFEKTIF,t.MUTASI_FILE,
    CASE 
        WHEN t.MUTASI_STATUS = '0' THEN 'Menunggu' 
        WHEN t.MUTASI_STATUS = '1' THEN 'Disetujui' 
        ELSE 'Ditolak' 
    END AS MUTASI_STATUS_DES,
    CASE
        WHEN t.MUTASI_STATUS = '0' THEN 'badge badge-inverse'
        WHEN t.MUTASI_STATUS = '1' THEN 'badge badge-success' 
        ELSE 'badge badge-danger' 
    END AS MUTASI_BADGE,
    CASE
        WHEN t.MUTASI_STATUS = '0' THEN 'fa-solid fa-spinner fa-spin'
        WHEN t.MUTASI_STATUS = '1' THEN 'fa-solid fa-check' 
        ELSE 'fa-solid fa-xmark' 
    END AS MUTASI_CLASS
    FROM t_mutasi t
    LEFT JOIN m_anggota a ON t.ANGGOTA_KEY = a.ANGGOTA_KEY
    LEFT JOIN m_anggota a2 ON t.INPUT_BY = a2.ANGGOTA_ID
    LEFT JOIN m_cabang cabawal ON t.CABANG_AWAL = cabawal.CABANG_KEY
    LEFT JOIN m_daerah daeawal ON cabawal.DAERAH_KEY = daeawal.DAERAH_KEY
    LEFT JOIN m_cabang cabtujuan ON t.CABANG_TUJUAN = cabtujuan.CABANG_KEY
    LEFT JOIN m_daerah daetujuan ON cabtujuan.DAERAH_KEY = daetujuan.DAERAH_KEY
    left join m_tingkatan t2 on a.TINGKATAN_ID = t2.TINGKATAN_ID
    WHERE t.DELETION_STATUS = 0
    ORDER BY t.MUTASI_STATUS ASC, t.MUTASI_TANGGAL DESC");

    $getAnggota = GetQuery("SELECT * FROM m_anggota WHERE ANGGOTA_AKSES <> 'Administrator' AND ANGGOTA_STATUS = 0");
} else {
    $getMutasi = GetQuery("SELECT t.MUTASI_ID,daeawal.DAERAH_KEY AS DAERAH_AWAL_KEY,daeawal.DAERAH_DESKRIPSI AS DAERAH_AWAL_DES,t.CABANG_AWAL,cabawal.CABANG_DESKRIPSI AS CABANG_AWAL_DES,daetujuan.DAERAH_KEY AS DAERAH_TUJUAN_KEY,daetujuan.DAERAH_DESKRIPSI AS DAERAH_TUJUAN_DES,t.CABANG_TUJUAN,cabtujuan.CABANG_DESKRIPSI AS CABANG_TUJUAN_DES,a.ANGGOTA_KEY,a.ANGGOTA_ID,a.ANGGOTA_NAMA,t2.TINGKATAN_NAMA,t2.TINGKATAN_SEBUTAN,t.MUTASI_DESKRIPSI,t.MUTASI_TANGGAL,t.MUTASI_STATUS,t.MUTASI_STATUS_TANGGAL,t.MUTASI_APPROVE_TANGGAL,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(t.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(t.MUTASI_TANGGAL, '%d %M %Y %H:%i') MUTASI_TGL, DATE_FORMAT(t.MUTASI_STATUS_TANGGAL, '%d %M %Y %H:%i') MUTASI_STATUS_TANGGAL, DATE_FORMAT(t.MUTASI_TANGGAL, '%d %M %Y') TANGGAL_EFEKTIF,t.MUTASI_FILE,
    CASE 
        WHEN t.MUTASI_STATUS = '0' THEN 'Menunggu' 
        WHEN t.MUTASI_STATUS = '1' THEN 'Disetujui' 
        ELSE 'Ditolak' 
    END AS MUTASI_STATUS_DES,
    CASE
        WHEN t.MUTASI_STATUS = '0' THEN 'badge badge-inverse'
        WHEN t.MUTASI_STATUS = '1' THEN 'badge badge-success' 
        ELSE 'badge badge-danger' 
    END AS MUTASI_BADGE,
    CASE
        WHEN t.MUTASI_STATUS = '0' THEN 'fa-solid fa-spinner fa-spin'
        WHEN t.MUTASI_STATUS = '1' THEN 'fa-solid fa-check' 
        ELSE 'fa-solid fa-xmark' 
    END AS MUTASI_CLASS
    FROM t_mutasi t
    LEFT JOIN m_anggota a ON t.ANGGOTA_KEY = a.ANGGOTA_KEY
    LEFT JOIN m_anggota a2 ON t.INPUT_BY = a2.ANGGOTA_ID
    LEFT JOIN m_cabang cabawal ON t.CABANG_AWAL = cabawal.CABANG_KEY
    LEFT JOIN m_daerah daeawal ON cabawal.DAERAH_KEY = daeawal.DAERAH_KEY
    LEFT JOIN m_cabang cabtujuan ON t.CABANG_TUJUAN = cabtujuan.CABANG_KEY
    LEFT JOIN m_daerah daetujuan ON cabtujuan.DAERAH_KEY = daetujuan.DAERAH_KEY
    left join m_tingkatan t2 on a.TINGKATAN_ID = t2.TINGKATAN_ID
    WHERE t.DELETION_STATUS = 0 and (t.CABANG_AWAL = '$USER_CABANG' or t.CABANG_TUJUAN = '$USER_CABANG')
    ORDER BY t.MUTASI_STATUS ASC, t.MUTASI_TANGGAL DESC");

    $getAnggota = GetQuery("SELECT * FROM m_anggota WHERE ANGGOTA_AKSES <> 'Administrator' AND ANGGOTA_STATUS = 0 and CABANG_KEY = '$USER_CABANG'");
}

$getDaerah = GetQuery("select * from m_daerah where DELETION_STATUS = 0 order by DAERAH_DESKRIPSI asc");
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
                    <i class="fa-solid fa-chevron-down"></i> Filter Data Mutasi Anggota
                </h4>
            </div>
        </a>
        <div id="collapseOne" class="panel-collapse collapse">
            <div class="panel-body">
                <form method="post" class="form filterMutasiAnggota" id="filterMutasiAnggota">
                    <div class="row">
                        <?php
                        if ($USER_AKSES == "Administrator") {
                            ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Daerah Awal</label>
                                    <select name="DAERAH_AWAL_KEY" id="selectize-select3" required="" class="form-control" data-parsley-required>
                                        <option value="">-- Pilih Daerah Awal --</option>
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
                                    <label>Cabang Awal</label>
                                    <select name="CABANG_AWAL_KEY" id="selectize-select2" required="" class="form-control" data-parsley-required>
                                        <option value="">-- Pilih Cabang Awal --</option>
                                    </select>
                                </div> 
                            </div>
                            <?php
                        }
                        ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Daerah Tujuan</label>
                                <select name="DAERAH_TUJUAN_KEY" id="selectize-select4" required="" class="form-control" data-parsley-required>
                                    <option value="">-- Pilih Daerah Tujuan --</option>
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
                                <label>Cabang Tujuan</label>
                                <select name="CABANG_TUJUAN_KEY" id="selectize-select5" required="" class="form-control" data-parsley-required>
                                    <option value="">-- Pilih Cabang Tujuan --</option>
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status Mutasi</label>
                                <select id="filterMUTASI_STATUS" name="MUTASI_STATUS" class="form-control"  data-parsley-required required>
                                    <option value="">Tampilkan semua</option>
                                    <option value="0">Menunggu</option>
                                    <option value="1">Disetujui</option>
                                    <option value="2">Ditolak</option>
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
<div class="row"> <!-- Add Data Button -->
    <div class="col-lg-12">
        <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddMutasiAnggota btn btn-inverse btn-outline mb5 btn-rounded" href="#AddMutasiAnggota"><i class="ico-plus2"></i> Tambah Data Mutasi Anggota</a>
    </div>
</div>
<br>
<!--/ END row -->

<!-- START row -->
<div class="row"> <!-- Table Mutasi Anggota -->
    <div class="col-md-12">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Tabel Mutasi Anggota</h3>
            </div>
            <table class="table table-striped table-bordered" id="mutasianggota-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>No Dokumen</th>
                        <th>ID Anggota </th>
                        <th>Nama </th>
                        <th>Tingkatan </th>
                        <th>Gelar</th>
                        <th>Daerah Awal </th>
                        <th>Cabang Awal </th>
                        <th>Daerah Tujuan </th>
                        <th>Cabang Tujuan </th>
                        <th>Deskripsi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th>Tanggal Efektif</th>
                        <th>Input Oleh</th>
                        <th>Input Tanggal</th>
                    </tr>
                </thead>
                <tbody id="mutasianggotadata">
                    <?php
                    while ($rowMutasi = $getMutasi->fetch(PDO::FETCH_ASSOC)) {
                        extract($rowMutasi);
                        ?>
                        <tr>
                            <td align="center">
                                <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                                    <div class="btn-group" style="margin-bottom:5px;">
                                        <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a data-toggle="modal" href="#ViewMutasiAnggota" class="open-ViewMutasiAnggota" style="color:#222222;" data-id="<?= $MUTASI_ID; ?>" data-anggota="<?= $ANGGOTA_KEY; ?>" data-cabang="<?= $CABANG_KEY; ?>"><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                                            <?php
                                            if ($MUTASI_STATUS == 0 && ($USER_AKSES == "Administrator" || $USER_AKSES == "Koordinator")) {
                                                if ($USER_AKSES == "Administrator") {
                                                    ?>
                                                    <li><a data-toggle="modal" href="#EditMutasiAnggota" class="open-EditMutasiAnggota" style="color:#00a5d2;" data-id="<?= $MUTASI_ID; ?>" data-anggota="<?= $ANGGOTA_KEY; ?>" data-cabang="<?= $CABANG_KEY; ?>"><span class="ico-edit"></span> Ubah</a></li>
                                                    <?php
                                                }
                                                if ($USER_AKSES == "Koordinator" && $USER_CABANG == $CABANG_AWAL) {
                                                    ?>
                                                    <li><a data-toggle="modal" href="#EditMutasiAnggota" class="open-EditMutasiAnggota" style="color:#00a5d2;" data-id="<?= $MUTASI_ID; ?>" data-anggota="<?= $ANGGOTA_KEY; ?>" data-cabang="<?= $CABANG_KEY; ?>"><span class="ico-edit"></span> Ubah</a></li>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            <li><a href="assets/print/transaksi/mutasi/print_mutasi.php?id=<?= encodeIdToBase64($MUTASI_ID); ?>" target="_blank" style="color: darkgoldenrod;"><i class="fa-solid fa-print"></i> Cetak</a></li>
                                            <?php
                                            if ($USER_AKSES == "Administrator" && $MUTASI_STATUS <> 0) {
                                                ?>
                                                <li class="divider"></li>
                                                <li><a href="#" onclick="eventmutasi('<?= $MUTASI_ID;?>','reset')" style="color:#dimgrey;"><i class="fa-solid fa-clock-rotate-left"></i> Reset Persetujuan</a></li>
                                                <?php
                                            }
                                            ?>
                                            <li class="divider"></li>
                                            <li><a href="#" onclick="eventmutasi('<?= $MUTASI_ID;?>','delete')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td><?= $MUTASI_ID; ?> <br> <span class="<?= $MUTASI_BADGE; ?>"><i class="<?= $MUTASI_CLASS; ?>"></i> <?= $MUTASI_STATUS_DES; ?></span></td>
                            <td align="center"><?= $ANGGOTA_ID; ?></td>
                            <td align="center"><?= $ANGGOTA_NAMA; ?></td>
                            <td align="center"><?= $TINGKATAN_NAMA; ?></td>
                            <td align="center"><?= $TINGKATAN_SEBUTAN; ?></td>
                            <td align="center"><?= $DAERAH_AWAL_DES; ?></td>
                            <td align="center"><?= $CABANG_AWAL_DES; ?></td>
                            <td align="center"><?= $DAERAH_TUJUAN_DES; ?></td>
                            <td align="center"><?= $CABANG_TUJUAN_DES; ?></td>
                            <td><?= $MUTASI_DESKRIPSI; ?></td>
                            <td align="center"><?= $TANGGAL_EFEKTIF; ?></td>
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

<div id="AddMutasiAnggota" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="AddMutasiAnggota-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Tambah Data Mutasi Anggota</h3>
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
                                        <div id="selectize-wrapper10" style="position: relative;">
                                            <select name="CABANG_KEY" id="selectize-dropdown10" required="" class="form-control" data-parsley-required>
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
                                    <label>Anggota</label>
                                    <?php
                                    if ($USER_AKSES == "Administrator") {
                                        ?>
                                        <div id="selectize-wrapper" style="position: relative;">
                                            <select name="ANGGOTA_ID" id="selectize-dropdown" required="" class="form-control" onchange="populateFields()" data-parsley-required>
                                                <option value="">-- Pilih Anggota --</option>
                                            </select>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="short-div hidden">
                                            <div class="form-group">
                                                <label>Cabang Key</label>
                                                <input type="text" class="form-control" id="CABANG_KEY" name="CABANG_KEY" value="<?= $USER_CABANG; ?>" readonly>
                                            </div> 
                                        </div>
                                        <div id="selectize-wrapper" style="position: relative;">
                                            <select name="ANGGOTA_ID" id="selectize-dropdown" required="" class="form-control" onchange="populateFields()" data-parsley-required>
                                                <option value="">-- Pilih Anggota --</option>
                                                <?php
                                                foreach ($rowa as $rowAnggota) {
                                                    extract($rowAnggota);
                                                    ?>
                                                    <option value="<?= $ANGGOTA_ID; ?>"><?= $ANGGOTA_ID; ?> - <?= $ANGGOTA_NAMA; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div> 
                            </div>
                            <div class="short-div hidden">
                                <div class="form-group">
                                    <label>Anggota Key</label>
                                    <input type="text" class="form-control" id="ANGGOTA_KEY" name="ANGGOTA_KEY" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div hidden">
                                <div class="form-group">
                                    <label>ID Cabang</label>
                                    <input type="text" class="form-control" id="CABANG_AWAL" name="CABANG_AWAL" value="" readonly>
                                </div> 
                            </div>
                            <?php
                            if ($USER_AKSES <> "Administrator") {
                                ?>
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
                                <?php
                            }
                            ?>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Daerah Tujuan<span class="text-danger">*</span></label>
                                <div id="selectize-wrapper2" style="position: relative;">
                                    <select name="DAERAH_TUJUAN" id="selectize-dropdown2" required="" class="form-control" data-parsley-required>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cabang Tujuan<span class="text-danger">*</span></label>
                                <div id="selectize-wrapper3" style="position: relative;">
                                    <select name="CABANG_TUJUAN" id="selectize-dropdown3" required="" class="form-control" data-parsley-required>
                                        <option value="">-- Pilih Cabang --</option>
                                    </select>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Deskripsi<span class="text-danger">*</span></label>
                                <textarea type="text" rows="4" class="form-control" id="MUTASI_DESKRIPSI" name="MUTASI_DESKRIPSI" value="" data-parsley-required required></textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Efektif<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="datepicker4" name="MUTASI_TANGGAL" placeholder="Pilih tanggal" readonly data-parsley-required required/>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                    <button type="submit" name="submit" id="savemutasianggota" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="ViewMutasiAnggota" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ViewMutasiAnggota-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Lihat Data Mutasi Anggota</h3>
                </div>
                <div class="modal-body">
                    <h5 class="text-center" id="viewMUTASI_STATUS_DES"></h5><br>
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <div class="form-group">
                                <label>Diajukan Oleh</label>
                                <p id="viewINPUT_BY"></p>
                            </div> 
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <div class="form-group">
                                <label>Tanggal Pengajuan</label>
                                <p id="viewINPUT_DATE"></p>
                            </div> 
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <div class="form-group">
                                <label>Disetujui Oleh</label>
                                <p id="viewAPPROVE_BY"></p>
                            </div> 
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <div class="form-group">
                                <label>Tanggal persetujuan</label>
                                <p id="viewMUTASI_APP_TANGGAL"></p>
                            </div> 
                        </div>
                    </div>
                    <hr>
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
                                    <label>ID - Nama Anggota</label>
                                    <input type="text" class="form-control" id="viewANGGOTA_IDNAMA" name="ANGGOTA_ID" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div hidden">
                                <div class="form-group">
                                    <label>ID Cabang</label>
                                    <input type="text" class="form-control" id="viewCABANG_AWAL" name="CABANG_AWAL" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Daerah</label>
                                    <input type="text" class="form-control" id="viewDAERAH_AWAL_DES" name="DAERAH_AWAL" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Cabang</label>
                                    <input type="text" class="form-control" id="viewCABANG_AWAL_DES" name="CABANG_DESKRIPSI" value="" readonly>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Daerah Tujuan</label>
                                <input type="text" class="form-control" id="viewDAERAH_TUJUAN_DES" name="DAERAH_TUJUAN" value="" readonly>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cabang Tujuan</label>
                                <input type="text" class="form-control" id="viewCABANG_TUJUAN_DES" name="CABANG_TUJUAN" value="" readonly>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea type="text" rows="4" class="form-control" id="viewMUTASI_DESKRIPSI" name="MUTASI_DESKRIPSI" value="" data-parsley-required readonly></textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Efektif</label>
                                <input type="text" class="form-control" id="viewTANGGAL_EFEKTIF" name="MUTAS_TANGGAL" value="" readonly>
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

<div id="EditMutasiAnggota" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditMutasiAnggota-form" method="post" class="form" data-parsley-validate>
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
                                <label>ID Mutasi</label>
                                <input type="text" class="form-control" id="editMUTASI_ID" name="MUTASI_ID" value="" readonly>
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
                            <?php
                            if ($USER_AKSES == "Administrator") {
                                ?>
                                <div class="short-div">
                                    <div class="form-group">
                                        <label>Daerah<span class="text-danger">*</span></label>
                                        <div id="selectize-wrapper11" style="position: relative;">
                                            <select name="DAERAH_KEY" id="selectize-dropdown11" required="" class="form-control" data-parsley-required>
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
                                        <div id="selectize-wrapper12" style="position: relative;">
                                            <select name="CABANG_KEY" id="selectize-dropdown12" required="" class="form-control" data-parsley-required>
                                                <option value="">-- Pilih Cabang --</option>]
                                            </select>
                                        </div>
                                    </div> 
                                </div>
                                <div class="short-div">
                                    <div class="form-group">
                                        <label>Anggota</label>
                                        <div id="selectize-wrapper4" style="position: relative;">
                                            <select name="ANGGOTA_ID" id="selectize-dropdown4" required="" class="form-control" onchange="populateFieldsEdit()" data-parsley-required>
                                                <option value="">-- Pilih Anggota --</option>
                                            </select>
                                        </div>
                                    </div> 
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="short-div hidden">
                                    <div class="form-group">
                                        <label>Cabang Token</label>
                                        <input type="text" class="form-control" id="editCABANG_KEY" name="CABANG_KEY" value="<?= $USER_CABANG; ?>" readonly>
                                    </div> 
                                </div>
                                <div class="short-div">
                                    <div class="form-group">
                                        <label>Anggota</label>
                                        <div id="selectize-wrapper4" style="position: relative;">
                                            <select name="ANGGOTA_ID" id="selectize-dropdown4" required="" class="form-control" onchange="populateFieldsEdit()" data-parsley-required>
                                                <option value="">-- Pilih Anggota --</option>
                                                <?php
                                                foreach ($rowa as $rowAnggota) {
                                                    extract($rowAnggota);
                                                    ?>
                                                    <option value="<?= $ANGGOTA_ID; ?>"><?= $ANGGOTA_ID; ?> - <?= $ANGGOTA_NAMA; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div> 
                                </div>
                                <div class="short-div">
                                    <div class="form-group">
                                        <label>Daerah</label>
                                        <input type="text" class="form-control" id="editDAERAH_AWAL_DES" name="DAERAH_AWAL" value="" readonly>
                                    </div> 
                                </div>
                                <div class="short-div">
                                    <div class="form-group">
                                        <label>Cabang</label>
                                        <input type="text" class="form-control" id="editCABANG_AWAL_DES" name="CABANG_DESKRIPSI" value="" readonly>
                                    </div> 
                                </div>
                                <?php
                            }
                            ?>
                            <div class="short-div hidden">
                                <div class="form-group">
                                    <label>Anggota Token</label>
                                    <input type="text" class="form-control" id="editANGGOTA_KEY" name="ANGGOTA_KEY" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div hidden">
                                <div class="form-group">
                                    <label>ID Cabang</label>
                                    <input type="text" class="form-control" id="editCABANG_AWAL" name="CABANG_AWAL" value="" readonly>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Daerah Tujuan<span class="text-danger">*</span></label>
                                <div id="selectize-wrapper5" style="position: relative;">
                                    <select name="DAERAH_TUJUAN" id="selectize-dropdown5" required="" class="form-control" data-parsley-required>
                                        <option value="">-- Pilih Daerah --</option>
                                        <?php
                                        foreach ($rowd as $rowEditDaerah) {
                                            extract($rowEditDaerah);
                                            ?>
                                            <option value="<?= $DAERAH_KEY; ?>"><?= $DAERAH_DESKRIPSI; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cabang Tujuan<span class="text-danger">*</span></label>
                                <div id="selectize-wrapper6" style="position: relative;">
                                    <select name="CABANG_TUJUAN" id="selectize-dropdown6" required="" class="form-control" data-parsley-required>
                                        <option value="">-- Pilih Cabang --</option>
                                    </select>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Deskripsi<span class="text-danger">*</span></label>
                                <textarea type="text" rows="4" class="form-control" id="editMUTASI_DESKRIPSI" name="MUTASI_DESKRIPSI" value="" data-parsley-required required></textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Efektif<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="datepicker5" name="MUTASI_TANGGAL" placeholder="Pilih tanggal" readonly data-parsley-required required/>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                    <button type="submit" name="submit" id="editmutasianggota" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>