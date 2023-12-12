<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];

$getAnggotadata = GetQuery("SELECT a.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,t.TINGKATAN_NAMA,t.TINGKATAN_GELAR,t.TINGKATAN_SEBUTAN,DATE_FORMAT(a.ANGGOTA_TANGGAL_LAHIR, '%d %M %Y') TGL_LAHIR,DATE_FORMAT(a.ANGGOTA_JOIN, '%d %M %Y') TGL_JOIN,DATE_FORMAT(a.ANGGOTA_RESIGN, '%d %M %Y') TGL_RESIGN,RIGHT(a.ANGGOTA_ID,3) SHORT_ID FROM m_anggota a
LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID");

$getDaerah = GetQuery("select * from m_daerah where DELETION_STATUS = 0");
$getCabang = GetQuery("select * from m_cabang where DELETION_STATUS = 0");
$getTingkatan = GetQuery("select * from m_tingkatan where DELETION_STATUS = 0");
// Fetch all rows into an array
$rowd = $getDaerah->fetchAll(PDO::FETCH_ASSOC);
$rows = $getCabang->fetchAll(PDO::FETCH_ASSOC);
$rowt = $getTingkatan->fetchAll(PDO::FETCH_ASSOC);
?>

<h4>Filter Anggota</h4>
<form method="post" class="form filterAnggota" id="filterAnggota">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Daerah</label>
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
                <label for="">Cabang</label>
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
                <label for="">ID Anggota</label>
                <input type="text" class="form-control" id="filterANGGOTA_ID" name="ANGGOTA_ID" value="" placeholder="Input ID Anggota">
            </div> 
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Nama</label>
                <input type="text" class="form-control" id="filterANGGOTA_NAMA" name="ANGGOTA_NAMA" value="" placeholder="Input Nama">
            </div> 
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">KTP</label>
                <input type="text" class="form-control" id="filterANGGOTA_KTP" name="ANGGOTA_KTP" value="" placeholder="Input KTP">
            </div> 
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">No HP</label>
                <input type="text" class="form-control" id="filterANGGOTA_HP" name="ANGGOTA_HP" value="" placeholder="Input Nomor HP">
            </div> 
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Tanggal Join</label>
                <input type="text" class="form-control" id="datepicker41" name="ANGGOTA_JOIN" placeholder="Pilih tanggal" readonly/>
            </div> 
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" align="center">
            <button type="button" id="reloadButton" onclick="clearForm()" class="submit btn btn-teal btn-outline mb5 btn-rounded"><span class="ico-refresh"></span> Reset Filter</button>
        </div>
    </div>
</form>
<hr>
<!-- START row -->
<div class="row">
    <div class="col-lg-12">
        <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddAnggota btn btn-inverse btn-outline mb5 btn-rounded" href="#AddAnggota"><i class="ico-plus2"></i> Tambah Data Anggota</a>
    </div>
</div>
<br>
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
                        <th>Daerah </th>
                        <th>Cabang </th>
                        <th>Sabuk </th>
                        <th>Tingkatan </th>
                        <th>Gelar </th>
                        <th>KTP</th>
                        <th>Nama</th>
                        <th>L/P</th>
                        <th>TTL</th>
                        <th>No HP</th>
                        <th>Email</th>
                        <th>Tgl Bergabung</th>
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
                                            <li><a data-toggle="modal" href="#ViewAnggota" class="open-ViewAnggota" style="color:forestgreen;" data-key="<?= $ANGGOTA_KEY; ?>" data-id="<?= $ANGGOTA_ID; ?>" data-shortid="<?= $SHORT_ID; ?>" data-daerahkey="<?= $DAERAH_KEY;?>" data-daerahdes="<?= $DAERAH_DESKRIPSI;?>" data-cabangkey="<?= $CABANG_KEY; ?>" data-cabangdes="<?= $CABANG_DESKRIPSI; ?>" data-tingkatanid=<?= $TINGKATAN_ID; ?> data-tingkatannama="<?= $TINGKATAN_NAMA; ?>" data-ktp="<?= $ANGGOTA_KTP; ?>" data-nama="<?= $ANGGOTA_NAMA; ?>" data-alamat="<?= $ANGGOTA_ALAMAT;?>" data-pekerjaan="<?= $ANGGOTA_PEKERJAAN; ?>" data-kelamin="<?= $ANGGOTA_KELAMIN; ?>" data-tempatlahir="<?= $ANGGOTA_TEMPAT_LAHIR; ?>" data-tanggallahir="<?= $ANGGOTA_TANGGAL_LAHIR; ?>" data-hp="<?= $ANGGOTA_HP; ?>" data-email="<?= $ANGGOTA_EMAIL; ?>" data-pic="<?= $ANGGOTA_PIC; ?>" data-join="<?= $ANGGOTA_JOIN; ?>" data-resign="<?= $ANGGOTA_RESIGN; ?>"><span class="ico-check"></span> Lihat</a></li>
                                            <li><a data-toggle="modal" href="#EditAnggota" class="open-EditAnggota" style="color:cornflowerblue;" data-key="<?= $ANGGOTA_KEY; ?>" data-id="<?= $ANGGOTA_ID; ?>" data-shortid="<?= $SHORT_ID; ?>" data-daerahkey="<?= $DAERAH_KEY;?>" data-daerahdes="<?= $DAERAH_DESKRIPSI;?>" data-cabangkey="<?= $CABANG_KEY; ?>" data-cabangdes="<?= $CABANG_DESKRIPSI; ?>" data-tingkatanid=<?= $TINGKATAN_ID; ?> data-tingkatannama="<?= $TINGKATAN_NAMA; ?>" data-ktp="<?= $ANGGOTA_KTP; ?>" data-nama="<?= $ANGGOTA_NAMA; ?>" data-alamat="<?= $ANGGOTA_ALAMAT;?>" data-pekerjaan="<?= $ANGGOTA_PEKERJAAN; ?>" data-kelamin="<?= $ANGGOTA_KELAMIN; ?>" data-tempatlahir="<?= $ANGGOTA_TEMPAT_LAHIR; ?>" data-tanggallahir="<?= $ANGGOTA_TANGGAL_LAHIR; ?>" data-hp="<?= $ANGGOTA_HP; ?>" data-email="<?= $ANGGOTA_EMAIL; ?>" data-pic="<?= $ANGGOTA_PIC; ?>" data-join="<?= $ANGGOTA_JOIN; ?>" data-resign="<?= $ANGGOTA_RESIGN; ?>"><span class="ico-edit"></span> Ubah</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#" onclick="deletedaftaranggota('<?= $ANGGOTA_KEY;?>','deleteevent')" style="color:firebrick;"><span class="ico-trash"></span> Hapus</a></li>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td><?= $ANGGOTA_ID; ?></td>
                            <td align="center"><?= $DAERAH_DESKRIPSI; ?></td>
                            <td align="center"><?= $CABANG_DESKRIPSI; ?></td>
                            <td align="center"><?= $TINGKATAN_NAMA; ?></td>
                            <td align="center"><?= $TINGKATAN_SEBUTAN; ?></td>
                            <td align="center"><?= $TINGKATAN_GELAR; ?></td>
                            <td align="center"><?= $ANGGOTA_KTP; ?></td>
                            <td><?= $ANGGOTA_NAMA; ?></td>
                            <td align="center"><?= $ANGGOTA_KELAMIN; ?></td>
                            <td><?= $ANGGOTA_TEMPAT_LAHIR; ?> <br> <?= $TGL_LAHIR; ?></td>
                            <td><?= $ANGGOTA_HP; ?></td>
                            <td><?= $ANGGOTA_EMAIL; ?></td>
                            <td><?= $TGL_JOIN; ?></td>
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
                    <h3 class="semibold modal-title text-success">Tambah Data Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" align="center">
                            <div class="form-group">
                                <!-- PUT THE SAMPLE UPLOAD PHOTO HERE -->
                                <label for="">Foto Anggota </label><br>
                                <div id="preview-container">
                                    <img id="preview-image" src="#" alt="Preview" style="max-width: 250px; max-height: 250px;" />
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
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="short-div">
                                <div class="form-group">
                                    <label for="">Daerah<span class="text-danger">*</span></label>
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
                                    <label for="">Cabang<span class="text-danger">*</span></label>
                                    <div id="selectize-wrapper3" style="position: relative;">
                                        <select name="CABANG_KEY" id="selectize-dropdown3" required="" class="form-control" data-parsley-required>
                                            <option value="">-- Pilih Cabang --</option>]
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label for="">Tingkatan<span class="text-danger">*</span></label>
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
                                    <label for="">No Urut Anggota<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" minlength="3" maxlength="3" pattern="\d{1,3}" required id="ANGGOTA_ID" name="ANGGOTA_ID" value="" placeholder="Inputkan 3 digit nomor urut keanggotaan" data-parsley-required>
                                </div> 
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tanggal Bergabung</label><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="datepicker44" name="ANGGOTA_JOIN" placeholder="Pilih tanggal" readonly required data-parsley-required/>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">KTP</label><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ANGGOTA_KTP" name="ANGGOTA_KTP" value="" placeholder="Inputkan no KTP" data-parsley-required required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Nama</label><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ANGGOTA_NAMA" name="ANGGOTA_NAMA" value="" data-parsley-required required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Jenis Kelamin</label><span class="text-danger">*</span></label>
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
                                <label for="">Pekerjaan</label>
                                <input type="text" class="form-control" id="ANGGOTA_PEKERJAAN" name="ANGGOTA_PEKERJAAN" value="">
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Alamat</label><span class="text-danger">*</span></label>
                                <textarea type="text" rows="4" class="form-control" id="ANGGOTA_ALAMAT" name="ANGGOTA_ALAMAT" value="" data-parsley-required required></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">No HP</label><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ANGGOTA_HP" name="ANGGOTA_HP" value="" data-parsley-required required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tempat Lahir</label><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ANGGOTA_TEMPAT_LAHIR" name="ANGGOTA_TEMPAT_LAHIR" value="" data-parsley-required required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tanggal Lahir</label><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="datepicker4" name="ANGGOTA_TANGGAL_LAHIR" placeholder="Pilih tanggal" readonly data-parsley-required required/>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Email</label><span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="ANGGOTA_EMAIL" name="ANGGOTA_EMAIL" data-parsley-required required/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Cancel</button>
                    <button type="submit" name="submit" id="savedaftaranggota" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
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
                                    <input type="text" class="form-control" id="viewANGGOTA_ID" name="ANGGOTA_ID" value="" data-parsley-required readonly>
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
                                                <input type="text" class="form-control" id="viewANGGOTA_JOIN" name="ANGGOTA_JOIN" value="" data-parsley-required readonly>
                                            </div> 
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">KTP</label>
                                                <input type="text" class="form-control" id="viewANGGOTA_KTP" name="ANGGOTA_KTP" value="" data-parsley-required readonly>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Nama</label>
                                                <input type="text" class="form-control" id="viewANGGOTA_NAMA" name="ANGGOTA_NAMA" value="" data-parsley-required readonly>
                                            </div> 
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Jenis Kelamin</label>
                                                <input type="text" class="form-control" id="viewANGGOTA_KELAMIN" name="ANGGOTA_KELAMIN" value="" data-parsley-required readonly>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Pekerjaan</label>
                                                <input type="text" class="form-control" id="viewANGGOTA_PEKERJAAN" name="ANGGOTA_PEKERJAAN" value="" readonly>
                                            </div> 
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Alamat</label>
                                                <textarea type="text" rows="4" class="form-control" id="viewANGGOTA_ALAMAT" name="ANGGOTA_ALAMAT" value="" data-parsley-required readonly></textarea>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">No HP</label>
                                                <input type="text" class="form-control" id="viewANGGOTA_HP" name="ANGGOTA_HP" value="" data-parsley-required readonly>
                                            </div> 
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Tempat Lahir</label>
                                                <input type="text" class="form-control" id="viewANGGOTA_TEMPAT_LAHIR" name="ANGGOTA_TEMPAT_LAHIR" value="" data-parsley-required readonly>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Tanggal Lahir</label>
                                                <input type="text" class="form-control" id="viewANGGOTA_TANGGAL_LAHIR" name="ANGGOTA_TANGGAL_LAHIR" readonly data-parsley-required/>
                                            </div> 
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Email</label>
                                                <input type="email" class="form-control" id="viewANGGOTA_EMAIL" name="ANGGOTA_EMAIL" data-parsley-required readonly/>
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

