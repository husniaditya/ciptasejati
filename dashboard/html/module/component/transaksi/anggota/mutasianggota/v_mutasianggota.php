<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];

$getMutasi = GetQuery("SELECT t.*,a.ANGGOTA_ID,a.ANGGOTA_NAMA,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(t.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(t.MUTASI_TANGGAL, '%d %M %Y %H:%i') MUTASI_TGL, DATE_FORMAT(t.MUTASI_STATUS_TANGGAL, '%d %M %Y %H:%i') MUTASI_STATUS_TANGGAL, DATE_FORMAT(t.MUTASI_TANGGAL, '%d %M %Y') TANGGAL_EFEKTIF,
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
LEFT JOIN m_cabang c ON t.CABANG_TUJUAN = c.CABANG_KEY
LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
ORDER BY t.MUTASI_STATUS ASC, t.MUTASI_TANGGAL DESC");

$getDaerah = GetQuery("select * from m_daerah where DELETION_STATUS = 0");
$getCabang = GetQuery("select * from m_cabang where DELETION_STATUS = 0");
$getTingkatan = GetQuery("select * from m_tingkatan where DELETION_STATUS = 0");
$getAnggota = GetQuery("SELECT * FROM m_anggota WHERE ANGGOTA_AKSES <> 'Administrator' AND ANGGOTA_STATUS = 0");
// Fetch all rows into an array
$rowd = $getDaerah->fetchAll(PDO::FETCH_ASSOC);
$rows = $getCabang->fetchAll(PDO::FETCH_ASSOC);
$rowt = $getTingkatan->fetchAll(PDO::FETCH_ASSOC);
$rowa = $getAnggota->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="panel-group" id="accordion1">
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
                <form method="post" class="form filterAnggota" id="filterAnggota">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Daerah Tujuan</label>
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
                                <label for="">Cabang Tujuan</label>
                                <select name="CABANG_KEY" id="selectize-select2" required="" class="form-control" data-parsley-required>
                                    <option value="">-- Pilih Cabang --</option>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Tingkatan</label>
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
                                <label for="">Status Mutasi</label>
                                <select id="filterANGGOTA_STATUS" name="ANGGOTA_STATUS" class="form-control"  data-parsley-required required>
                                    <option value="">Tampilkan semua</option>
                                    <option value="0">Tertunda</option>
                                    <option value="1">Disetujui</option>
                                    <option value="2">Ditolak</option>
                                </select>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">ID Anggota</label>
                                <input type="text" class="form-control" id="filterANGGOTA_ID" name="ANGGOTA_ID" value="" placeholder="Input ID Anggota">
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Nama</label>
                                <input type="text" class="form-control" id="filterANGGOTA_NAMA" name="ANGGOTA_NAMA" value="" placeholder="Input Nama">
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
    <div class="col-lg-12">
        <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddMutasiAnggota btn btn-inverse btn-outline mb5 btn-rounded" href="#AddMutasiAnggota"><i class="ico-plus2"></i> Tambah Data Mutasi Anggota</a>
    </div>
</div>
<br>
<!--/ END row -->

<!-- START row -->
<div class="row">
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
                        <th>Daerah Tujuan </th>
                        <th>Cabang Tujuan </th>
                        <th>ID Anggota </th>
                        <th>Nama </th>
                        <th>Tingkatan </th>
                        <th>Gelar</th>
                        <th>Deskripsi</th>
                        <th>Tanggal Efektif</th>
                        <th>Input Oleh</th>
                        <th>Input Tanggal</th>
                    </tr>
                </thead>
                <tbody id="anggotadata">
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
                                            <li><a data-toggle="modal" href="#ViewMutasiAnggota" class="open-ViewMutasiAnggota" style="color:#222222;"><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                                            <li><a data-toggle="modal" href="#EditMutasiAnggota" class="open-EditMutasiAnggota" style="color:#00a5d2;"><span class="ico-edit"></span> Ubah</a></li>
                                            <li><a href="./assets/print/transaksi/mutasi/print_mutasi.php?id=<?= $MUTASI_ID; ?>" target="_blank" style="color: darkgoldenrod;"><i class="fa-solid fa-print"></i> Cetak</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#" onclick="approvemutasi('<?= $ANGGOTA_KEY;?>','approveevent')" style="color:forestgreen;"><i class="fa-regular fa-circle-check fa-lg"></i> Setuju</a></li>
                                            <li><a href="#" onclick="rejectmutasi('<?= $ANGGOTA_KEY;?>','rejectevent')" style="color:firebrick;"><i class="fa-regular fa-circle-xmark fa-lg"></i> Tolak</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#" onclick="deletemutasi('<?= $ANGGOTA_KEY;?>','deleteevent')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td><?= $MUTASI_ID; ?> <br> <span class="<?= $MUTASI_BADGE; ?>"><i class="<?= $MUTASI_CLASS; ?>"></i> <?= $MUTASI_STATUS_DES; ?></span></td>
                            <td align="center"><?= $DAERAH_DESKRIPSI; ?></td>
                            <td align="center"><?= $CABANG_DESKRIPSI; ?></td>
                            <td align="center"><?= $ANGGOTA_ID; ?></td>
                            <td align="center"><?= $ANGGOTA_NAMA; ?></td>
                            <td align="center"><?= $TINGKATAN_NAMA; ?></td>
                            <td align="center"><?= $TINGKATAN_SEBUTAN; ?></td>
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
                    <h3 class="semibold modal-title text-success">Tambah Data Mutasi Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Anggota</label>
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
                        <div class="col-md-6 hidden">
                            <div class="form-group">
                                <label for="">ID Cabang</label>
                                <input type="text" class="form-control" id="CABANG_KEY" name="CABANG_KEY" value="" readonly>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Daerah</label>
                                <input type="text" class="form-control" id="DAERAH_KEY" name="DAERAH_KEY" value="" readonly>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Cabang</label>
                                <input type="text" class="form-control" id="CABANG_DESKRIPSI" name="CABANG_DESKRIPSI" value="" readonly>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Sabuk</label>
                                <input type="text" class="form-control" id="TINGKATAN_ID" name="TINGKATAN_ID" value="" readonly>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tingkatan</label>
                                <input type="text" class="form-control" id="TINGKATAN_SEBUTAN" name="TINGKATAN_SEBUTAN" value="" readonly>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Daerah Tujuan<span class="text-danger">*</span></label>
                                <div id="selectize-wrapper2" style="position: relative;">
                                    <select name="DAERAH_KEY" id="selectize-dropdown2" required="" class="form-control" data-parsley-required>
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
                                <label for="">Cabang Tujuan<span class="text-danger">*</span></label>
                                <div id="selectize-wrapper3" style="position: relative;">
                                    <select name="CABANG_KEY" id="selectize-dropdown3" required="" class="form-control" data-parsley-required>
                                        <option value="">-- Pilih Cabang --</option>
                                    </select>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Deskripsi<span class="text-danger">*</span></label>
                                <textarea type="text" rows="4" class="form-control" id="MUTASI_DESKRIPSI" name="MUTASI_DESKRIPSI" value="" data-parsley-required required></textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tanggal Efektif<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="datepicker4" name="MUTASI_TANGGAL" placeholder="Pilih tanggal" readonly data-parsley-required required/>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Cancel</button>
                    <button type="submit" name="submit" id="savemutasianggota" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
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
                    <h3 class="semibold modal-title text-success">Lihat Data Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" align="center">
                            <label for="">Foto Anggota </label><br>
                            <div id="loadpic"></div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="short-div">
                                <div class="form-group">
                                    <label for="">Daerah<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="viewDAERAH_KEY" name="DAERAH_KEY" value="" data-parsley-required readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label for="">Cabang<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="viewCABANG_KEY" name="CABANG_KEY" value="" data-parsley-required readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label for="">Tingkatan<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="viewTINGKATAN_ID" name="TINGKATAN_ID" value="" data-parsley-required readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label for="">No Urut Anggota<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="ViewMutasiAnggota_ID" name="ANGGOTA_ID" value="" data-parsley-required readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-pills nav-justified">
                                <li class="active"><a href="#tab-informasianggota" data-toggle="tab"><i class="ico-user2 mr5"></i> Informasi Anggota</a></li>
                                <li><a href="#tab-riwayatmutasi" data-toggle="tab"><i class="fa-solid fa-recycle"></i> Riwayat Mutasi</a></li>
                                <li><a href="#tab-idsertifikat" data-toggle="tab"><i class="fa-regular fa-address-card"></i> ID &amp; Sertifikat</a></li>
                                <li><a href="#tab-mutasikas" data-toggle="tab"><i class="fa-solid fa-money-bill-transfer"></i>  Mutasi Kas</a></li>
                                <li><a href="#tab-riwayatppd" data-toggle="tab"><i class="fa-solid fa-clock-rotate-left"></i>  Riwayat PPD</a></li>
                            </ul>
                            <!--/ tab -->
                            <!-- tab content -->
                            <div class="tab-content panel-custom">
                                <div class="tab-pane active" id="tab-informasianggota">
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Tanggal Bergabung</label>
                                                <input type="text" class="form-control" id="ViewMutasiAnggota_JOIN" name="ANGGOTA_JOIN" value="" data-parsley-required readonly>
                                            </div> 
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Tanggal Resign</label>
                                                <input type="text" class="form-control" id="ViewMutasiAnggota_RESIGN" name="ANGGOTA_RESIGN" value="" data-parsley-required readonly>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">KTP</label>
                                                <input type="text" class="form-control" id="ViewMutasiAnggota_KTP" name="ANGGOTA_KTP" value="" data-parsley-required readonly>
                                            </div> 
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Nama</label>
                                                <input type="text" class="form-control" id="ViewMutasiAnggota_NAMA" name="ANGGOTA_NAMA" value="" data-parsley-required readonly>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Jenis Kelamin</label>
                                                <input type="text" class="form-control" id="ViewMutasiAnggota_KELAMIN" name="ANGGOTA_KELAMIN" value="" data-parsley-required readonly>
                                            </div> 
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Pekerjaan</label>
                                                <input type="text" class="form-control" id="ViewMutasiAnggota_PEKERJAAN" name="ANGGOTA_PEKERJAAN" value="" readonly>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Alamat</label>
                                                <textarea type="text" rows="4" class="form-control" id="ViewMutasiAnggota_ALAMAT" name="ANGGOTA_ALAMAT" value="" data-parsley-required readonly></textarea>
                                            </div> 
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">No HP</label>
                                                <input type="text" class="form-control" id="ViewMutasiAnggota_HP" name="ANGGOTA_HP" value="" data-parsley-required readonly>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Tempat Lahir</label>
                                                <input type="text" class="form-control" id="ViewMutasiAnggota_TEMPAT_LAHIR" name="ANGGOTA_TEMPAT_LAHIR" value="" data-parsley-required readonly>
                                            </div> 
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Tanggal Lahir</label>
                                                <input type="text" class="form-control" id="ViewMutasiAnggota_TANGGAL_LAHIR" name="ANGGOTA_TANGGAL_LAHIR" readonly data-parsley-required/>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Email</label>
                                                <input type="email" class="form-control" id="ViewMutasiAnggota_EMAIL" name="ANGGOTA_EMAIL" data-parsley-required readonly/>
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
                                                            <th>No Dokumen</th>
                                                            <th>ID Anggota</th>
                                                            <th>Daerah </th>
                                                            <th>Cabang </th>
                                                            <th>Sabuk </th>
                                                            <th>Tingkatan </th>
                                                            <th>Gelar </th>
                                                            <th>Nama</th>
                                                            <th>Tanggal Mutasi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="riwayatmutasi">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab-idsertifikat">
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-default" id="demo">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Tabel ID &amp; Sertifikat</h3>
                                                </div>
                                                <table class="table table-striped table-bordered" id="idsertifikat-table">
                                                    <thead>
                                                        <tr>
                                                            <th>No Dokumen</th>
                                                            <th>ID Anggota</th>
                                                            <th>Daerah </th>
                                                            <th>Cabang </th>
                                                            <th>Sabuk </th>
                                                            <th>Tingkatan </th>
                                                            <th>Gelar </th>
                                                            <th>Nama</th>
                                                            <th>ID Card</th>
                                                            <th>Sertifikat</th>
                                                            <th>Tanggal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="idsertifikat">
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
                                                            <th>No Dokumen</th>
                                                            <th>ID Anggota</th>
                                                            <th>Daerah </th>
                                                            <th>Cabang </th>
                                                            <th>Sabuk </th>
                                                            <th>Tingkatan </th>
                                                            <th>Gelar </th>
                                                            <th>Nama</th>
                                                            <th>Tanggal</th>
                                                            <th>Debet</th>
                                                            <th>Kredit</th>
                                                            <th>Jumlah</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="mutasikas">
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
                                                            <th>No Dokumen</th>
                                                            <th>ID Anggota</th>
                                                            <th>Daerah </th>
                                                            <th>Cabang </th>
                                                            <th>Sabuk </th>
                                                            <th>Tingkatan </th>
                                                            <th>Gelar </th>
                                                            <th>Nama</th>
                                                            <th>Tanggal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="riwayatppd">
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
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
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
                    <h3 class="semibold modal-title text-success">Edit Data Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row hidden">
                        <div class="col-md-6">
                            <label for="">Key<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" required id="EditMutasiAnggota_KEY" name="ANGGOTA_KEY" value="" data-parsley-required readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" align="center">
                            <div class="form-group">
                                <!-- PUT THE SAMPLE UPLOAD PHOTO HERE -->
                                <label for="">Foto Anggota </label><br>
                                <div id="loadpicedit" style="display: block;"></div>
                                <div id="preview-container-edit">
                                    <img id="preview-image-edit" src="#" alt="Preview" style="max-width: 250px; max-height: 250px;" />
                                </div>
                                <br>
                                <div>
                                    <span class="btn btn-inverse mb5 btn-rounded fileinput-button">
                                        <i class="fa-regular fa-image"></i>
                                        <span>Upload Foto...</span>
                                        <!-- The file input field used as target for the file upload widget -->
                                        <input type="file" name="ANGGOTA_PIC[]" id="ANGGOTA_PIC" onchange="previewImageedit(this);" accept="image/*" /> <br>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="short-div">
                                <div class="form-group">
                                    <label for="">Daerah<span class="text-danger">*</span></label>
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
                                    <label for="">Cabang<span class="text-danger">*</span></label>
                                    <div id="selectize-wrapper5" style="position: relative;">
                                        <select name="CABANG_KEY" id="selectize-dropdown5" required="" class="form-control" data-parsley-required>
                                            <option value="">-- Pilih Cabang --</option>]
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label for="">Tingkatan<span class="text-danger">*</span></label>
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
                                    <label for="">No Urut Anggota<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" minlength="3" maxlength="3" oninput="validateInput(this)" required id="EditMutasiAnggota_ID" name="ANGGOTA_ID" value="" placeholder="Inputkan 3 digit nomor urut keanggotaan" data-parsley-required>
                                    <div id="warning-message-edit" style="color: red;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tanggal Bergabung</label><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="datepicker45" name="ANGGOTA_JOIN" placeholder="Pilih tanggal" readonly data-parsley-required required/>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tanggal Resign</label>
                                <input type="text" class="form-control" id="datepicker47" name="ANGGOTA_RESIGN" placeholder="Pilih tanggal"/>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">KTP</label><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="EditMutasiAnggota_KTP" name="ANGGOTA_KTP" value="" placeholder="Inputkan no KTP" data-parsley-required required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Nama</label><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="EditMutasiAnggota_NAMA" name="ANGGOTA_NAMA" value="" data-parsley-required required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Jenis Kelamin</label><span class="text-danger">*</span></label>
                                <select id="EditMutasiAnggota_KELAMIN" name="ANGGOTA_KELAMIN" class="form-control" placeholder="Pilih Jenis Kelamin..." data-parsley-required required>
                                    <option value="">Pilih Jenis Kelamin...</option>
                                    <option value="L">Pria</option>
                                    <option value="P">Wanita</option>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Pekerjaan</label>
                                <input type="text" class="form-control" id="EditMutasiAnggota_PEKERJAAN" name="ANGGOTA_PEKERJAAN" value="">
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Alamat</label>
                                <textarea type="text" rows="4" class="form-control" id="EditMutasiAnggota_ALAMAT" name="ANGGOTA_ALAMAT" value="" data-parsley-required></textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">No HP</label><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="EditMutasiAnggota_HP" name="ANGGOTA_HP" value="" data-parsley-required required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tempat Lahir</label><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="EditMutasiAnggota_TEMPAT_LAHIR" name="ANGGOTA_TEMPAT_LAHIR" value="" data-parsley-required required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tanggal Lahir</label><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="datepicker46" name="ANGGOTA_TANGGAL_LAHIR" placeholder="Pilih tanggal" readonly data-parsley-required required/>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Email</label><span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="EditMutasiAnggota_EMAIL" name="ANGGOTA_EMAIL" data-parsley-required required/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                    <button type="submit" name="submit" id="editdaftaranggota" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>