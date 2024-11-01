<?php

$USER_ID = $_SESSION["LOGINIDUS_CS"];

$getKegiatan = GetQuery("SELECT k.*,case when k.DELETION_STATUS = 0 then 'Aktif' ELSE 'Tidak Aktif' END KEGIATAN_STATUS,a.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(k.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE
FROM c_kegiatan k 
LEFT JOIN m_anggota a ON k.INPUT_BY = a.ANGGOTA_ID AND a.ANGGOTA_STATUS = 0 AND a.DELETION_STATUS = 0");

$getStatus = GetQuery("SELECT * FROM p_param WHERE KATEGORI = 'status'");
$rows = $getStatus->fetchAll(PDO::FETCH_ASSOC);


if ($_SESSION["ADD_BagianKegiatan"] == "Y") {
    ?>
    <!-- START row -->
    <div class="row">
        <div class="col-lg-12">
            <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddKegiatan btn btn-inverse btn-outline mb5 btn-rounded" href="#AddKegiatan"><i class="ico-plus2"></i> Tambah Data Kegiatan</a>
        </div>
    </div>
    <br>
    <!--/ END row -->
    <?php
}
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

<!-- START row -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Tabel Kegiatan</h3>
            </div>
            <table class="table table-striped table-bordered" id="kegiatan-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Kegiatan ID</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Gambar</th>
                        <th>Status</th>
                        <th>Input Oleh</th>
                        <th>Input Tanggal</th>
                    </tr>
                </thead>
                <tbody id="kegiatandata">
                    <?php
                    while($rowKegiatan = $getKegiatan->fetch(PDO::FETCH_ASSOC)) {
                        extract($rowKegiatan);
                        ?>
                        <tr>
                            <td align="center">
                                <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                                    <div class="btn-group" style="margin-bottom:5px;">
                                        <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a data-toggle="modal" href="#ViewKegiatan" class="open-ViewKegiatan" style="color:#222222;" data-id="<?= $KEGIATAN_ID; ?>"><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                                            <li><a data-toggle="modal" href="#EditKegiatan" class="open-EditKegiatan" style="color:cornflowerblue;" data-id="<?= $KEGIATAN_ID; ?>"><span class="ico-edit"></span> Ubah</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#" onclick="deleteKegiatan('<?= $KEGIATAN_ID;?>','deletekegiatan')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td align="center"><?= $KEGIATAN_ID; ?></td>
                            <td align="center"><?= $KEGIATAN_JUDUL; ?></td>
                            <td><?= $KEGIATAN_DESKRIPSI; ?></td>
                            <td align="center"><img src="<?= $KEGIATAN_IMAGE; ?>" alt="<?= $KEGIATAN_IMAGE_NAMA; ?>" width="100" height="100"></td>
                            <td align="center"><?= $KEGIATAN_STATUS; ?></td>
                            <td align="center"><?= $INPUT_BY; ?></td>
                            <td align="center"><?= $INPUT_DATE; ?></td>
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

<div id="AddKegiatan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="AddKegiatan-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Tambah Data Kegiatan</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Judul<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="KEGIATAN_JUDUL" name="KEGIATAN_JUDUL" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea type="text" rows="3" class="form-control" id="KEGIATAN_DESKRIPSI" name="KEGIATAN_DESKRIPSI" value=""></textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Upload File </label><br>
                                <div>
                                    <input type="file" name="KEGIATAN_IMAGE[]" id="KEGIATAN_IMAGE" /><br/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                    <button type="submit" name="submit" id="savekegiatan" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="ViewKegiatan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ViewKegiatan-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Lihat Data Kegiatan</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Judul<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="viewKEGIATAN_JUDUL" name="KEGIATAN_JUDUL" value="" readonly>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="viewKEGIATAN_STATUS" name="KEGIATAN_STATUS" value="" readonly>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea type="text" rows="3" class="form-control" id="viewKEGIATAN_DESKRIPSI" name="KEGIATAN_DESKRIPSI" value="" readonly></textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <img id="viewKEGIATAN_IMAGE" src="" alt="" width="100" height="100">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                    <button type="submit" name="submit" id="savekegiatan" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="EditKegiatan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditKegiatan-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Ubah Data Kegiatan</h3>
                </div>
                <div class="modal-body">
                    <div class="row hidden">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>id<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="editKEGIATAN_ID" name="KEGIATAN_ID" value="" data-parsley-required readonly>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Judul<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="editKEGIATAN_JUDUL" name="KEGIATAN_JUDUL" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status<span class="text-danger">*</span></label>
                                <select name="DELETION_STATUS" id="editKEGIATAN_STATUS" required class="form-control" data-parsley-required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="0">Aktif</option>
                                    <option value="1">Tidak Aktif</option>
                                </select>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea type="text" rows="3" class="form-control" id="editKEGIATAN_DESKRIPSI" name="KEGIATAN_DESKRIPSI" value=""></textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Upload File </label><br>
                                <div>
                                    <input type="file" name="KEGIATAN_IMAGE[]" id="KEGIATAN_IMAGE" /><br/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                    <button type="submit" name="submit" id="updatekegiatan" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>
