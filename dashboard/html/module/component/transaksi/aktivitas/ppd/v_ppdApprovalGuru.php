<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];

$getPPD = GetQuery("SELECT c2.CABANG_DESKRIPSI PPD_CABANG,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(p.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(p.PPD_TANGGAL, '%d %M %Y') PPD_TANGGAL_FORMAT,PPD_LOKASI,PPD_TANGGAL,
CASE WHEN p.PPD_APPROVE_PELATIH = 0 THEN 'fa-solid fa-spinner fa-spin'
WHEN p.PPD_APPROVE_PELATIH = 1 THEN 'fa-solid fa-check'
ELSE 'fa-solid fa-xmark'
END PELATIH_CLASS,
CASE WHEN p.PPD_APPROVE_GURU = 0 THEN 'fa-solid fa-spinner fa-spin'
WHEN p.PPD_APPROVE_GURU = 1 THEN 'fa-solid fa-check'
ELSE 'fa-solid fa-xmark'
END GURU_CLASS,
CASE WHEN p.PPD_APPROVE_PELATIH = 0 THEN 'badge badge-inverse'
WHEN p.PPD_APPROVE_PELATIH = 1 THEN 'badge badge-success' 
ELSE 'badge badge-danger' 
END AS PELATIH_BADGE,
CASE WHEN p.PPD_APPROVE_GURU = 0 THEN 'badge badge-inverse'
WHEN p.PPD_APPROVE_GURU = 1 THEN 'badge badge-success' 
ELSE 'badge badge-danger' 
END AS GURU_BADGE
FROM t_ppd p
LEFT JOIN m_anggota a2 ON p.INPUT_BY = a2.ANGGOTA_ID
LEFT JOIN m_cabang c2 ON p.PPD_LOKASI = c2.CABANG_KEY
WHERE p.DELETION_STATUS = 0 AND p.PPD_APPROVE_PELATIH = 1 AND p.PPD_APPROVE_GURU = 0
GROUP BY p.PPD_TANGGAL,p.PPD_LOKASI
ORDER BY p.PPD_TANGGAL DESC");

$getPPDCabang = GetQuery("SELECT c.*,d.DAERAH_DESKRIPSI FROM m_cabang c
LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY 
WHERE c.DELETION_STATUS = 0
ORDER BY CABANG_DESKRIPSI ASC");
$getPPDTanggal = GetQuery("SELECT DISTINCT DATE_FORMAT(PPD_TANGGAL, '%d %M %Y') PPD_TANGGAL_FORMAT, PPD_TANGGAL FROM t_ppd WHERE DELETION_STATUS = 0 AND PPD_APPROVE_PELATIH = 1 AND PPD_APPROVE_GURU = 0 ORDER BY PPD_TANGGAL");
// Fetch all rows into an array
$rowPPDCabang = $getPPDCabang->fetchAll(PDO::FETCH_ASSOC);
$rowPPDTanggal = $getPPDTanggal->fetchAll(PDO::FETCH_ASSOC);
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
                <form method="post" class="form filterPPDGuru" id="filterPPDGuru">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Cabang PPD</label>
                                <div id="selectize-wrapper" style="position: relative;">
                                    <select name="PPD_LOKASI" id="selectize-dropdown" required="" class="form-control" data-parsley-required>
                                        <option value="">-- Pilih Cabang --</option>
                                        <?php
                                        foreach ($rowPPDCabang as $rowCabang) {
                                            extract($rowCabang);
                                            ?>
                                            <option value="<?= $CABANG_KEY; ?>"><?= $CABANG_DESKRIPSI; ?> - <?= $DAERAH_DESKRIPSI; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal PPD</label>
                                <div id="selectize-wrapper2" style="position: relative;">
                                    <select name="PPD_TANGGAL" id="selectize-dropdown2" required="" class="form-control" data-parsley-required>
                                        <option value="">-- Pilih Tanggal --</option>
                                        <?php
                                        foreach ($rowPPDTanggal as $rowTanggal) {
                                            extract($rowTanggal);
                                            ?>
                                            <option value="<?= $PPD_TANGGAL; ?>"><?= $PPD_TANGGAL_FORMAT; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" align="center">
                            <button type="button" id="reloadButton" onclick="clearFormGuru()" class="submit btn btn-teal btn-outline mb5 btn-rounded"><span class="ico-refresh"></span> Reset Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<hr>

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
                        <th>Status Persetujuan</th>
                        <th>Cabang PPD &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th>Tanggal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th>Input Oleh &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th>Input Tanggal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    </tr>
                </thead>
                <tbody id="ppddata">
                    <?php
                    while ($rowPPD = $getPPD->fetch(PDO::FETCH_ASSOC)) {
                        extract($rowPPD);
                        ?>
                        <tr>
                            <td align="center">
                                <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                                    <div class="btn-group" style="margin-bottom:5px;">
                                        <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu">
                                            <?php
                                            if ($_SESSION['APPROVE_PersetujuanPPDGuru'] == "Y") {
                                                ?>
                                                <li><a data-toggle="modal" href="#ApprovePPDGuru" class="open-ApprovePPDGuru" style="color:#00a5d2;" data-tanggal="<?= $PPD_TANGGAL; ?>" data-cabangppd="<?= $PPD_LOKASI; ?>"><span class="ico-edit"></span> Persetujuan</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td align="center">
                                <span class="<?= $PELATIH_BADGE; ?>"><i class="<?= $PELATIH_CLASS; ?>"></i> Koordinator </span><br> 
                                <span class="<?= $GURU_BADGE; ?>"><i class="<?= $GURU_CLASS; ?>"></i> Guru Besar </span>
                            </td>
                            <td align="center"><?= $PPD_CABANG; ?></td>
                            <td align="center"><?= $PPD_TANGGAL_FORMAT; ?></td>
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

<div id="ApprovePPDGuru" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ApprovePPDGuru-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Persetujuan Data PPD Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 hidden">
                            <div class="form-group">
                                <label>Lokasi Cabang PPD ID</label>
                                <input type="text" class="form-control" id="guruPPD_LOKASI" name="PPD_LOKASI" readonly />
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Lokasi Cabang PPD</label>
                                <input type="text" class="form-control" id="guruPPD_LOKASI_DESKRIPSI" name="PPD_LOKASI_DESKRIPSI" readonly />
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal PPD</label>
                                <input type="text" class="form-control" id="guruPPD_TANGGAL" name="PPD_TANGGAL" readonly />
                            </div> 
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default" id="demo">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Daftar Anggota PPD</h3>
                                </div>
                                <table class="table table-striped table-bordered" id="detailppd-table">
                                    <thead>
                                        <tr>
                                            <th>No. </th>
                                            <th>No. Dokumen </th>
                                            <th>ID Anggota </th>
                                            <th>Nama Anggota </th>
                                            <th>Daerah </th>
                                            <th>Cabang </th>
                                            <th>Ranting </th>
                                            <th>Jenis PPD </th>
                                            <th>Tingkatan </th>
                                            <th>Tingkatan PPD</th>
                                            <th>Cabang PPD </th>
                                            <th>Tanggal </th>
                                            <th>Deskripsi </th>
                                        </tr>
                                    </thead>
                                    <tbody id="detailppddata">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6 text-left">
                            <button type="submit" name="submit" id="approveGuru" class="submit btn btn-success mb5 btn-rounded"><i class="fa-regular fa-square-check"></i> Setuju</button>
                            <button type="submit" name="submit" id="rejectGuru" class="submit btn btn-danger mb5 btn-rounded"><i class="fa-regular fa-rectangle-xmark"></i> Tolak</button>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-inverse btn-outline mb5 btn-rounded next" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
