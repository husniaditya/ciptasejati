<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];

if ($USER_AKSES == "Administrator") {
    $getAnggotadata = GetQuery("SELECT a.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,t.TINGKATAN_NAMA,t.TINGKATAN_GELAR,t.TINGKATAN_SEBUTAN,DATE_FORMAT(a.ANGGOTA_TANGGAL_LAHIR, '%d %M %Y') TGL_LAHIR,DATE_FORMAT(a.ANGGOTA_JOIN, '%d %M %Y') TGL_JOIN,DATE_FORMAT(a.ANGGOTA_RESIGN, '%d %M %Y') TGL_RESIGN,RIGHT(a.ANGGOTA_ID,3) SHORT_ID, CASE WHEN ANGGOTA_STATUS = 0 THEN 'Aktif' WHEN ANGGOTA_STATUS = 1 THEN 'Non Aktif' ELSE 'Mutasi' END STATUS_DES FROM m_anggota a
    LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID");
} else {
    $getAnggotadata = GetQuery("SELECT a.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,t.TINGKATAN_NAMA,t.TINGKATAN_GELAR,t.TINGKATAN_SEBUTAN,DATE_FORMAT(a.ANGGOTA_TANGGAL_LAHIR, '%d %M %Y') TGL_LAHIR,DATE_FORMAT(a.ANGGOTA_JOIN, '%d %M %Y') TGL_JOIN,DATE_FORMAT(a.ANGGOTA_RESIGN, '%d %M %Y') TGL_RESIGN,RIGHT(a.ANGGOTA_ID,3) SHORT_ID, CASE WHEN ANGGOTA_STATUS = 0 THEN 'Aktif' WHEN ANGGOTA_STATUS = 1 THEN 'Non Aktif' ELSE 'Mutasi' END STATUS_DES FROM m_anggota a
    LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
    WHERE a.CABANG_KEY = '$USER_CABANG' and a.ANGGOTA_AKSES != 'Administrator'");
}

$getDaerah = GetQuery("select * from m_daerah where DELETION_STATUS = 0");
$getCabang = GetQuery("select * from m_cabang where DELETION_STATUS = 0");
$getTingkatan = GetQuery("select * from m_tingkatan where DELETION_STATUS = 0 order by TINGKATAN_LEVEL");
// Fetch all rows into an array
$rowd = $getDaerah->fetchAll(PDO::FETCH_ASSOC);
$rows = $getCabang->fetchAll(PDO::FETCH_ASSOC);
$rowt = $getTingkatan->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .dataTables_wrapper {
        width: 100%;
        overflow: auto;
    }
    table.dataTable {
        width: 100% !important;
    }
</style>

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
if ($_SESSION["ADD_DaftarAnggota"] == "Y") {
    ?>
    <div class="row">
        <div class="col-lg-12">
            <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddAnggota btn btn-inverse btn-outline mb5 btn-rounded" href="#AddAnggota"><i class="ico-plus2"></i> Tambah Data Anggota</a>
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
                <tbody id="anggotadata">
                    <?php
                    while ($rowAnggota = $getAnggotadata->fetch(PDO::FETCH_ASSOC)) {
                        extract($rowAnggota);
                        ?>
                        <tr>
                            <td align="center">
                                <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                                    <div class="btn-group" style="margin-bottom:5px;">
                                        <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu">
                                        <?php
                                        if ($_SESSION['VIEW_DaftarAnggota'] == "Y") {
                                            ?>
                                            <li><a data-toggle="modal" href="#ViewAnggota" class="open-ViewAnggota" style="color:#222222;" data-key="<?= $ANGGOTA_KEY; ?>" data-id="<?= $ANGGOTA_ID; ?>" data-shortid="<?= $SHORT_ID; ?>" data-daerahkey="<?= $DAERAH_KEY;?>" data-daerahdes="<?= $DAERAH_DESKRIPSI;?>" data-cabangkey="<?= $CABANG_KEY; ?>" data-cabangdes="<?= $CABANG_DESKRIPSI; ?>" data-tingkatanid=<?= $TINGKATAN_ID; ?> data-tingkatannama="<?= $TINGKATAN_NAMA; ?>" data-ktp="<?= $ANGGOTA_KTP; ?>" data-nama="<?= $ANGGOTA_NAMA; ?>" data-alamat="<?= $ANGGOTA_ALAMAT;?>" data-pekerjaan="<?= $ANGGOTA_PEKERJAAN; ?>" data-agama="<?= $ANGGOTA_AGAMA; ?>" data-kelamin="<?= $ANGGOTA_KELAMIN; ?>" data-tempatlahir="<?= $ANGGOTA_TEMPAT_LAHIR; ?>" data-tanggallahir="<?= $ANGGOTA_TANGGAL_LAHIR; ?>" data-hp="<?= $ANGGOTA_HP; ?>" data-email="<?= $ANGGOTA_EMAIL; ?>" data-pic="<?= $ANGGOTA_PIC; ?>" data-join="<?= $ANGGOTA_JOIN; ?>" data-resign="<?= $ANGGOTA_RESIGN; ?>" data-akses="<?= $ANGGOTA_AKSES; ?>" data-status="<?= $ANGGOTA_STATUS; ?>" data-statusdes="<?= $STATUS_DES; ?>" data-ranting="<?= $ANGGOTA_RANTING; ?>"><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                                            <?php
                                        }
                                        if ($_SESSION['EDIT_DaftarAnggota'] == "Y") {
                                            ?>
                                            <li><a data-toggle="modal" href="#EditAnggota" class="open-EditAnggota" style="color:cornflowerblue;" data-key="<?= $ANGGOTA_KEY; ?>" data-id="<?= $ANGGOTA_ID; ?>" data-shortid="<?= $SHORT_ID; ?>" data-daerahkey="<?= $DAERAH_KEY;?>" data-daerahdes="<?= $DAERAH_DESKRIPSI;?>" data-cabangkey="<?= $CABANG_KEY; ?>" data-cabangdes="<?= $CABANG_DESKRIPSI; ?>" data-tingkatanid=<?= $TINGKATAN_ID; ?> data-tingkatannama="<?= $TINGKATAN_NAMA; ?>" data-ktp="<?= $ANGGOTA_KTP; ?>" data-nama="<?= $ANGGOTA_NAMA; ?>" data-alamat="<?= $ANGGOTA_ALAMAT;?>" data-pekerjaan="<?= $ANGGOTA_PEKERJAAN; ?>" data-agama="<?= $ANGGOTA_AGAMA; ?>" data-kelamin="<?= $ANGGOTA_KELAMIN; ?>" data-tempatlahir="<?= $ANGGOTA_TEMPAT_LAHIR; ?>" data-tanggallahir="<?= $ANGGOTA_TANGGAL_LAHIR; ?>" data-hp="<?= $ANGGOTA_HP; ?>" data-email="<?= $ANGGOTA_EMAIL; ?>" data-pic="<?= $ANGGOTA_PIC; ?>" data-join="<?= $ANGGOTA_JOIN; ?>" data-resign="<?= $ANGGOTA_RESIGN; ?>" data-akses="<?= $ANGGOTA_AKSES; ?>" data-status="<?= $ANGGOTA_STATUS; ?>" data-statusdes="<?= $STATUS_DES; ?>" data-ranting="<?= $ANGGOTA_RANTING; ?>"><span class="ico-edit"></span> Ubah</a></li>
                                            <?php
                                        }
                                        if ($_SESSION['DELETE_DaftarAnggota'] == "Y") {
                                            ?>
                                            <li class="divider"></li>
                                            <li><a href="#" onclick="deletedaftaranggota('<?= $ANGGOTA_KEY;?>','deleteevent')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                                            <?php
                                        }
                                        ?>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td><?= $ANGGOTA_ID; ?></td>
                            <td><?= $ANGGOTA_NAMA; ?></td>
                            <td><?= $ANGGOTA_TEMPAT_LAHIR; ?> <br> <?= $TGL_LAHIR; ?></td>
                            <td align="center"><?= $ANGGOTA_KELAMIN; ?></td>
                            <td align="center"><?= $TINGKATAN_NAMA; ?></td>
                            <td align="center"><?= $TINGKATAN_SEBUTAN; ?></td>
                            <td align="center"><?= $TINGKATAN_GELAR; ?></td>
                            <td align="center"><?= $ANGGOTA_KTP; ?></td>
                            <td><?= $ANGGOTA_HP; ?></td>
                            <td><?= $ANGGOTA_EMAIL; ?></td>
                            <td align="center"><?= $ANGGOTA_RANTING; ?></td>
                            <td align="center"><?= $CABANG_DESKRIPSI; ?></td>
                            <td align="center"><?= $DAERAH_DESKRIPSI; ?></td>
                            <td><?= $TGL_JOIN; ?></td>
                            <td><?= $STATUS_DES; ?></td>
                            <td><?= $TGL_RESIGN; ?></td>
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
                                        <div id="selectize-wrapper" style="position: relative;">
                                            <select name="DAERAH_KEY" id="selectize-dropdown" required="" class="form-control" data-parsley-required>
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
                                    <input type="text" class="form-control" minlength="3" maxlength="3" oninput="validateInput(this)" required id="ANGGOTA_ID" name="ANGGOTA_ID" value="" placeholder="Inputkan 3 digit nomor urut keanggotaan" data-parsley-required>
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
                                    <option value="User">User</option>
                                    <option value="Koordinator">Koordinator</option>
                                    <option value="Pengurus">Pengurus</option>
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
                                    <label>No Urut Anggota<span class="text-danger">*</span></label>
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
                                    <input type="text" class="form-control" minlength="3" maxlength="3" oninput="validateInput(this)" required id="editANGGOTA_ID" name="ANGGOTA_ID" value="" placeholder="Inputkan 3 digit nomor urut keanggotaan" data-parsley-required readonly>
                                    <div id="warning-message-edit" style="color: red;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Bergabung</label><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editANGGOTA_JOIN" name="ANGGOTA_JOIN" value="" data-parsley-required readonly>
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
                                    <option value="User">User</option>
                                    <option value="Koordinator">Koordinator</option>
                                    <option value="Pengurus">Pengurus</option>
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