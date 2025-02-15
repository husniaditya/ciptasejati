<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];

if ($USER_AKSES == "Administrator") {
    $getPPD = GetQuery("SELECT p.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,c.CABANG_KEY,c.CABANG_DESKRIPSI,t2.TINGKATAN_NAMA PPD_TINGKATAN,t2.TINGKATAN_SEBUTAN PPD_SEBUTAN,a.ANGGOTA_ID,a.ANGGOTA_NAMA,a.ANGGOTA_RANTING,c2.CABANG_DESKRIPSI PPD_CABANG,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(p.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(p.PPD_TANGGAL, '%d %M %Y') PPD_TANGGAL, p.PPD_FILE, u.UKT_ID, DATE_FORMAT(u.UKT_TANGGAL, '%d %M %Y') UKT_TANGGAL,
    CASE WHEN p.PPD_JENIS = 0 THEN 'Kenaikan'
    ELSE 'Ulang'
    END PPD_JENIS,
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
    LEFT JOIN m_anggota a ON p.ANGGOTA_ID = a.ANGGOTA_ID AND p.CABANG_KEY = a.CABANG_KEY AND a.ANGGOTA_STATUS = 0 AND a.DELETION_STATUS = 0
    LEFT JOIN m_anggota a2 ON p.INPUT_BY = a2.ANGGOTA_ID AND a2.ANGGOTA_STATUS = 0 AND a2.DELETION_STATUS = 0
    LEFT JOIN m_cabang c ON p.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_cabang c2 ON p.PPD_LOKASI = c2.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID_LAMA = t.TINGKATAN_ID
    LEFT JOIN m_tingkatan t2 ON p.TINGKATAN_ID_BARU = t2.TINGKATAN_ID
    LEFT JOIN t_ukt u ON u.TINGKATAN_ID = p.TINGKATAN_ID_BARU AND u.ANGGOTA_ID = p.ANGGOTA_ID AND u.DELETION_STATUS = 0 AND u.UKT_APP_KOOR = 1
    WHERE p.DELETION_STATUS = 0
    ORDER BY p.PPD_ID DESC");

    $getAnggota = GetQuery("SELECT * FROM m_anggota WHERE ANGGOTA_AKSES <> 'Administrator' AND ANGGOTA_STATUS = 0");
} else if ($USER_AKSES == "Koordinator" || $USER_AKSES == "Pengurus") {
    $getPPD = GetQuery("SELECT p.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,c.CABANG_KEY,c.CABANG_DESKRIPSI,t2.TINGKATAN_NAMA PPD_TINGKATAN,t2.TINGKATAN_SEBUTAN PPD_SEBUTAN,a.ANGGOTA_ID,a.ANGGOTA_NAMA,a.ANGGOTA_RANTING,c2.CABANG_DESKRIPSI PPD_CABANG,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(p.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(p.PPD_TANGGAL, '%d %M %Y') PPD_TANGGAL, p.PPD_FILE, u.UKT_ID, DATE_FORMAT(u.UKT_TANGGAL, '%d %M %Y') UKT_TANGGAL,
    CASE WHEN p.PPD_JENIS = 0 THEN 'Kenaikan'
    ELSE 'Ulang'
    END PPD_JENIS,
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
    LEFT JOIN m_anggota a ON p.ANGGOTA_ID = a.ANGGOTA_ID AND p.CABANG_KEY = a.CABANG_KEY
    LEFT JOIN m_anggota a2 ON p.INPUT_BY = a2.ANGGOTA_ID
    LEFT JOIN m_cabang c ON p.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_cabang c2 ON p.PPD_LOKASI = c2.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID_LAMA = t.TINGKATAN_ID
    LEFT JOIN m_tingkatan t2 ON p.TINGKATAN_ID_BARU = t2.TINGKATAN_ID
    LEFT JOIN t_ukt u ON u.TINGKATAN_ID = p.TINGKATAN_ID_BARU AND u.ANGGOTA_ID = p.ANGGOTA_ID AND u.DELETION_STATUS = 0 AND u.UKT_APP_KOOR = 1
    WHERE p.DELETION_STATUS = 0 AND p.CABANG_KEY = '$USER_CABANG'
    ORDER BY p.PPD_ID DESC");

    $getAnggota = GetQuery("SELECT * FROM m_anggota WHERE ANGGOTA_AKSES <> 'Administrator' AND ANGGOTA_STATUS = 0 and CABANG_KEY = '$USER_CABANG'");
} else {
    $getPPD = GetQuery("SELECT p.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,c.CABANG_KEY,c.CABANG_DESKRIPSI,t2.TINGKATAN_NAMA PPD_TINGKATAN,t2.TINGKATAN_SEBUTAN PPD_SEBUTAN,a.ANGGOTA_ID,a.ANGGOTA_NAMA,a.ANGGOTA_RANTING,c2.CABANG_DESKRIPSI PPD_CABANG,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(p.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(p.PPD_TANGGAL, '%d %M %Y') PPD_TANGGAL, p.PPD_FILE, u.UKT_ID, DATE_FORMAT(u.UKT_TANGGAL, '%d %M %Y') UKT_TANGGAL,
    CASE WHEN p.PPD_JENIS = 0 THEN 'Kenaikan'
    ELSE 'Ulang'
    END PPD_JENIS,
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
    LEFT JOIN m_anggota a ON p.ANGGOTA_ID = a.ANGGOTA_ID AND p.CABANG_KEY = a.CABANG_KEY
    LEFT JOIN m_anggota a2 ON p.INPUT_BY = a2.ANGGOTA_ID
    LEFT JOIN m_cabang c ON p.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_cabang c2 ON p.PPD_LOKASI = c2.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID_LAMA = t.TINGKATAN_ID
    LEFT JOIN m_tingkatan t2 ON p.TINGKATAN_ID_BARU = t2.TINGKATAN_ID
    LEFT JOIN t_ukt u ON u.TINGKATAN_ID = p.TINGKATAN_ID_BARU AND u.ANGGOTA_ID = p.ANGGOTA_ID AND u.DELETION_STATUS = 0 AND u.UKT_APP_KOOR = 1
    WHERE p.DELETION_STATUS = 0 AND p.ANGGOTA_ID = '$USER_ID'
    ORDER BY p.PPD_ID DESC");

    $getAnggota = GetQuery("SELECT * FROM m_anggota WHERE ANGGOTA_AKSES <> 'Administrator' AND ANGGOTA_STATUS = 0 and CABANG_KEY = '$USER_CABANG'");
}

$getDaerah = GetQuery("select * from m_daerah where DELETION_STATUS = 0 order by DAERAH_DESKRIPSI asc");
$getPPDCabang = GetQuery("SELECT c.*,d.DAERAH_DESKRIPSI FROM m_cabang c
LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY 
WHERE c.DELETION_STATUS = 0
ORDER BY CABANG_DESKRIPSI ASC");
$getTingkatan = GetQuery("SELECT * FROM m_tingkatan WHERE DELETION_STATUS = 0 ORDER BY TINGKATAN_LEVEL ASC");
// Fetch all rows into an array
$rowd = $getDaerah->fetchAll(PDO::FETCH_ASSOC);
$rowPPDCabang = $getPPDCabang->fetchAll(PDO::FETCH_ASSOC);
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
                <form method="post" class="form filterLapPPD resettable-form" id="filterLapPPD">
                    <div class="row">
                        <?php
                        if ($USER_AKSES == "Administrator") {
                            ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Daerah</label>
                                    <select name="DAERAH_KEY" id="selectize-select3" required="" class="form-control" data-parsley-required>
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
                                <label>No Dokumen</label>
                                <input type="text" class="form-control" id="filterPPD_ID" name="PPD_ID" value="" placeholder="Input No Dokumen PPD">
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
                                <label>Tingkatan PPD</label>
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
                                <label>Jenis PPD</label>
                                <select id="filterPPD_JENIS" name="PPD_JENIS" class="form-control"  data-parsley-required required>
                                    <option value="">Tampilkan semua</option>
                                    <option value="0">Kenaikan</option>
                                    <option value="1">Ulang</option>
                                </select>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal PPD</label>
                                <input type="text" class="form-control" id="datepicker42" name="PPD_TANGGAL" placeholder="Pilih Tanggal PPD" readonly/>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" align="center">
                            <button type="button" id="reloadButton" onclick="clearLapForm()" class="submit btn btn-teal btn-outline mb5 btn-rounded"><span class="ico-refresh"></span> Reset Filter</button>
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
            <table class="table table-striped table-bordered" id="lapppd-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>No Dokumen</th>
                        <th>ID Anggota </th>
                        <th>Nama Anggota </th>
                        <th>Daerah </th>
                        <th>Cabang </th>
                        <th>Ranting </th>
                        <th>Jenis PPD </th>
                        <th>Tingkatan</th>
                        <th>Tingkatan PPD </th>
                        <th>Cabang PPD</th>
                        <th>Tanggal </th>
                        <th>Deskripsi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th>Dokumen UKT </th>
                        <th>Tanggal UKT </th>
                        <th>No Sertifikat</th>
                        <th>Input Oleh</th>
                        <th>Input Tanggal</th>
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
                                            <li><a data-toggle="modal" href="#ViewPPD" class="open-ViewPPD" style="color:#222222;" data-id="<?= $PPD_ID; ?>" data-cabang="<?= $CABANG_KEY; ?>" ><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                                            <li class="divider"></li>
                                            <li><a href="assets/print/transaksi/aktivitas/ppd/print_ppdreportanggota.php?id=<?= encodeIdToBase64($PPD_ID); ?>" target="_blank" style="color: darkgoldenrod;"><i class="fa-solid fa-print"></i> Cetak</a></li>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td>
                                <?= $PPD_ID; ?><br> 
                                <span class="<?= $PELATIH_BADGE; ?>"><i class="<?= $PELATIH_CLASS; ?>"></i> Koordinator </span><br> 
                                <span class="<?= $GURU_BADGE; ?>"><i class="<?= $GURU_CLASS; ?>"></i> Guru Besar </span>
                            </td>
                            <td align="center"><?= $ANGGOTA_ID; ?></td>
                            <td align="center"><?= $ANGGOTA_NAMA; ?></td>
                            <td align="center"><?= $DAERAH_DESKRIPSI; ?></td>
                            <td align="center"><?= $CABANG_DESKRIPSI; ?></td>
                            <td align="center"><?= $ANGGOTA_RANTING; ?></td>
                            <td align="center"><?= $PPD_JENIS; ?></td>
                            <td align="center"><?= $TINGKATAN_NAMA; ?> - <?= $TINGKATAN_SEBUTAN; ?></td>
                            <td align="center"><?= $PPD_TINGKATAN; ?> - <?= $PPD_SEBUTAN; ?></td>
                            <td align="center"><?= $PPD_CABANG; ?></td>
                            <td align="center"><?= $PPD_TANGGAL; ?></td>
                            <td><?= $PPD_DESKRIPSI; ?></td>
                            <td align="center"><a data-toggle="modal" href="#ViewUKT" class="open-ViewUKT" data-id="<?= $UKT_ID; ?>" > <?= $UKT_ID; ?></a></td>
                            <td align="center"><?= $UKT_TANGGAL; ?></td>
                            <td align="center">
                            <?php
                            if ($PPD_APPROVE_GURU == 1) {
                                ?>
                                <div>
                                    <a href="<?= $PPD_FILE; ?>" target="_blank"> <i class="fa-regular fa-file-lines"></i> <?= $PPD_FILE_NAME; ?>
                                    </a>
                                </div>
                                <?php
                            }
                            ?>
                            </td>
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

<div id="ViewPPD" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ViewPPD-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Lihat Data PPD Anggota</h3>
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
                            <?php
                            if ($USER_AKSES == "Administrator") {
                                ?>
                                <div class="form-group">
                                    <label>Daerah</label>
                                    <input type="text" class="form-control" id="viewPPD_DAERAH" name="DAERAH_KEY" readonly />
                                </div> 
                                <div class="short-div">
                                    <div class="form-group">
                                        <label>Cabang</label>
                                        <input type="text" class="form-control" id="viewPPD_CABANG" name="CABANG_KEY" readonly />
                                    </div> 
                                </div>
                                <?php
                            }
                            ?>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="text" class="form-control" id="viewPPD_TANGGAL" name="PPD_TANGGAL" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Jenis PPD</label>
                                    <input type="text" class="form-control" id="viewPPD_JENIS" name="PPD_JENIS" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tingkatan Lanjutan</label>
                                    <input type="text" class="form-control" id="viewPPD_TINGKATAN" name="PPD_TINGKATAN" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Anggota</label>
                                    <input type="text" class="form-control" id="viewPPD_ANGGOTA" name="ANGGOTA_KEY" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Lokasi Cabang PPD</label>
                                    <input type="text" class="form-control" id="viewPPD_LOKASI" name="PPD_LOKASI" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Deskripsi</label>
                                    <textarea type="text" rows="4" class="form-control" id="viewPPD_DESKRIPSI" name="PPD_DESKRIPSI" value="" readonly></textarea>
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

<div id="ViewUKT" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ViewUKT-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Lihat Data UKT Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" align="center">
                            <div class="short-div">
                                <label>Foto Anggota </label><br>
                                <div id="loadpicukt"></div>
                            </div>
                            <br>
                            <br>
                            <div class="short-div">
                                <div class="col-md-6">
                                    <h2><u>Nilai UKT</u></h2>
                                    <h2 id="viewUKT_TOTAL"></h2>
                                </div>
                                <div class="col-md-6">
                                    <h2><u>Predikat</u></h2>
                                    <h2 id="viewUKT_NILAI"></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <?php
                        if ($USER_AKSES == "Administrator") {
                            ?>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Daerah </label>
                                    <input type="text" class="form-control" id="viewDAERAH_KEY" name="DAERAH_KEY" readonly/>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Cabang </label>
                                    <input type="text" class="form-control" id="viewCABANG_KEY" name="CABANG_KEY" readonly />
                                </div> 
                            </div>
                            <?php
                        }
                        ?>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tanggal </label>
                                    <input type="text" class="form-control" id="viewUKT_TANGGAL" name="UKT_TANGGAL" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Pilih Anggota </label>
                                    <input type="text" class="form-control" id="viewANGGOTA_ID" name="ANGGOTA_ID" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tingkatan UKT </label>
                                    <input type="text" class="form-control" id="viewTINGKATAN_ID" name="TINGKATAN_ID" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Lokasi Cabang UKT </label>
                                    <input type="text" class="form-control" id="viewUKT_LOKASI" name="UKT_LOKASI" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Deskripsi </label>
                                    <textarea type="text" rows="4" class="form-control" id="viewUKT_DESKRIPSI" name="UKT_DESKRIPSI" value="" readonly></textarea>
                                </div> 
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h4>Daftar Penguji</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading">
                                <h3 class="panel-title">Tabel Daftar Penguji</h3>
                            </div>
                            <table class="table table-striped table-bordered" id="viewPenguji-table">
                                <thead>
                                    <tr>
                                        <th>No. </th>
                                        <th>Nama Penguji </th>
                                        <th>Tingkatan </th>
                                    </tr>
                                </thead>
                                <tbody id="viewPengujiData">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <h4>Rincian Nilai UKT</h4>
                    <div id="viewrincianukt"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                </div>
            </div>
        </div>
    </form>
</div>