<div id="EditAnggota" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditAnggota-form" method="post" class="form" data-parsley-validate>
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
                            <input type="text" class="form-control" required id="editANGGOTA_KEY" name="ANGGOTA_KEY" value="" data-parsley-required readonly>
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
                                    <input type="text" class="form-control" minlength="3" maxlength="3" pattern="\d{1,3}" required id="editANGGOTA_ID" name="ANGGOTA_ID" value="" placeholder="Inputkan 3 digit nomor urut keanggotaan" data-parsley-required>
                                </div> 
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tanggal Bergabung</label>
                                <input type="text" class="form-control" id="datepicker45" name="ANGGOTA_JOIN" placeholder="Pilih tanggal" readonly data-parsley-required/>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">KTP</label>
                                <input type="text" class="form-control" id="editANGGOTA_KTP" name="ANGGOTA_KTP" value="" placeholder="Inputkan no KTP" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Nama</label>
                                <input type="text" class="form-control" id="editANGGOTA_NAMA" name="ANGGOTA_NAMA" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Jenis Kelamin</label>
                                <select id="editANGGOTA_KELAMIN" name="ANGGOTA_KELAMIN" class="form-control" placeholder="Pilih Jenis Kelamin..." data-parsley-required>
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
                                <label for="">Pekerjaan</label>
                                <input type="text" class="form-control" id="editANGGOTA_PEKERJAAN" name="ANGGOTA_PEKERJAAN" value="">
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Alamat</label>
                                <textarea type="text" rows="4" class="form-control" id="editANGGOTA_ALAMAT" name="ANGGOTA_ALAMAT" value="" data-parsley-required></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">No HP</label><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editANGGOTA_HP" name="ANGGOTA_HP" value="" data-parsley-required required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tempat Lahir</label>
                                <input type="text" class="form-control" id="editANGGOTA_TEMPAT_LAHIR" name="ANGGOTA_TEMPAT_LAHIR" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tanggal Lahir</label>
                                <input type="text" class="form-control" id="datepicker46" name="ANGGOTA_TANGGAL_LAHIR" placeholder="Pilih tanggal" readonly data-parsley-required/>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" class="form-control" id="editANGGOTA_EMAIL" name="ANGGOTA_EMAIL" data-parsley-required/>
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