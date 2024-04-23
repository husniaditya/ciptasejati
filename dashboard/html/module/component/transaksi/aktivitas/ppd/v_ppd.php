<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];

if ($USER_AKSES == "Administrator") {
    $getMutasi = GetQuery("SELECT p.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,c.CABANG_KEY,c.CABANG_DESKRIPSI,t2.TINGKATAN_NAMA,t2.TINGKATAN_SEBUTAN,t.TINGKATAN_NAMA PPD_TINGKATAN,t.TINGKATAN_SEBUTAN PPD_SEBUTAN,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(p.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(p.PPD_TANGGAL, '%d %M %Y') PPD_TANGGAL, p.PPD_FILE
    FROM t_ppd p
    LEFT JOIN m_anggota a2 ON p.INPUT_BY = a2.ANGGOTA_ID
    LEFT JOIN m_cabang c ON p.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID = t.TINGKATAN_ID
    LEFT JOIN m_tingkatan t2 ON p.TINGKATAN_ID = t2.TINGKATAN_ID
    WHERE p.DELETION_STATUS = 0
    ORDER BY p.PPD_TANGGAL DESC");

    $getAnggota = GetQuery("SELECT * FROM m_anggota WHERE ANGGOTA_AKSES <> 'Administrator' AND ANGGOTA_STATUS = 0");
} else {
    $getMutasi = GetQuery("SELECT p.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,c.CABANG_KEY,c.CABANG_DESKRIPSI,t2.TINGKATAN_NAMA,t2.TINGKATAN_SEBUTAN,t.TINGKATAN_NAMA PPD_TINGKATAN,t.TINGKATAN_SEBUTAN PPD_SEBUTAN,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(p.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(p.PPD_TANGGAL, '%d %M %Y') PPD_TANGGAL, p.PPD_FILE
    FROM t_ppd p
    LEFT JOIN m_anggota a2 ON p.INPUT_BY = a2.ANGGOTA_ID
    LEFT JOIN m_cabang c ON p.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID = t.TINGKATAN_ID
    LEFT JOIN m_tingkatan t2 ON p.TINGKATAN_ID = t2.TINGKATAN_ID
    WHERE p.DELETION_STATUS = 0 and p.CABANG_KEY = '$USER_CABANG'
    ORDER BY p.PPD_TANGGAL DESC");

    $getAnggota = GetQuery("SELECT * FROM m_anggota WHERE ANGGOTA_AKSES <> 'Administrator' AND ANGGOTA_STATUS = 0 and CABANG_KEY = '$USER_CABANG'");
}

$getDaerah = GetQuery("select * from m_daerah where DELETION_STATUS = 0 order by DAERAH_DESKRIPSI asc");
$getTingkatan = GetQuery("SELECT * FROM m_tingkatan WHERE DELETION_STATUS = 0 ORDER BY TINGKATAN_LEVEL ASC");
// Fetch all rows into an array
$rowd = $getDaerah->fetchAll(PDO::FETCH_ASSOC);
$rowt = $getTingkatan->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="panel-group" id="accordion1"> <!-- Input Filter -->
    <div class="panel panel-default">
        <a data-toggle="collapse" data-parent="#accordion1" href="#collapseOne" class="collapsed">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <i class="fa-solid fa-chevron-down"></i> Filter Data Pembukaan Pusat Daya
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
        <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddPPD btn btn-inverse btn-outline mb5 btn-rounded" href="#AddPPD"><i class="ico-plus2"></i> Tambah Data Pembukaan Pusat Daya</a>
    </div>
</div>
<br>
<!--/ END row -->

<!-- START row -->
<div class="row"> <!-- Table PPD -->
    <div class="col-md-12">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Tabel Pembukaan Pusat Daya</h3>
            </div>
            <table class="table table-striped table-bordered" id="ppd-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>No Dokumen</th>
                        <th>Tanggal </th>
                        <th>Anggota &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th>PPD Tingkatan </th>
                        <th>Deskripsi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th>PIC &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th>Cabang </th>
                        <th>Daerah </th>
                        <th>Input Oleh</th>
                        <th>Input Tanggal</th>
                    </tr>
                </thead>
                <tbody id="ppddata">
                    <?php
                    while ($rowMutasi = $getMutasi->fetch(PDO::FETCH_ASSOC)) {
                        extract($rowMutasi);
                        $getAnggotaPPD = GetQuery("SELECT p.ANGGOTA_TYPE,a.ANGGOTA_ID,a.ANGGOTA_NAMA,a.ANGGOTA_RANTING FROM t_ppd_anggota p
                        LEFT JOIN m_anggota a ON p.ANGGOTA_KEY = a.ANGGOTA_KEY
                        WHERE p.PPD_ID = '$PPD_ID' order by a.ANGGOTA_NAMA asc");
                        $AnggotaPPD = $getAnggotaPPD->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <tr>
                            <td align="center">
                                <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                                    <div class="btn-group" style="margin-bottom:5px;">
                                        <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a data-toggle="modal" href="#ViewPPD" class="open-ViewPPD" style="color:#222222;" data-id="<?= $PPD_ID; ?>" data-anggota="<?= $ANGGOTA_KEY; ?>"><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                                            <?php
                                            if ($MUTASI_STATUS == 0 && ($USER_AKSES == "Administrator" || $USER_AKSES == "Koordinator")) {
                                                if ($USER_AKSES == "Administrator") {
                                                    ?>
                                                    <li><a data-toggle="modal" href="#EditPPD" class="open-EditPPD" style="color:#00a5d2;" data-id="<?= $PPD_ID; ?>" data-anggota="<?= $ANGGOTA_KEY; ?>"><span class="ico-edit"></span> Ubah</a></li>
                                                    <?php
                                                }
                                                if ($USER_AKSES == "Koordinator" && $USER_CABANG == $CABANG_AWAL) {
                                                    ?>
                                                    <li><a data-toggle="modal" href="#EditPPD" class="open-EditPPD" style="color:#00a5d2;" data-id="<?= $PPD_ID; ?>" data-anggota="<?= $ANGGOTA_KEY; ?>"><span class="ico-edit"></span> Ubah</a></li>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            <li><a href="assets/print/transaksi/mutasi/print_mutasi.php?id=<?=$PPD_ID; ?>" target="_blank" style="color: darkgoldenrod;"><i class="fa-solid fa-print"></i> Cetak</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#" onclick="eventmutasi('<?= $PPD_ID;?>','delete')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td><?= $PPD_ID; ?></td>
                            <td align="center"><?= $PPD_TANGGAL; ?></td>
                            <td>
                                <?php
                                foreach ($AnggotaPPD as $rowAnggotaPPD) {
                                    extract($rowAnggotaPPD);
                                    if ($ANGGOTA_TYPE == "Anggota") {
                                        echo $ANGGOTA_ID . " - " . $ANGGOTA_NAMA . "<br>";
                                    }
                                }
                                ?>
                            </td>
                            <td align="center"><?= $PPD_TINGKATAN; ?><br><?= $PPD_SEBUTAN; ?></td>
                            <td align="center"><?= $PPD_DESKRIPSI; ?></td>
                            <td>
                                <?php
                                foreach ($AnggotaPPD as $rowAnggotaPPD) {
                                    extract($rowAnggotaPPD);
                                    if ($ANGGOTA_TYPE == "PIC") {
                                        echo $ANGGOTA_ID . " - " . $ANGGOTA_NAMA . "<br>";
                                    }
                                }
                                ?>
                            </td>
                            <td align="center"><?= $CABANG_DESKRIPSI; ?></td>
                            <td align="center"><?= $DAERAH_DESKRIPSI; ?></td>
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

<div id="AddPPD" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="AddPPD-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Tambah Data PPD Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <?php
                            if ($USER_AKSES == "Administrator") {
                                ?>
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
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
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
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal</label><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="datepicker4" name="PPD_TANGGAL" placeholder="Pilih tanggal" readonly required data-parsley-required/>
                            </div> 
                        </div>
                        <div class="col-md-6">
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
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Deskripsi<span class="text-danger">*</span></label>
                                <textarea type="text" rows="4" class="form-control" id="MUTASI_DESKRIPSI" name="PPD_DESKRIPSI" value="" data-parsley-required required></textarea>
                            </div> 
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pilih Anggota<span class="text-danger">*</span></label>
                                <div id="selectize-wrapper4" style="position: relative;">
                                    <select name="ANGGOTA_KEY" id="selectize-dropdown4" required="" class="form-control" data-parsley-required>
                                        <option value="">-- Pilih Anggota --</option>
                                    </select>
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-2">
                            <label style="color: transparent;">.</label><br>
                            <button type="button" id="tambahanggota" class="btn btn-success btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-plus"></span> Tambah</button>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pilih PIC<span class="text-danger">*</span></label>
                                <div id="selectize-wrapper3" style="position: relative;">
                                    <select name="ANGGOTA_ID" id="selectize-dropdown3" required="" class="form-control" data-parsley-required>
                                        <option value="">-- Pilih PIC --</option>
                                    </select>
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-2">
                            <label style="color: transparent;">.</label><br>
                            <button type="button" id="tambahpic" class="btn btn-success btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-plus"></span> Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel-heading">
                            <h3 class="panel-title">Data Anggota</h3>
                            <table class="table table-striped table-bordered" id="anggota-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID Anggota</th>
                                        <th>Nama</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="dataanggota">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel-heading">
                            <h3 class="panel-title">Data PIC</h3>
                            <table class="table table-striped table-bordered" id="pic-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID Anggota</th>
                                        <th>Nama</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="datapic">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Cancel</button>
                    <button type="submit" name="submit" id="saveppd" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="ViewPPD" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ViewPPD-form" method="post" class="form" data-parsley-validate>
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
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="EditPPD" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditPPD-form" method="post" class="form" data-parsley-validate>
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
                                <input type="text" class="form-control" id="editPPD_ID" name="PPD_ID" value="" readonly>
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
                                        <div id="selectize-wrapper7" style="position: relative;">
                                            <select name="ANGGOTA_KEY" id="selectize-dropdown7" required="" class="form-control" onchange="populateFieldsEdit()" data-parsley-required>
                                                <option value="">-- Pilih Anggota --</option>
                                            </select>
                                        </div>
                                    </div> 
                                </div>
                                <?php
                            } else {
                                ?>
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
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                    <button type="submit" name="submit" id="EditPPD" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="ApproveMutasiAnggota" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ApproveMutasiAnggota-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Persetujuan Data Mutasi Anggota</h3>
                </div>
                <div class="modal-body">
                    <h5 class="text-center" id="appMUTASI_STATUS_DES"></h5><br>
                    <div class="row hidden">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>ID Mutasi</label>
                                <input type="text" class="form-control" id="appPPD_ID" name="PPD_ID" value="" readonly>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <div class="form-group">
                                <label>Diajukan Oleh</label>
                                <p id="appINPUT_BY"></p>
                            </div> 
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <div class="form-group">
                                <label>Tanggal Pengajuan</label>
                                <p id="appINPUT_DATE"></p>
                            </div> 
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <div class="form-group">
                                <label>Disetujui Oleh</label>
                                <p id="appAPPROVE_BY"></p>
                            </div> 
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <div class="form-group">
                                <label>Tanggal persetujuan</label>
                                <p id="appMUTASI_APP_TANGGAL"></p>
                            </div> 
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" align="center">
                            <div class="short-div">
                                <label>Foto Anggota </label><br>
                                <div id="loadpicapp"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="short-div">
                                <div class="form-group">
                                    <label>ID - Nama Anggota</label>
                                    <input type="text" class="form-control" id="appANGGOTA_IDNAMA" name="ANGGOTA_ID" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div hidden">
                                <div class="form-group">
                                    <label>ID Cabang</label>
                                    <input type="text" class="form-control" id="appCABANG_AWAL" name="CABANG_AWAL" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Daerah</label>
                                    <input type="text" class="form-control" id="appDAERAH_AWAL_DES" name="DAERAH_AWAL" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Cabang</label>
                                    <input type="text" class="form-control" id="appCABANG_AWAL_DES" name="CABANG_DESKRIPSI" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Sabuk</label>
                                    <input type="text" class="form-control" id="appTINGKATAN_NAMA" name="TINGKATAN_ID" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tingkatan</label>
                                    <input type="text" class="form-control" id="appTINGKATAN_SEBUTAN" name="TINGKATAN_SEBUTAN" value="" readonly>
                                </div> 
                            </div>   
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Daerah Tujuan</label>
                                <input type="text" class="form-control" id="appDAERAH_TUJUAN_DES" name="DAERAH_TUJUAN" value="" readonly>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cabang Tujuan</label>
                                <input type="text" class="form-control" id="appCABANG_TUJUAN_DES" name="CABANG_TUJUAN" value="" readonly>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea type="text" rows="4" class="form-control" id="appMUTASI_DESKRIPSI" name="MUTASI_DESKRIPSI" value="" data-parsley-required readonly></textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Efektif</label>
                                <input type="text" class="form-control" id="appTANGGAL_EFEKTIF" name="MUTAS_TANGGAL" value="" readonly>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6 text-left">
                            <button type="submit" name="submit" id="approvemutasianggota" class="submit btn btn-success mb5 btn-rounded"><i class="fa-regular fa-square-check"></i> Setuju</button>
                            <button type="submit" name="submit" id="rejectmutasianggota" class="submit btn btn-danger mb5 btn-rounded"><i class="fa-regular fa-rectangle-xmark"></i> Tolak</button>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-inverse btn-outline mb5 btn-rounded next" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>