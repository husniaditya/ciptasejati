<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];

$getAnggotadata = GetQuery("SELECT a.*,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,t.TINGKATAN_NAMA,t.TINGKATAN_GELAR,t.TINGKATAN_SEBUTAN,DATE_FORMAT(a.ANGGOTA_TANGGAL_LAHIR, '%d %M %Y') TGL_LAHIR,DATE_FORMAT(a.ANGGOTA_JOIN, '%d %M %Y') TGL_JOIN,DATE_FORMAT(a.ANGGOTA_RESIGN, '%d %M %Y') TGL_RESIGN FROM m_anggota a
LEFT JOIN m_cabang c ON a.CABANG_ID = c.CABANG_ID
LEFT JOIN m_daerah d ON c.DAERAH_ID = d.DAERAH_ID
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
                <label for="">Cabang</label>
                <select name="CABANGID" id="selectize-select2" required="" class="form-control" data-parsley-required>
                    <option value="">-- Pilih Cabang --</option>
                    <?php
                    foreach ($rows as $filterCabang) {
                        extract($filterCabang);
                        ?>
                        <option value="<?= $CABANG_ID; ?>"><?= $CABANG_DESKRIPSI; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div> 
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Nama</label>
                <input type="text" class="form-control" id="filterANGGOTA_NAMA" name="ANGGOTA_NAMA" value="" placeholder="Input Nama">
            </div> 
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">KTP</label>
                <input type="text" class="form-control" id="filterANGGOTA_KTP" name="ANGGOTA_KTP" value="" placeholder="Input Nomor HP">
            </div> 
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">No HP</label>
                <input type="text" class="form-control" id="filterANGGOTA_HP" name="ANGGOTA_HP" value="" placeholder="Input Nomor HP">
            </div> 
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Tingkatan</label>
                <select name="TINGKATAN_ID" id="selectize-select" required="" class="form-control" data-parsley-required>
                    <option value="">-- Pilih Tingkatan --</option>
                    <?php
                    foreach ($rowt as $filterTingkatan) {
                        extract($filterTingkatan);
                        ?>
                        <option value="<?= $TINGKATAN_ID; ?>"><?= $TINGKATAN_NAMA; ?></option>
                        <?php
                    }
                    ?>
                </select>
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
            <button type="button" id="reloadButton" onclick="clearForm()" class="submit btn btn-teal btn-outline mb5 btn-rounded"><span class="ico-refresh"></span> Clear Filter</button>
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
                                            <li><a data-toggle="modal" href="#ViewPusatdata" class="open-ViewPusatdata" style="color:forestgreen;"><span class="ico-check"></span> Lihat</a></li>
                                            <li><a data-toggle="modal" href="#EditPusatdata" class="open-EditPusatdata" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah</a></li>
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
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-success">Tambah Data Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Daerah<span class="text-danger">*</span></label>
                                <div id="selectize-wrapper" style="position: relative;">
                                    <select name="DAERAH_ID" id="selectize-dropdown" required="" class="form-control" data-parsley-required>
                                        <option value="">-- Pilih Daerah --</option>
                                        <?php
                                        foreach ($rowd as $rowDaerah) {
                                            extract($rowDaerah);
                                            ?>
                                            <option value="<?= $DAERAH_ID; ?>"><?= $DAERAH_DESKRIPSI; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Cabang<span class="text-danger">*</span></label>
                                    <select name="CABANG_ID" id="CABANG_ID" required="" class="form-control" data-parsley-required>
                                        <option value="">Pilih Cabang</option>
                                    </select>
                            </div> 
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">No Urut Anggota<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" maxlength="3" pattern="\d{1,3}" required id="ANGGOTA_ID" name="ANGGOTA_ID" value="" placeholder="Inputkan 3 digit nomor urut keanggotaan" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tingkatan<span class="text-danger">*</span></label>
                                <div id="selectize-wrapper2" style="position: relative;">
                                    <select name="TINGKATAN_ID" id="selectize-dropdown2" required="" class="form-control" data-parsley-required>
                                        <option value="">-- Pilih Tingkatan --</option>
                                        <?php
                                        foreach ($rowt as $rowTingkatan) {
                                            extract($rowTingkatan);
                                            ?>
                                            <option value="<?= $TINGKATAN_ID; ?>"><?= $TINGKATAN_NAMA; ?></option>
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
                                <label for="">Tanggal Bergabung</label>
                                <input type="text" class="form-control" id="datepicker44" name="ANGGOTA_JOIN" placeholder="Pilih tanggal" readonly data-parsley-required/>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">KTP</label>
                                <input type="text" class="form-control" id="ANGGOTA_KTP" name="ANGGOTA_KTP" value="" placeholder="Inputkan no KTP" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Nama</label>
                                <input type="text" class="form-control" id="ANGGOTA_NAMA" name="ANGGOTA_NAMA" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Jenis Kelamin</label>
                                <select id="selectize-select" name="ANGGOTA_KELAMIN" class="form-control" placeholder="Pilih Jenis Kelamin..." data-parsley-required>
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
                                <label for="">Alamat</label>
                                <textarea type="text" rows="4" class="form-control" id="ANGGOTA_ALAMAT" name="ANGGOTA_ALAMAT" value="" data-parsley-required></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">No HP</label>
                                <input type="text" class="form-control" id="ANGGOTA_HP" name="ANGGOTA_HP" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tempat Lahir</label>
                                <input type="text" class="form-control" id="ANGGOTA_TEMPAT_LAHIR" name="ANGGOTA_TEMPAT_LAHIR" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tanggal Lahir</label>
                                <input type="text" class="form-control" id="datepicker4" name="ANGGOTA_TANGGAL_LAHIR" placeholder="Pilih tanggal" readonly data-parsley-required/>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" class="form-control" id="ANGGOTA_EMAIL" name="ANGGOTA_EMAIL" data-parsley-required/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Upload File </label><br>
                                <div>
                                    <input type="file" name="ANGGOTA_PIC[]" id="ANGGOTA_PIC" /><br/>
                                </div>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_NAME">Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" readonly required id="ViewAnggota_NAME" name="GUEST_NAME" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_PHONE">Phone Number</label>
                                <input type="text" class="form-control" id="ViewAnggota_PHONE" name="GUEST_PHONE" readonly value="">
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_RELATION">Relation</label>
                                <input type="text" class="form-control" id="ViewAnggota_RELATION" name="GUEST_RELATION" readonly value="">
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_ADDRESS">Address</label>
                                <textarea type="text" rows="4" class="form-control" id="ViewAnggota_ADDRESS" name="GUEST_ADDRESS" readonly value=""></textarea>
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="GUEST_ID">Guest ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required readonly id="editGUEST_ID" name="GUEST_ID" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_NAME">Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="editGUEST_NAME" name="GUEST_NAME" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_PHONE">Phone Number</label>
                                <input type="text" class="form-control" id="editGUEST_PHONE" name="GUEST_PHONE" value="">
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_RELATION">Relation</label>
                                <input type="text" class="form-control" id="editGUEST_RELATION" name="GUEST_RELATION" value="">
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_ADDRESS">Address</label>
                                <textarea type="text" rows="4" class="form-control" id="editGUEST_ADDRESS" name="GUEST_ADDRESS" value=""></textarea>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                    <button type="submit" name="submit" id="EditAnggota" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>