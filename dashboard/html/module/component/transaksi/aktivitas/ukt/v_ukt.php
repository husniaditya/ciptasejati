<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];

if ($USER_AKSES == "Administrator") {
    $getUKT = GetQuery("SELECT u.*,d.DAERAH_DESKRIPSI,d2.DAERAH_DESKRIPSI UKT_DAERAH,c.CABANG_DESKRIPSI,c2.CABANG_DESKRIPSI UKT_CABANG,a.ANGGOTA_ID,a.ANGGOTA_NAMA,a.ANGGOTA_RANTING,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(u.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(u.UKT_TANGGAL, '%d %M %Y') UKT_TANGGAL,
    CASE
    WHEN u.UKT_TOTAL >= 85 THEN 'A'
    WHEN u.UKT_TOTAL >= 75 THEN 'B'
    WHEN u.UKT_TOTAL >= 60 THEN 'C'
    WHEN u.UKT_TOTAL >= 40 THEN 'D'
    ELSE 'E' END UKT_NILAI,
    CASE WHEN u.UKT_APP_KOOR = 0 THEN 'fa-solid fa-spinner fa-spin'
    WHEN u.UKT_APP_KOOR = 1 THEN 'fa-solid fa-check'
    ELSE 'fa-solid fa-xmark'
    END KOOR_CLASS,
    CASE WHEN u.UKT_APP_GURU = 0 THEN 'fa-solid fa-spinner fa-spin'
    WHEN u.UKT_APP_GURU = 1 THEN 'fa-solid fa-check'
    ELSE 'fa-solid fa-xmark'
    END GURU_CLASS,
    CASE WHEN u.UKT_APP_KOOR = 0 THEN 'badge badge-inverse'
    WHEN u.UKT_APP_KOOR = 1 THEN 'badge badge-success' 
    ELSE 'badge badge-danger' 
    END AS KOOR_BADGE,
    CASE WHEN u.UKT_APP_GURU = 0 THEN 'badge badge-inverse'
    WHEN u.UKT_APP_GURU = 1 THEN 'badge badge-success' 
    ELSE 'badge badge-danger' 
    END AS GURU_BADGE
    FROM t_ukt u
    LEFT JOIN m_anggota a ON u.ANGGOTA_ID = a.ANGGOTA_ID AND u.CABANG_KEY = a.CABANG_KEY
    LEFT JOIN m_anggota a2 ON u.INPUT_BY = a2.ANGGOTA_ID AND u.CABANG_KEY = a2.CABANG_KEY
    LEFT JOIN m_cabang c ON u.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_cabang c2 ON u.UKT_LOKASI = c2.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_daerah d2 ON c2.DAERAH_KEY = d2.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON t.TINGKATAN_ID = a.TINGKATAN_ID
    WHERE u.DELETION_STATUS = 0
    ORDER BY u.UKT_ID DESC");

    $getAnggota = GetQuery("SELECT * FROM m_anggota WHERE ANGGOTA_AKSES <> 'Administrator' AND ANGGOTA_STATUS = 0 ORDER BY ANGGOTA_NAMA ASC");
    $getPenguji = GetQuery("SELECT * FROM m_anggota a
    LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
    WHERE a.ANGGOTA_STATUS = 0 AND t.TINGKATAN_LEVEL >= 6 AND a.ANGGOTA_AKSES <> 'Administrator'
    ORDER BY a.ANGGOTA_NAMA");
} else {
    $getUKT = GetQuery("SELECT u.*,d.DAERAH_DESKRIPSI,d2.DAERAH_DESKRIPSI UKT_DAERAH,c.CABANG_DESKRIPSI,c2.CABANG_DESKRIPSI UKT_CABANG,a.ANGGOTA_ID,a.ANGGOTA_NAMA,a.ANGGOTA_RANTING,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(u.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(u.UKT_TANGGAL, '%d %M %Y') UKT_TANGGAL,
    CASE
    WHEN u.UKT_TOTAL >= 85 THEN 'A'
    WHEN u.UKT_TOTAL >= 75 THEN 'B'
    WHEN u.UKT_TOTAL >= 60 THEN 'C'
    WHEN u.UKT_TOTAL >= 40 THEN 'D'
    ELSE 'E' END UKT_NILAI,
    CASE WHEN u.UKT_APP_KOOR = 0 THEN 'fa-solid fa-spinner fa-spin'
    WHEN u.UKT_APP_KOOR = 1 THEN 'fa-solid fa-check'
    ELSE 'fa-solid fa-xmark'
    END KOOR_CLASS,
    CASE WHEN u.UKT_APP_GURU = 0 THEN 'fa-solid fa-spinner fa-spin'
    WHEN u.UKT_APP_GURU = 1 THEN 'fa-solid fa-check'
    ELSE 'fa-solid fa-xmark'
    END GURU_CLASS,
    CASE WHEN u.UKT_APP_KOOR = 0 THEN 'badge badge-inverse'
    WHEN u.UKT_APP_KOOR = 1 THEN 'badge badge-success' 
    ELSE 'badge badge-danger' 
    END AS KOOR_BADGE,
    CASE WHEN u.UKT_APP_GURU = 0 THEN 'badge badge-inverse'
    WHEN u.UKT_APP_GURU = 1 THEN 'badge badge-success' 
    ELSE 'badge badge-danger' 
    END AS GURU_BADGE
    FROM t_ukt u
    LEFT JOIN m_anggota a ON u.ANGGOTA_ID = a.ANGGOTA_ID AND u.CABANG_KEY = a.CABANG_KEY
    LEFT JOIN m_anggota a2 ON u.INPUT_BY = a2.ANGGOTA_ID AND u.CABANG_KEY = a2.CABANG_KEY
    LEFT JOIN m_cabang c ON u.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_cabang c2 ON u.UKT_LOKASI = c2.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_daerah d2 ON c2.DAERAH_KEY = d2.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON t.TINGKATAN_ID = a.TINGKATAN_ID
    WHERE u.DELETION_STATUS = 0 AND u.CABANG_KEY = '$USER_CABANG'
    ORDER BY u.UKT_ID DESC");

    $getAnggota = GetQuery("SELECT * FROM m_anggota WHERE ANGGOTA_AKSES <> 'Administrator' AND ANGGOTA_STATUS = 0 and CABANG_KEY = '$USER_CABANG' ORDER BY ANGGOTA_NAMA ASC");
    $getPenguji = GetQuery("SELECT * FROM m_anggota a
    LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
    WHERE a.ANGGOTA_STATUS = 0 AND t.TINGKATAN_LEVEL >= 6 AND a.CABANG_KEY = '$USER_CABANG' AND a.ANGGOTA_AKSES <> 'Administrator'
    ORDER BY a.ANGGOTA_NAMA");
}

$getDaerah = GetQuery("select * from m_daerah where DELETION_STATUS = 0 order by DAERAH_DESKRIPSI asc");
$getTingkatan = GetQuery("SELECT * FROM m_tingkatan WHERE DELETION_STATUS = 0 ORDER BY TINGKATAN_LEVEL ASC");
$getPPDCabang = GetQuery("SELECT c.*,d.DAERAH_DESKRIPSI FROM m_cabang c
LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY 
WHERE c.DELETION_STATUS = 0
ORDER BY CABANG_DESKRIPSI ASC");
// Fetch all rows into an array
$rowPPDCabang = $getPPDCabang->fetchAll(PDO::FETCH_ASSOC);
$rowd = $getDaerah->fetchAll(PDO::FETCH_ASSOC);
$rowt = $getTingkatan->fetchAll(PDO::FETCH_ASSOC);
$rowa = $getAnggota->fetchAll(PDO::FETCH_ASSOC);
$rowp = $getPenguji->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .dataTables_wrapper {
        width: 100%;
        overflow: auto;
    }
    table.dataTable {
        width: 100% !important;
    }
    .icon-circle {
        display: inline-block;
        width: 40px;  /* Adjust the size as needed */
        height: 40px; /* Adjust the size as needed */
        border: 2px solid black; /* Circle outline color */
        border-radius: 50%; /* Makes it a circle */
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="panel-group" id="accordion1"> <!-- Input Filter -->
    <div class="panel panel-default">
        <a data-toggle="collapse" data-parent="#accordion1" href="#collapseOne" class="collapsed">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <i class="fa-solid fa-chevron-down"></i> Filter Data Uji Kenaikan Tingkat
                </h4>
            </div>
        </a>
        <div id="collapseOne" class="panel-collapse collapse">
            <div class="panel-body">
                <form method="post" class="form filterUKT resettable-form" id="filterUKT">
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
                                <label>Cabang UKT</label>
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
                                <input type="text" class="form-control" id="filterUKT_ID" name="UKT_ID" value="" placeholder="Input No Dokumen UKT">
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
                                <label>Tingkatan UKT</label>
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
                                <label>Tanggal UKT</label>
                                <input type="text" class="form-control" id="datepicker43" name="UKT_TANGGAL" placeholder="Pilih Tanggal UKT" readonly/>
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
        <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddUKT btn btn-inverse btn-outline mb5 btn-rounded" href="#AddUKT"><i class="ico-plus2"></i> Tambah Data Uji Kenaikan Tingkat</a>
    </div>
</div>
<br>
<!--/ END row -->

<!-- START row -->
<div class="row"> <!-- Table UKT -->
    <div class="col-md-12">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Tabel Uji Kenaikan Tingkat</h3>
            </div>
            <table class="table table-striped table-bordered" id="ukt-table">
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
                <tbody id="uktdata">
                    <?php
                    while ($rowUKT = $getUKT->fetch(PDO::FETCH_ASSOC)) {
                        extract($rowUKT);
                        ?>
                        <tr>
                            <td align="center">
                                <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                                    <div class="btn-group" style="margin-bottom:5px;">
                                        <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a data-toggle="modal" href="#ViewUKT" class="open-ViewUKT" style="color:#222222;" data-id="<?= $UKT_ID; ?>" data-cabang="<?= $CABANG_KEY; ?>" ><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                                            <?php
                                            if ($UKT_APP_KOOR == 0) {
                                                ?>
                                                <li><a data-toggle="modal" href="#EditUKT" class="open-EditUKT" style="color:#00a5d2;" data-id="<?= $UKT_ID; ?>" data-cabang="<?= $CABANG_KEY; ?>" ><span class="ico-edit"></span> Ubah</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#" onclick="eventukt('<?= $UKT_ID;?>','delete')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td>
                                <?= $UKT_ID; ?><br> 
                                <span class="<?= $KOOR_BADGE; ?>"><i class="<?= $KOOR_CLASS; ?>"></i> Koordinator </span><br> 
                                <span class="<?= $GURU_BADGE; ?>"><i class="<?= $GURU_CLASS; ?>"></i> Guru Besar </span>
                            </td>
                            <td align="center"><?= $ANGGOTA_ID; ?></td>
                            <td><?= $ANGGOTA_NAMA; ?></td>
                            <td align="center"><?= $DAERAH_DESKRIPSI; ?></td>
                            <td align="center"><?= $CABANG_DESKRIPSI; ?></td>
                            <td align="center"><?= $ANGGOTA_RANTING; ?></td>
                            <td align="center"><?= $TINGKATAN_NAMA; ?> - <?= $TINGKATAN_SEBUTAN; ?></td>
                            <td align="center"><?= $UKT_CABANG; ?></td>
                            <td align="center"><?= $UKT_TANGGAL; ?></td>
                            <td align="center"><?= $UKT_TOTAL; ?></td>
                            <td align="center"><?= $UKT_NILAI; ?></td>
                            <td><?= $UKT_DESKRIPSI; ?></td>
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

<div id="AddUKT" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="AddUKT-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Tambah Data UKT Anggota</h3>
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
                                    <label>Tanggal</label><span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="datepicker4" name="UKT_TANGGAL" placeholder="Pilih tanggal" readonly required data-parsley-required/>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Pilih Anggota<span class="text-danger">*</span></label>
                                    <div id="selectize-wrapper4" style="position: relative;">
                                        <select name="ANGGOTA_ID" id="selectize-dropdown4" required="" class="form-control" data-parsley-required>
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
                                    <label>Tingkatan UKT<span class="text-danger">*</span></label>
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
                                    <label>Lokasi Cabang UKT<span class="text-danger">*</span></label>
                                    <div id="selectize-wrapper8" style="position: relative;">
                                        <select name="UKT_LOKASI" id="selectize-dropdown8" required="" class="form-control" data-parsley-required>
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
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Deskripsi </label>
                                    <textarea type="text" rows="4" class="form-control" id="UKT_DESKRIPSI" name="UKT_DESKRIPSI" value="" ></textarea>
                                </div> 
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h4>Daftar Penguji</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div id="selectize-wrapper13" style="position: relative;">
                                    <select name="PENGUJI_ID" id="selectize-dropdown13" class="form-control" >
                                        <option value="">-- Pilih Penguji --</option>
                                        <?php
                                        foreach ($rowp as $rowPenguji) {
                                            extract($rowPenguji);
                                            ?>
                                            <option value="<?= $ANGGOTA_ID; ?>"><?= $ANGGOTA_ID; ?> - <?= $ANGGOTA_NAMA; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label style="color: transparent;">.</span></label>
                                <button type="button" id="addtambahdetail" class="addtambahdetail submit btn btn-success btn-outline mb5 btn-rounded"><span class="ico-plus"></span> Tambah</button>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading">
                                <h3 class="panel-title">Tabel Daftar Penguji</h3>
                            </div>
                            <table class="table table-striped table-bordered" id="addPenguji-table">
                                <thead>
                                    <tr>
                                        <th>No. </th>
                                        <th>Nama Penguji </th>
                                        <th>Tingkatan </th>
                                        <th> </th>
                                    </tr>
                                </thead>
                                <tbody id="addPengujiData">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <h4>Rincian Nilai UKT</h4>
                    <div id="addrincianukt"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Cancel</button>
                    <button type="submit" name="submit" id="saveukt" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
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
                    <h3 class="semibold modal-title text-inverse">Tambah Data UKT Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" align="center">
                            <div class="short-div">
                                <label>Foto Anggota </label><br>
                                <div id="loadpicview"></div>
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
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Cancel</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="EditUKT" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditUKT-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Ubah Data UKT Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" align="center">
                            <div class="short-div">
                                <label>Foto Anggota </label><br>
                                <div id="loadpicedit"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="short-div hidden">
                                <div class="form-group">
                                    <label>ID</label><span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="editUKT_ID" name="UKT_ID" readonly required data-parsley-required/>
                                </div> 
                            </div>
                            <div class="short-div hidden">
                                <div class="form-group">
                                    <label>Tingkatan</label><span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="editUKT_TINGKATAN" name="UKT_TINGKATAN" readonly required data-parsley-required/>
                                </div> 
                            </div>
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
                                    <label>Tanggal</label><span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="datepicker42" name="UKT_TANGGAL" placeholder="Pilih tanggal" readonly required data-parsley-required/>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Pilih Anggota<span class="text-danger">*</span></label>
                                    <div id="selectize-wrapper5" style="position: relative;">
                                        <select name="ANGGOTA_ID" id="selectize-dropdown5" required="" class="form-control" data-parsley-required>
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
                                    <label>Tingkatan UKT<span class="text-danger">*</span></label>
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
                                    <label>Lokasi Cabang UKT<span class="text-danger">*</span></label>
                                    <div id="selectize-wrapper7" style="position: relative;">
                                        <select name="UKT_LOKASI" id="selectize-dropdown7" required="" class="form-control" data-parsley-required>
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
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Deskripsi </label>
                                    <textarea type="text" rows="4" class="form-control" id="editUKT_DESKRIPSI" name="UKT_DESKRIPSI" value="" ></textarea>
                                </div> 
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h4>Daftar Penguji</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div id="selectize-wrapper14" style="position: relative;">
                                    <select name="PENGUJI_ID" id="selectize-dropdown14" class="form-control" >
                                        <option value="">-- Pilih Penguji --</option>
                                        <?php
                                        foreach ($rowp as $rowEditPenguji) {
                                            extract($rowEditPenguji);
                                            ?>
                                            <option value="<?= $ANGGOTA_ID; ?>"><?= $ANGGOTA_ID; ?> - <?= $ANGGOTA_NAMA; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label style="color: transparent;">.</span></label>
                                <button type="button" id="edittambahdetail" class="edittambahdetail submit btn btn-success btn-outline mb5 btn-rounded"><span class="ico-plus"></span> Tambah</button>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading">
                                <h3 class="panel-title">Tabel Daftar Penguji</h3>
                            </div>
                            <table class="table table-striped table-bordered" id="editPenguji-table">
                                <thead>
                                    <tr>
                                        <th>No. </th>
                                        <th>Nama Penguji </th>
                                        <th>Tingkatan </th>
                                        <th> </th>
                                    </tr>
                                </thead>
                                <tbody id="editPengujiData">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <h4>Rincian Nilai UKT</h4>
                    <div id="editrincianukt"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Cancel</button>
                    <button type="submit" name="submit" id="updateukt" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>